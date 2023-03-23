<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

class VictimsModel extends BaseModel
{
    private $table_name = "victims";
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handleGetAllVictims(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT * FROM $this->table_name WHERE 1 ";
        
        if(isset($filters["last_name"])){
            $sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last_name"]."%";
        }

        if(isset($filters["marital_status"])){
            $sql .= " AND marital_status LIKE CONCAT(:marital_status,'%') ";
            $query_values[":marital_status"] = $filters["marital_status"]."%";
        }

        if(isset($filters["age"])){
            $sql .= " AND age LIKE CONCAT(:age,'%') ";
            $query_values[":age"] = $filters["age"]."%";
        }

        if(isset($filters["victim_id"])){
            $sql .= " AND victim_id LIKE CONCAT(:victim_id,'%') ";
            $query_values[":victim_id"] = $filters["victim_id"]."%";
        }

        return $this->paginate($sql, $query_values);
    }

    public function handleGetVictimById($victim_id) {
        $sql = " SELECT * FROM $this->table_name WHERE victim_id = :victim_id";
        $querry_value[":victim_id"] = $victim_id;
        return $this->run($sql, $querry_value)->fetchAll();
    }

}
