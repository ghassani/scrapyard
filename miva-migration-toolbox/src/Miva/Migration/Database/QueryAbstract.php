<?php

namespace Miva\Migration\Database;


abstract class QueryAbstract
{
	
    /**
     * insertAbstract
     * 
     * @param mixed $table       Description.
     * @param mixed \array Description.
     * @param mixed \array   Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertAbstract($table, array $fields, array $data)
    {
        $query = $this->getConnection()->prepare('INSERT INTO '.$table.' ('.implode(', ', $fields).') VALUES('.implode(', ', array_map(function($value){ return ':'.$value; }, $fields)).')');  
        return $query->execute($data);
    }

    /**
     * updateAbstract
     * 
     * @param mixed $table       Description.
     * @param mixed $recordIdKey Description.
     * @param mixed \array   Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateAbstract($table, $recordIdKey, array $data)
    {
        $set = array();
        $recordId = null;

        foreach($data as $field => $value){
            if($field == $recordIdKey){
                $recordId = $value;
            } else if(is_numeric($field) || $field === 0){
                unset($data[$field]);
            } else {
                $set[] .= sprintf('`%s` = :%s', $field, $field);
            }
        }

        unset($data[0]);

        if(empty($recordId)){
            throw new \Exception('Record Primary Key Required For Update');
        }

        $query = sprintf('UPDATE %s SET %s WHERE `%s` = :%s',
            $table, implode(', ', $set), $recordIdKey, $recordIdKey
        );

        $query = $this->getConnection()->prepare($query);  
        return $query->execute($data);
    }

    /**
     * countAbstract
     * 
     * @param mixed $table    Description.
     * @param mixed $field    Description.
     * @param mixed $where    Description.
     * @param mixed $distinct Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function countAbstract($table, $field, $where = null, $distinct = true)
    {
        $query = sprintf('SELECT COUNT(%s) AS count FROM %s %s',
            $distinct == true ? 'DISTINCT '.$field : $field,
            $this->tablePrefix.$table,
            !is_null($where) ? 'WHERE '.$where : null
        );


        $query = $this->getConnection()->prepare($query);  
        $r = $query->execute(array());
        $result = $query->fetch();
        return isset($result['count']) ? $result['count'] : 0;
    }


    /**
     * nextIdAbstract
     * 
     * @param mixed $table Description.
     * @param mixed $field Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function nextIdAbstract($table, $field, $includePrefix = true)
    {
        $query = $this->getConnection()->prepare(sprintf('SELECT %s FROM %s ORDER BY %s DESC LIMIT 1',
            $field,
            ($includePrefix === true ? $this->tablePrefix : null).$table,
            $field
        ));  
        $r = $query->execute(array());
        $result = $query->fetch();
        return !isset($result[$field]) || !$result[$field] ? 1 : $result[$field]+1;
    }

    public function currentIdAbstract($table, $field, $includePrefix = true)
    {
        $query = $this->getConnection()->prepare(sprintf('SELECT %s FROM %s ORDER BY %s DESC LIMIT 1',
            $field,
            ($includePrefix === true ? $this->tablePrefix : null).$table,
            $field
        ));  
        $r = $query->execute(array());
        $result = $query->fetch();
        return !isset($result[$field]) || !$result[$field] ? 0 : $result[$field];
    }
}