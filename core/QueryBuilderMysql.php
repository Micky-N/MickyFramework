<?php

namespace Core;

use Core\MysqlDatabase;

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

    public function query(string $statement)
    {
        return MysqlDatabase::query($statement);
    }

    public function prepare(string $statement, array $attribute)
    {
        return MysqlDatabase::prepare($statement, $attribute);
    }

    public function select()
    {
        $this->fields = func_get_args();
        return $this;
    }

    public function from($table, $alias = null)
    {
        if (is_null($alias)) {
            $this->from[] = "$table";
        } else {
            $this->from[] = "$table AS $alias";
        }
        return $this;
    }

    public function where()
    {
        if (count(func_get_args()) === 2)
            $this->conditions[] = sprintf('%s = "%s"', ...func_get_args());
        else if (count(func_get_args()) === 3)
            $this->conditions[] = sprintf('%s %s "%s"', ...func_get_args());
        return $this;
    }

    public function orderBy()
    {
        foreach (func_get_args() as $arg) {
            if (count(explode(' ', $arg)) == 1) {
                $arg .= ' DESC';
            }
            $this->order[] = $arg;
        }
        return $this;
    }

    public function limit()
    {
        if (count(func_get_args()) === 2)
            $this->limit[] = join(' OFFSET ', func_get_args());
        else if (count(func_get_args()) === 1)
            $this->limit[] = join(' ', func_get_args());
        return $this;
    }

    public function groupBy()
    {
        $this->group[] = join(', ', func_get_args());
        return $this;
    }

    public function join(string $join_table, string $on, string $operation, string $to, string $aliasFirstTable = '')
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
     * @param string $value
     * @param string $key
     * @return Array
     */
    public function map(string $key = '', string $value = '*'): Array
    {
        $valuemap = $value !== "*" ? $this->mapping($value) : $this->get();
        if ($key) {
            $keymap = $key ? $this->mapping($key) : range(1, count($this->get()));
            $valuemap = array_combine($keymap, $valuemap);
        }
        return $valuemap;
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

    public function hasJoin()
    {
        $syntax = '';
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $syntax .= sprintf(" LEFT JOIN %s ON %s %s %s", ...$join);
            }
        }
        return $syntax;
    }

    public function hasGroup()
    {
        if (!empty($this->group))
            return ' GROUP BY ' . implode(', ', $this->group);
        else
            return '';
    }

    /**
     * Récupere les enregistrement
     * @return Array
     */
    public function get(): Array
    {
        return MysqlDatabase::query($this->stringify(), get_class($this->instance));
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

    public function stringify()
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
     * Récupere les données du champ $key 
     * sous forme de tableau 
     * @param string $key
     * @return Array
     */
    private function mapping(string $key): Array
    {
        $keymap = array_map(function ($k) use ($key) {
            return $k->$key;
        }, $this->get());
        return $keymap;
    }

    /**
     * Récuperer le model en cours
     * @return Model
     */
    public function getInstance(): Model
    {
        return $this->instance;
    }

    public function setDatetime(array $data, int $dateInt = null)
    {
        $date = is_null($dateInt) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $dateInt);
        $data['created_at'] = $date;
        $data['updated_at'] = $date;
        return $data;
    }
}
