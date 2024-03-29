<?php

namespace Vanier\Api\Models;

/**
 * Summary of ProsecutorsModel
 */
class ProsecutorsModel extends BaseModel
{
    private $table_name;
    private $sql;

    /**
     * Summary of __construct
     */
    public function __construct() 
    {
        parent::__construct();
        $this->table_name = 'prosecutors';
        $this->sql = "SELECT * FROM $this->table_name WHERE 1 ";
    }

    /**
     * Summary of getProsecutorById
     * @param mixed $prosecutor_id
     * @return mixed
     */
    public function getProsecutorById($prosecutor_id) 
    {
        $this->sql .= "AND prosecutor_id = :prosecutor_id ";
        return $this->run($this->sql, [':prosecutor_id' => $prosecutor_id])->fetchAll();
    }

    /**
     * Summary of getAllProsecutors
     * @param array $filters
     * @return array 
     * Supported filters for ID, first_name, last_name, age, and specialization
     */
    public function getAllProsecutors(array $filters = []) 
    {
        $query_values = [];
        
        if (isset($filters["prosecutor_id"]))
        {
            $this->sql .= " AND prosecutor_id = :prosecutor_id ";
            $query_values[":prosecutor_id"] = $filters["prosecutor_id"];
        }
        
        if (isset($filters["first_name"]))
        {
            $this->sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["first_name"]."%";
        }

        if (isset($filters["last_name"]))
        {
            $this->sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last_name"]."%";
        }

        if (isset($filters["age"]))
        {
            $this->sql .= " AND age = :age ";
            $query_values[":age"] = $filters["age"];
        }

        if (isset($filters["specialization"]))
        {
            $this->sql .= " AND specialization LIKE CONCAT(:specialization, '%') ";
            $query_values[":specialization"] = $filters["specialization"] . "%";
        }

        if (isset($filters["sort"]))
        {
            $sort = $filters["sort"];
            if($sort == "first_name")           { $this->sql .= " ORDER BY first_name"; } 
            elseif($sort == "last_name")        { $this->sql .= " ORDER BY last_name"; } 
            elseif($sort == "age")              { $this->sql .= " ORDER BY age"; } 
            elseif($sort == "specialization")   { $this->sql .= " ORDER BY specialization"; }
        }

        return $this->paginate($this->sql, $query_values, 'prosecutors');
    }

    /**
     * Summary of postProsecutor
     * @param array $data
     * @return bool|string
     */
    public function postProsecutor(array $data)
    {
        return $this->insert($this->table_name, $data);
    }

    /**
     * Summary of putProsecutor
     * @param mixed $prosecutor
     * @return mixed
     */
    public function putProsecutor($prosecutor)
    {
        $prosecutor_id = $prosecutor['prosecutor_id'];
        unset($prosecutor['prosecutor_id']);
        return $this->update($this->table_name, $prosecutor, ['prosecutor_id' => $prosecutor_id]);
    }

    public function deleteProsecutor($prosecutor_id)
    {
        $where = ['prosecutor_id' => $prosecutor_id];
        return $this->delete($this->table_name, $where);
    }
}
