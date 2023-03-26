<?php

namespace Vanier\Api\Models;

/**
 * Summary of ProsecutorsModel
 */
class ProsecutorsModel extends BaseModel
{
    private $sql = "SELECT * FROM prosecutors WHERE 1 ";

    public function __construct() 
    {
        parent::__construct();
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

        if(isset($filters["id"]))
        {
            $this->sql .= " AND prosecutor_id = :prosecutor_id ";
            $query_values[":prosecutor_id"] = $filters["id"];
        }
        
        if(isset($filters["first-name"]))
        {
            $this->sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["first-name"]."%";
        }

        if(isset($filters["last-name"]))
        {
            $this->sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last-name"]."%";
        }

        if(isset($filters["age"]))
        {
            $this->sql .= " AND age = :age ";
            $query_values[":age"] = $filters["age"];
        }

        if(isset($filters["specialization"]))
        {
            $this->sql .= " AND specialization LIKE CONCAT(:specialization, '%') ";
            $query_values[":specialization"] = $filters["specialization"] . "%";
        }

        if(isset($filters["sort"])){
            $sort = $filters["sort"];
            if($sort == "first-name")           { $this->sql .= " ORDER BY first_name"; } 
            elseif($sort == "last-name")        { $this->sql .= " ORDER BY last_name"; } 
            elseif($sort == "age")              { $this->sql .= " ORDER BY age"; } 
            elseif($sort == "specialization")   { $this->sql .= " ORDER BY specialization"; }
        }

        return $this->paginate($this->sql, $query_values);
    }
}
