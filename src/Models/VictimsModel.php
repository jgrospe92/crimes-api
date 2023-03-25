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
        $sql = "SELECT v.victim_id, v.first_name, v.last_name, v.age, v.marital_status, p.prosecutor_id, p.specialization 
                FROM victims v 
                LEFT JOIN prosecutors p ON v.prosecutor_id = p.prosecutor_id 
                WHERE 1";
        
        if(isset($filters["last_name"])){
            $sql .= " AND v.last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last_name"]."%";
        }

        if(isset($filters["marital_status"])){
            $sql .= " AND v.marital_status LIKE CONCAT(:marital_status,'%') ";
            $query_values[":marital_status"] = $filters["marital_status"]."%";
        }

        if(isset($filters["age"])){
            $sql .= " AND v.age LIKE CONCAT(:age,'%') ";
            $query_values[":age"] = $filters["age"]."%";
        }

        if(isset($filters["victim_id"])){
            $sql .= " AND v.victim_id LIKE CONCAT(:victim_id,'%') ";
            $query_values[":victim_id"] = $filters["victim_id"]."%";
        }

        if(isset($filters["prosecutor_id"])){
            $sql .= " AND p.prosecutor_id LIKE CONCAT(:prosecutor_id,'%') ";
            $query_values[":prosecutor_id"] = $filters["prosecutor_id"]."%";
        }

        if(isset($filters["sort_by"])){
            $sort_by = $filters["sort_by"];
            if($sort_by == "first_name"){
                $sql .= " ORDER BY v.first_name";
            } elseif($sort_by == "last_name"){
                $sql .= " ORDER BY v.last_name";
            } elseif($sort_by == "age"){
                $sql .= " ORDER BY v.age";
            } elseif($sort_by == "marital_status"){
                $sql .= " ORDER BY v.marital_status";
            }
        }

        $result = $this->paginate($sql, $query_values);

        $victims = [];
        foreach($result["data"] as $row){
            $victim = [
                "victim_id" => $row["victim_id"],
                "first_name" => $row["first_name"],
                "last_name" => $row["last_name"],
                "age" => $row["age"],
                "marital_status" => $row["marital_status"]
            ];

            $prosecutor = [
                "prosecutor_id" => $row["prosecutor_id"],
                "first_name" => $row["first_name"],
                "last_name" => $row["last_name"],
                "age" => $row["age"],
                "specialization" => $row["specialization"]
            ];

            $victims[] = [
                "victim" => $victim,
                "prosecutor" => $prosecutor
            ];
        }
    
        $result["data"] = $victims;
        return $result;
    
    }

    public function handleGetVictimById($victim_id) {
        $victim_query = "SELECT * FROM $this->table_name WHERE victim_id = :victim_id";
        $victim_params = [":victim_id" => $victim_id];
        $victim = $this->run($victim_query, $victim_params)->fetchAll();
    
        $prosecutor_id = $victim[0]['prosecutor_id'];
        $prosecutor_query = "SELECT * FROM prosecutors WHERE prosecutor_id = :prosecutor_id";
        $prosecutor_params = [":prosecutor_id" => $prosecutor_id];
        $prosecutor = $this->run($prosecutor_query, $prosecutor_params)->fetchAll();
    
        $victim_data = $victim[0];
        unset($victim_data['prosecutor_id']);
    
        $prosecutor_data = $prosecutor ? $prosecutor[0] : null;
    
        return ['Victim' => $victim_data, 'Prosecutor' => $prosecutor_data];
    }

}
