<?php

namespace Vanier\Api\Models;

/**
 * Summary of DefendantsModel
 */
class DefendantsModel extends BaseModel
{
    private $table_name;
    private $sql;

    /**
     * Summary of __construct
     */
    public function __construct() 
    {
        parent::__construct();
        $this->table_name = 'defendants';
        $this->sql = "SELECT * FROM $this->table_name WHERE 1 ";
    }

    /**
     * Summary of getDefendantById
     * @param mixed $defendant_id
     * @return mixed
     */
    public function getDefendantById($defendant_id) 
    {
        $this->sql .= "AND defendant_id = :defendant_id ";
        return $this->run($this->sql, [':defendant_id' => $defendant_id])->fetchAll();
    }

    /**
     * Summary of getAllDefendants
     * @param array $filters
     * @return array
     * Supports filters for ID, first_name, last_name, age, and specialization
     */
    public function getAllDefendants(array $filters = []) 
    {
        $query_values = [];

        if (isset($filters["id"]))
        {
            $this->sql .= " AND defendant_id = :defendant_id ";
            $query_values[":defendant_id"] = $filters["id"];
        }
        
        if (isset($filters["first-name"]))
        {
            $this->sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["first-name"]."%";
        }

        if (isset($filters["last-name"]))
        {
            $this->sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last-name"]."%";
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

        if (isset($filters["sort"])){
            $sort = $filters["sort"];
            if ($sort == "first-name")           { $this->sql .= " ORDER BY first_name"; } 
            elseif ($sort == "last-name")        { $this->sql .= " ORDER BY last_name"; } 
            elseif ($sort == "age")              { $this->sql .= " ORDER BY age"; } 
            elseif ($sort == "specialization")   { $this->sql .= " ORDER BY specialization"; }
        }

        return $this->paginate($this->sql, $query_values, 'defendants');
    }

    /**
     * Summary of postDefendant
     * @param array $data
     * @return bool|string
     */
    public function postDefendant(array $data)
    {
        return $this->insert($this->table_name, $data);
    }

    /**
     * Summary of putDefendant
     * @param mixed $defendant
     * @return void
     */
    public function putDefendant($defendant)
    {
        $defendant_id = $defendant['defendant_id'];
        unset($defendant['defendant_id']);
        $this->update($this->table_name, $defendant, ['defendant_id' => $defendant_id]);
    }

    public function deleteDefendant()
    {
        
    }
}
