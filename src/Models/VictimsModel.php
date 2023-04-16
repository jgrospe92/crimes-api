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

     /**
     * Handle retrieving all victims from the database based on provided filters
     * 
     * @param array $filters - An array of filters to apply to the query
     * @return array - An array containing the paginated victims data along with their respective prosecutors
     */
    public function handleGetAllVictims(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT v.victim_id, v.first_name AS victim_first_name, v.last_name AS victim_last_name, v.age AS victim_age, 
               v.marital_status, p.prosecutor_id, p.first_name AS prosecutor_first_name, p.last_name AS prosecutor_last_name, 
               p.age AS prosecutor_age, p.specialization
                FROM victims v 
                LEFT JOIN prosecutors p ON v.prosecutor_id = p.prosecutor_id 
                WHERE 1";
        
        //Filtering 
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

        // Sorting the table values, a better way of filtering
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

         // Paginate the results
        $result = $this->paginate($sql, $query_values, 'victims');

        //mapping the data to a victims json objects that is the parent of victim and prosecutor json objects
        $victims = [];
        foreach($result["victims"] as $row){
            $victim = [
                "victim_id" => $row["victim_id"],
                "first_name" => $row["victim_first_name"],
                "last_name" => $row["victim_last_name"],
                "age" => $row["victim_age"],
                "marital_status" => $row["marital_status"]
            ];
        
            $prosecutor = [
                "prosecutor_id" => $row["prosecutor_id"],
                "first_name" => $row["prosecutor_first_name"],
                "last_name" => $row["prosecutor_last_name"],
                "age" => $row["prosecutor_age"],
                "specialization" => $row["specialization"]
            ];

            $victims[] = [
                "victim" => $victim,
                "prosecutor" => $prosecutor
            ];
        }
    
        $result["victims"] = $victims;
        return $result;
    
    }

    /**
     * Fetches a victim and their associated prosecutor by ID
     *
     * @param int $victim_id The ID of the victim to fetch
     *
     * @return array An array containing the victim and prosecutor data
     */
    public function handleGetVictimById($victim_id) {
        $victim_query = "SELECT * FROM $this->table_name WHERE victim_id = :victim_id";
        $victim_params = [":victim_id" => $victim_id];
        $victim = $this->run($victim_query, $victim_params)->fetchAll();
    
        $prosecutor_id = $victim[0]['prosecutor_id'];
    
        // Only fetch prosecutor data if it exists
        if ($prosecutor_id) {
            $sql = "SELECT * FROM prosecutors WHERE prosecutor_id = :prosecutor_id";
            $prosecutor_params = [":prosecutor_id" => $prosecutor_id];
            $prosecutor = $this->run($sql, $prosecutor_params)->fetchAll();
            $prosecutor_data = $prosecutor[0];
        } else {
            $prosecutor_data = null;
        }
    
        // removing prosecutor id from victim json object
        $victim_data = $victim[0];
        unset($victim_data['prosecutor_id']);
    
        return ['Victim' => $victim_data, 'Prosecutor' => $prosecutor_data];
    }

     /**
     * Inserts a Victim in the database
     * @param $victim
     */
    public function createVictim($victim) {
        return $this->insert('victims', $victim);
    }

    /**
     * Summary of updateVictim
     * @param mixed $victim
     * @return void
     */
    public function updateVictims($victims)
    {
        foreach ($victims as $victim) {
            $victim_id = $victim['victim_id'];
            unset($victim['victim_id']);
            $this->update('victims', $victim, ['victim_id' => $victim_id]);
        }
    }

    /**
     * Summary of deleteVictim
     * @param mixed $victim_id
     * @return void
     */
    public function deleteVictim($victim_id)
    {
        return $this->delete('victims', ['victim_id' => $victim_id]);
    }


}
