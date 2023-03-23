<?php

namespace Vanier\Api\Models;

class DefendantsModel extends BaseModel
{
    private $sql = "SELECT first_name, last_name, age, specialization FROM defendants WHERE 1 ";

    public function __construct() 
    {
        parent::__construct();
    }

    public function getDefendantById($defendant_id) 
    {
        $this->sql .= "AND defendant_id = :defendant_id ";
        return $this->run($this->sql, [':defendant_id' => $defendant_id])->fetchAll();
    }

    public function getAllDefendants(array $filters = []) 
    {
        $query_values = [];

        if(isset($filters["id"]))
        {
            $this->sql .= " AND defendant_id = :defendant_id ";
            $query_values[":defendant_id"] = $filters["id"];
        }
        
        if(isset($filters["firstName"]))
        {
            $this->sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["firstName"]."%";
        }

        if(isset($filters["lastName"]))
        {
            $this->sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["lastName"]."%";
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

        return $this->paginate($this->sql, $query_values);
    }
}
