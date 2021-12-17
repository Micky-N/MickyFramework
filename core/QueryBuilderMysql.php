<?php

namespace Core;

use Core\MysqlDatabase;
use stdClass;

class QueryBuilderMysql
{
    private array $fields = [];
    private array $from = [];
    private array $conditions = [];
    private array $order = [];
    private array $limit = [];
    private array $joins = [];
    private array $group = [];
    private Model $instance;

    public function __construct($instance)
    {
        $this->instance = new $instance();
        return $this;
    }

    /**
     * @param string $statement
     * @return array
     */
    public function query(string $statement): array
    {
        return MysqlDatabase::query($statement);
    }

    /**
     * @param string $statement
     * @param array $attribute
     * @return array
     */
    public function prepare(string $statement, array $attribute): array
    {
        return MysqlDatabase::prepare($statement, $attribute);
    }

    public function select()
    {
        $this->fields = func_get_args();
        return $this;
    }

    /**
     * @param string $table
     * @param null $alias
     * @return $this
     */
    public function from(string $table, $alias = null): QueryBuilderMysql
    {
        if (is_null($alias)) {
            $this->from[] = "$table";
        } else {
            $this->from[] = "$table AS $alias";
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function where(): QueryBuilderMysql
    {
        if (count(func_get_args()) === 2)
            $this->conditions[] = sprintf('%s = "%s"', ...func_get_args());
        else if (count(func_get_args()) === 3)
            $this->conditions[] = sprintf('%s %s "%s"', ...func_get_args());
        return $this;
    }

    /**
     * @return $this
     */
    public function orderBy(): QueryBuilderMysql
    {
        foreach (func_get_args() as $arg) {
            if (count(explode(' ', $arg)) == 1) {
                $arg .= ' DESC';
            }
            $this->order[] = $arg;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function limit(): QueryBuilderMysql
    {
        if (count(func_get_args()) === 2)
            $this->limit[] = join(' OFFSET ', func_get_args());
        else if (count(func_get_args()) === 1)
            $this->limit[] = join(' ', func_get_args());
        return $this;
    }

    /**
     * @return $this
     */
    public function groupBy(): QueryBuilderMysql
    {
        $this->group[] = join(', ', func_get_args());
        return $this;
    }

    /**
     * @param string $join_table
     * @param string $on
     * @param string $operation
     * @param string $to
     * @param string $aliasFirstTable
     * @return $this
     * @throws \Exception
     */
    public function join(string $join_table, string $on, string $operation, string $to, string $aliasFirstTable = ''): QueryBuilderMysql
    {
        if (!strpos($on, '.')) {
            $on = empty($alias) ? $this->instance->getTable() . ".$on" : "$aliasFirstTable.$on";
        }
        if (!strpos($to, '.')) {
            $to = "$join_table.$to";
        }
        $this->joins[] = [$join_table, $on, $operation, $to];

        return $this;
    }

    /**
     * Crée un tableau avec les champs
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public function map(string $key = '', $value = null): array
    {
        $query = MysqlDatabase::query($this->stringify());
        $value = str_replace(' ', '', $value);
        $valuemap = !empty($value) ? $this->mapping($value, $query) : $query;
        if ($key) {
            $keymap = $key ? $this->mapping($key, $query) : range(1, count($query));
            $valuemap = array_combine($keymap, $valuemap);
        }
        return $valuemap;
    }

    /**
     * Récupere les données du champ $key 
     * sous forme de tableau 
     * @param mixed $key
     * @return array
     */
    private function mapping($key, array $query = []): array
    {
        $keymap = array_map(function ($km) use ($key) {
            if(is_string($key)){
                return $km[$key];
            }
            $map = [];
            foreach ($key as $k => $v) {
                $map[$v] = $km[$v];
            }
            return $map;
        }, $query);
        return $keymap;
    }

    private function hasFields()
    {
        if (!empty($this->fields)) {
            return 'SELECT ' . implode(', ', $this->fields);
        } else {
            return 'SELECT *';
        }
    }

    private function hasLimit()
    {
        if (!empty($this->limit))
            return ' LIMIT ' . implode(' ', $this->limit);
        else
            return '';
    }

    private function hasConditions()
    {
        if (!empty($this->conditions))
            return ' WHERE ' . implode(' AND ', $this->conditions);
        else
            return '';
    }

    private function hasOrder()
    {
        if (!empty($this->order))
            return ' ORDER BY ' . implode(', ', $this->order);
        else
            return '';
    }

    private function hasFrom()
    {
        if (!empty($this->from)) {
            return ' FROM ' . implode(', ', $this->from);
        } else {
            return ' FROM ' . $this->instance->getTable();
        }
    }

    private function hasJoin()
    {
        $syntax = '';
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $syntax .= sprintf(" LEFT JOIN %s ON %s %s %s", ...$join);
            }
        }
        return $syntax;
    }

    private function hasGroup()
    {
        if (!empty($this->group))
            return ' GROUP BY ' . implode(', ', $this->group);
        else
            return '';
    }

    /**
     * Récupere les enregistrement
     * @return array
     */
    public function get(): array
    {
        return MysqlDatabase::query($this->stringify(), get_class($this->instance));
    }

    /**
     * Récupere les enregistrement
     * @return array
     */
    public function toArray(): array
    {
        return MysqlDatabase::query($this->stringify());
    }

    /**
     * Recupere le premier enregistrement de la requête
     * @return Model
     */
    public function first(): Model
    {
        $this->limit(1);
        return MysqlDatabase::query($this->stringify(), get_class($this->instance), true);
    }

    /**
     * Recupere le dernier enregistrement de la requête
     * @return Model
     */
    public function last(): Model
    {
        $this->limit(1);
        $this->orderBy($this->instance->getPrimaryKey());
        return MysqlDatabase::query($this->stringify(), get_class($this->instance), true);
    }

    /**
     * @return string
     */
    public function stringify(): string
    {
        return $this->hasFields()
            . $this->hasFrom()
            . $this->hasJoin()
            . $this->hasConditions()
            . $this->hasGroup()
            . $this->hasOrder()
            . $this->hasLimit();
    }

    /**
     * Récuperer le model en cours
     * @return Model
     */
    public function getInstance(): Model
    {
        return $this->instance;
    }

    /**
     * @param array $data
     * @param int|null $dateInt
     * @return array
     */
    public function setDatetime(array $data, int $dateInt = null): array
    {
        $date = is_null($dateInt) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $dateInt);
        $data['created_at'] = $date;
        $data['updated_at'] = $date;
        return $data;
    }
}
