<?php

namespace Core;

use Core\Traits\QueryMysql;
use Exception;
use stdClass;

abstract class Model
{
    use QueryMysql;

    protected string $table;
    protected string $primaryKey = 'id';

    public function getTable(): string
    {
        $class = get_called_class();
        if (empty($this->table)) {
            $table = explode('\\', $class);
            $table = strtolower(end($table));
            if (get_plural($table)) {
                return get_plural($table);
            }
            throw new Exception("La table $table n'existe pas", 14);
        }
        return $this->table;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    protected static function getCurrentModel()
    {
        $current = get_called_class();
        return new $current();
    }

    /**
     * Récupere l'enregistrement de l'id
     * @param int $id
     * @return Model
     */
    public static function find(int $id): Model
    {
        return self::where(
            self::getCurrentModel()->getPrimaryKey(),
            $id
        )->first();
    }

    public static function all(): array
    {
        return self::get();
    }

    public static function count(): int
    {
        $count = self::select(
            self::getCurrentModel()->getPrimaryKey() .
                ', COUNT(' .
                self::getCurrentModel()->getPrimaryKey() .
                ') AS count'
        )->map(self::getCurrentModel()->getPrimaryKey(), 'count');
        return (int) array_shift($count);
    }

    public static function create(array $data, string $table = '')
    {
        if (isset($data['created_at']) && isset($data['updated_at'])) {
            $data = self::setDatetime($data);
        }
        $table = $table ?: self::getCurrentModel()->getTable();
        $keys = [];
        $values = [];
        $inter = [];
        foreach ($data as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
            $inter[] = '?';
        }
        $statement =
            'INSERT INTO ' .
            $table .
            ' (' .
            implode(',', $keys) .
            ')';
        $statement .= ' VALUES (' . implode(',', $inter) . ')';
        MysqlDatabase::prepare($statement, $values);
        return self::last();
    }

    public static function update(int $id, array $data)
    {
        $keys = [];
        $values = [];
        if (isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        foreach ($data as $k => $v) {
            $keys[] = sprintf('%s = ?', $k);
            $values[] = $v;
        }
        $values[] = $id;
        $statement =
            'UPDATE ' .
            self::getCurrentModel()->getTable() .
            ' SET ' .
            implode(', ', $keys) .
            ' WHERE ' .
            self::getCurrentModel()->getPrimaryKey() .
            ' = ?';
        MysqlDatabase::prepare($statement, $values);
        return self::find($id);
    }

    public static function delete($id)
    {
        $statement =
            'DELETE FROM ' .
            self::getCurrentModel()->getTable() .
            ' WHERE ' .
            self::getCurrentModel()->getPrimaryKey() .
            ' = ?';
        MysqlDatabase::prepare($statement, [$id]);
        return self::all();
    }

    public function deleteSelf()
    {
        $statement =
            'DELETE FROM ' .
            self::getCurrentModel()->getTable() .
            ' WHERE ' .
            self::getCurrentModel()->getPrimaryKey() .
            ' = ?';
        MysqlDatabase::prepare($statement, [$this->{$this->primaryKey}]);
        return self::all();
    }

    public static function shuffleId(): int
    {
        $pk = self::getCurrentModel()->getPrimaryKey();
        $ids = self::select($pk)->get();
        return (int) $ids[array_rand($ids, 1)]->$pk;
    }

    public function hasMany(string $model, string $foreignKey = '')
    {
        $table = (new $model())->getTable();
        $second = strtolower((new \ReflectionClass($this))->getShortName());
        $foreignKey = $foreignKey ?: $second . '_' . $this->primaryKey;
        return MysqlDatabase::prepare(
            "
		SELECT {$table}.*
		FROM {$table}
		LEFT JOIN {$this->getTable()}
		ON {$table}.{$foreignKey} = " .
                $this->getTable() .
                '.' .
                $this->primaryKey .
                ' WHERE ' .
                $this->getTable() .
                '.' .
                $this->primaryKey .
                " = ?
		",
            [$this->{$this->primaryKey}],
            $model
        );
    }

    public function belongsTo(string $model, string $foreignKey = '')
    {
        $table = new $model();
        $second = strtolower((new \ReflectionClass($table))->getShortName());
        $foreignKey = $foreignKey ?: $second . '_' . $table->primaryKey;
        return MysqlDatabase::prepare(
            "
		SELECT {$table->getTable()}.*
		FROM {$table->getTable()}
		LEFT JOIN " .
                $this->getTable() .
                "
		ON " .
                $table->getTable() .
                '.' .
                $table->primaryKey .
                ' = ' .
                $this->getTable() .
                '.' .
                $foreignKey .
                "
		WHERE " .
                $this->getTable() .
                '.' .
                $this->primaryKey .
                " = ?
		",
            [$this->{$this->primaryKey}],
            $model,
            true
        );
    }

    public function belongsToMany(string $model, string $pivot = '', string $foreignKeyOne = '', string $foreignKeyTwo = '')
    {
        $first = strtolower((new \ReflectionClass($this))->getShortName());
        $second = strtolower((new \ReflectionClass($model))->getShortName());
        $secondInstance = new $model();
        $table = $secondInstance->getTable();
        $all = MysqlDatabase::query(
            'SHOW TABLES FROM ' . config('connection.mysql.name')
        );
        if (empty($pivot)) {
            foreach ($all as $a) {
                if (
                    strpos(
                        $a->{'Tables_in_' . config('connection.mysql.name')},
                        '_'
                    )
                ) {
                    $atest = explode(
                        '_',
                        $a->{'Tables_in_' . config('connection.mysql.name')}
                    );
                    if (in_array($first, $atest) && in_array($second, $atest)) {
                        $pivot =
                            $a->{'Tables_in_' .
                                config('connection.mysql.name')};
                    }
                }
            }
        }

        $foreignKeyOne =
            $foreignKeyOne ?: $first . '_' . $this->getPrimaryKey();
        $foreignKeyTwo =
            $foreignKeyTwo ?: $second . '_' . $secondInstance->getPrimaryKey();
        return MysqlDatabase::prepare(
            "
		SELECT {$table}.*, {$pivot}.*
		FROM {$table}
		LEFT JOIN {$pivot}
		ON {$pivot}.{$foreignKeyTwo} = {$table}.{$secondInstance->getPrimaryKey()}
		WHERE {$pivot}.{$foreignKeyOne} = ?
		",
            [$this->{$this->getPrimaryKey()}],
            $model
        );
    }

    public function hasOne(string $model, string $foreignKey = '')
    {
        $table = (new $model())->getTable();
        $second = strtolower((new \ReflectionClass($this))->getShortName());
        $foreignKey = $foreignKey ?: $second . '_' . $this->primaryKey;
        return MysqlDatabase::prepare(
            "
		SELECT {$table}.*
		FROM {$table}
		LEFT JOIN {$this->getTable()}
		ON {$table}.{$foreignKey} = " .
                $this->getTable() .
                '.' .
                $this->primaryKey .
                ' WHERE ' .
                $this->getTable() .
                '.' .
                $this->primaryKey .
                " = ? LIMIT 1
		",
            [$this->{$this->primaryKey}],
            $model,
            true
        );
    }

    public function with(string $relation, array $properties = [])
    {
        $instance = $this->{$relation};
        if (!is_array($instance)) {
            if (!$properties) {
                foreach ($instance as $key => $value) {
                    if (!in_array($key, [$instance->getPrimaryKey()])) {
                        if (property_exists($this, $key)) {
                            $this->{"{$relation}_{$key}"} = $value;
                        } else {
                            $this->{$key} = $value;
                        }
                    }
                }
            } else {
                foreach ($properties as $property) {
                    if (property_exists($this, $property)) {
                        $this->{"{$relation}_{$property}"} = $instance->{$property};
                    } else {
                        $this->{$property} = $instance->{$property};
                    }
                }
            }
        } else {
            if ($properties) {
                foreach ($instance as $key => $model) {
                    $instance[$key] = new stdClass();
                    foreach ($properties as $property) {
                        $instance[$key]->{$property} = $model->{$property};
                    }
                }
            }
            $this->{$relation} = $instance;
        }
        return $this;
    }

    public function modify(array $data)
    {
        $keys = [];
        $values = [];
        if (isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        foreach ($data as $k => $v) {
            $keys[] = sprintf('%s = ?', $k);
            $values[] = $v;
        }
        $values[] = $this->{$this->getPrimaryKey()};
        $statement =
            'UPDATE ' .
            self::getCurrentModel()->getTable() .
            ' SET ' .
            implode(', ', $keys) .
            ' WHERE ' .
            self::getCurrentModel()->getPrimaryKey() .
            ' = ?';
        MysqlDatabase::prepare($statement, $values);
        return $this;
    }

    public function modifyMany(string $relation, $id, array $data)
    {
        $keys = [];
        $values = [];
        if (isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        foreach ($data as $k => $v) {
            $keys[] = sprintf('%s = ?', $k);
            $values[] = $v;
        }
        $values[] = $this->{$this->getPrimaryKey()};
        $statement =
            'UPDATE ' .
            self::getCurrentModel()->getTable() .
            ' SET ' .
            implode(', ', $keys) .
            ' WHERE ' .
            self::getCurrentModel()->getPrimaryKey() .
            ' = ?';
        MysqlDatabase::prepare($statement, $values);
        return $this;
    }

    public function attach($table, array $data)
    {
        $data[$this->primaryKey] = $this->{$this->primaryKey};
        return self::create($data, $table);
    }

    public function __get($key)
    {
        if (empty($this->$key)) {
            if (method_exists($this, 'get' . ucfirst($key))) {
                return $this->{'get' . ucfirst($key)}();
            }
            return $this->$key();
        }
    }
}
