<?php

namespace Core;

use Core\Traits\QueryMysql;
use Exception;
use ReflectionClass;
use ReflectionException;
use stdClass;

abstract class Model
{
    use QueryMysql;

    protected string $table;
    protected string $primaryKey = 'id';
    /**
     * Stocke le champ de date pour
     * automatiser les enregistrements
     * ['creation' => colonne création, 'update' => colonne modifié]
     *
     * @var array
     */
    protected array $datetimes = [];

    /**
     * Récupère le nom de la table
     * du model actuel
     *
     * @return string
     * @throws Exception
     */
    public function getTable(): string
    {
        $class = get_called_class();
        if(empty($this->table)){
            $table = explode('\\', $class);
            $table = strtolower(end($table));
            if(get_plural($table)){
                return get_plural($table);
            }
            throw new Exception("La table $table n'existe pas", 14);
        }
        return $this->table;
    }

    /**
     * Récupère le nom de la clé primaire
     * du model actuel
     *
     * @return string|null
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey ?? null;
    }

    /**
     * Récupère l'instance du model actuel
     *
     * @return object
     * @throws ReflectionException
     */
    protected static function getCurrentModel()
    {
        $current = new ReflectionClass(get_called_class());
        return $current->newInstance();
    }

    /**
     * Récupère un enregistrement
     *
     * @param mixed $id
     * @return $this|bool
     * @throws Exception
     */
    public static function find($id)
    {
        return self::where(
            self::getCurrentModel()->getPrimaryKey(),
            $id
        )->first();
    }

    /**
     * Retourn tous les enregistrement
     *
     * @return array
     */
    public static function all(): array
    {
        return self::get();
    }

    /**
     * Retourne le nombre d'enregistrement
     *
     * @return int
     * @throws ReflectionException
     */
    public static function count(): int
    {
        $count = self::select(
            self::getCurrentModel()->getPrimaryKey() .
            ', COUNT(' .
            self::getCurrentModel()->getPrimaryKey() .
            ') AS count'
        )->map(self::getCurrentModel()->getPrimaryKey(), 'count');
        return (int)array_shift($count);
    }

    /**
     * Enregistrement une nouvelle donnée
     * dans la table actuel
     *
     * @param array $data
     * @param string $table
     * @return $this|bool
     * @throws ReflectionException
     */
    public static function create(array $data, string $table = '')
    {
        $data = self::setDatetime($data);
        $data = self::filterColumns($data);
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

    /**
     * Modifie un enregistrement
     * de la table actuel
     *
     * @param $id
     * @param array $data
     * @return bool|$this
     * @throws ReflectionException
     */
    public static function update($id, array $data)
    {
        $keys = [];
        $values = [];
        $data = self::setUpdate($data);
        $data = self::filterColumns($data);
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

    /**
     * Supprime un enregistrement
     * de la table actuel
     *
     * @param $id
     * @return array
     * @throws ReflectionException
     */
    public static function delete($id): array
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

    /**
     * Supprime l'instance actuel
     * dans sa table
     *
     * @return array
     * @throws ReflectionException
     */
    public function deleteSelf(): array
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

    /**
     * Récupère une valeur d'une
     * clé primaire au hasard
     *
     * @return string
     * @throws ReflectionException
     */
    public static function shuffleId(): string
    {
        $pk = self::getCurrentModel()->getPrimaryKey();
        $ids = self::select($pk)->get();
        return $ids[array_rand($ids, 1)]->{$pk};
    }

    /**
     * Récupère les enregistrements de
     * la table porteuse de la clé primaire
     *
     * @param string $model
     * @param string $foreignKey
     * @return array|bool|mixed
     * @throws ReflectionException
     */
    public function hasMany(string $model, string $foreignKey = '')
    {
        $table = (new $model())->getTable();
        $second = strtolower((new ReflectionClass($this))->getShortName());
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

    /**
     * Récupère l'enregistrement de la table
     * lié avec la table actuel par la clé étrangère
     * @param string $model
     * @param string $foreignKey
     * @return array|bool|mixed
     * @throws ReflectionException
     * @example One to Many
     *
     */
    public function belongsTo(string $model, string $foreignKey = '')
    {
        $table = new $model();
        $second = strtolower((new ReflectionClass($table))->getShortName());
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

    /**
     * Récupère les enregistrements de la table
     * lié avec la table actuel par la table d'association
     *
     * @param string $model
     * @param string $pivot
     * @param string $foreignKeyOne
     * @param string $foreignKeyTwo
     * @return array|bool|mixed
     * @throws ReflectionException
     * @example Many to Many
     */
    public function belongsToMany(string $model, string $pivot = '', string $foreignKeyOne = '', string $foreignKeyTwo = '')
    {
        $first = strtolower((new ReflectionClass($this))->getShortName());
        $second = strtolower((new ReflectionClass($model))->getShortName());
        $secondInstance = new $model();
        $table = $secondInstance->getTable();
        $all = MysqlDatabase::query(
            'SHOW TABLES FROM ' . config('connection.mysql.name')
        );
        if(empty($pivot)){
            foreach ($all as $a) {
                if(
                strpos(
                    $a['Tables_in_' . config('connection.mysql.name')],
                    '_'
                )
                ){
                    $atest = explode(
                        '_',
                        $a['Tables_in_' . config('connection.mysql.name')]
                    );
                    if(in_array($first, $atest) && in_array($second, $atest)){
                        $pivot =
                            $a['Tables_in_' . config('connection.mysql.name')];
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

    /**
     * Récupère l'enregistrement de
     * la table porteuse de la clé primaire
     * @param string $model
     * @param string $foreignKey
     * @return array|bool|mixed
     * @throws ReflectionException
     * @example One to One
     *
     */
    public function hasOne(string $model, string $foreignKey = '')
    {
        $table = (new $model())->getTable();
        $second = strtolower((new ReflectionClass($this))->getShortName());
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

    /**
     * Récupère les données des champs
     * séléctionnés à partir d'une relation
     *
     * @param string $relation
     * @param array $properties
     * @return $this
     */
    public function with(string $relation, array $properties = [])
    {
        $instance = $this->{$relation};
        if(!is_array($instance)){
            if(!$properties){
                foreach ($instance as $key => $value) {
                    if(!in_array($key, [$instance->getPrimaryKey()])){
                        if(property_exists($this, $key)){
                            $this->{"{$relation}_{$key}"} = $value;
                        } else {
                            $this->{$key} = $value;
                        }
                    }
                }
            } else {
                foreach ($properties as $property) {
                    if(property_exists($this, $property)){
                        $this->{"{$relation}_{$property}"} = $instance->{$property};
                    } else {
                        $this->{$property} = $instance->{$property};
                    }
                }
            }
        } else {
            if($properties){
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

    /**
     * Modifie l'enregistrement de l'instance
     * actuel
     * @param array $data
     * @return $this
     * @throws ReflectionException
     * @example One to One ou self
     *
     */
    public function modify(array $data)
    {
        $keys = [];
        $values = [];
        $data = self::setUpdate($data);
        $data = self::filterColumns($data);
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

    /**
     * Modifie tous les engeristrements d'une
     * relation
     * @param string $relation
     * @param $id
     * @param array $data
     * @return $this
     * @throws ReflectionException
     * @example One to Many
     *
     */
    public function modifyMany(string $relation, $id, array $data)
    {
        $instances = self::getCurrentModel()->{$relation};
        $instances = !empty($instances) ? (is_array($instances) ? $instances : [$instances]) : false;
        if($instances !== false){
            foreach ($instances as $instance) {
                $keys = [];
                $values = [];
                $data = self::setUpdate($data);
                $data = self::filterColumns($data);
                foreach ($data as $k => $v) {
                    $keys[] = sprintf('%s = ?', $k);
                    $values[] = $v;
                }
                $values[] = $instance->{$instance->getPrimaryKey()};
                $statement =
                    'UPDATE ' .
                    $instance->getTable() .
                    ' SET ' .
                    implode(', ', $keys) .
                    ' WHERE ' .
                    $instance->getPrimaryKey() .
                    ' = ?';
                MysqlDatabase::prepare($statement, $values);
            }
            return $this;
        } else {
            throw new Exception(sprintf('la relation %s n\'existe pas', $relation));
        }
    }

    /**
     * Créer un nouvel enregistrement dans
     * la table en relation
     *
     * @param $table
     * @param array $data
     * @return bool|$this
     * @throws ReflectionException
     */
    public function attach($table, array $data)
    {
        $data[$this->primaryKey] = $this->{$this->primaryKey};
        $data = self::setDatetime($data);
        $data = self::filterColumns($data);
        return self::create($data, $table);
    }

    /**
     * Récupère le nom de chaque colonne
     * de la table
     *
     * @return array
     * @throws ReflectionException
     */
    private static function getColumns()
    {
        return array_map(function ($column) {
            return $column['Field'];
        }, MysqlDatabase::query("SHOW COLUMNS FROM " . self::getCurrentModel()->getTable()));
    }

    /**
     * Filtre les données selon les colonnes
     * de la table
     *
     * @param array $data
     * @return array
     * @throws ReflectionException
     */
    private static function filterColumns(array $data)
    {
        $columns = self::getColumns();
        $filteredData = [];
        foreach ($columns as $key => $column) {
            $filteredData[$column] = $data[$column] ?? null;
        }
        return array_filter($filteredData, function ($data) {
            return $data;
        });
    }

    /**
     * Met les champs datetimes de
     * l'instance actuel à la date aujourd'hui
     *
     * @param array $data
     * @return array
     * @throws ReflectionException
     */
    public static function setDatetime(array $data): array
    {
        if(!empty(self::getCurrentModel()->datetimes)){
            foreach (self::getCurrentModel()->datetimes as $key => $datetime) {
                if(in_array($key, ['creation', 'update'])){
                    $data[$datetime] = date('Y-m-d H:i:s');
                }
            }
        }
        return $data;
    }

    /**
     * Met le champs datetimes update de
     * l'instance actuel à la date aujourd'hui
     * @param array $data
     * @return array
     * @throws ReflectionException
     */
    public static function setUpdate(array $data): array
    {
        if(!empty(self::getCurrentModel()->datetimes) && isset(self::getCurrentModel()->datetimes['update'])){
            $data[self::getCurrentModel()->datetimes['update']] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    public function __get($key)
    {
        if(empty($this->$key)){
            if(method_exists($this, 'get' . ucfirst($key))){
                return $this->{'get' . ucfirst($key)}();
            }
            return $this->$key();
        }
    }
}
