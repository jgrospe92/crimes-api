<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

/**
 * Summary of JudgesModel
 */
class JudgesModel extends BaseModel
{
    private $table_name = "judges";
    
    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Summary of handleGetAllJudges
     * @param array $filters
     * @return array
     */
    public function handleGetAllJudges(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT * FROM $this->table_name WHERE 1 ";
        
        //filters
        if(isset($filters["first_name"])){
            $sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["first_name"]."%";
        }

        if(isset($filters["last_name"])){
            $sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last_name"]."%";
        }

        if(isset($filters["age"])){
            $sql .= " AND age LIKE CONCAT(:age,'%') ";
            $query_values[":age"] = $filters["age"]."%";
        }

        if(isset($filters["judge_id"])){
            $sql .= " AND judge_id LIKE CONCAT(:judge_id,'%') ";
            $query_values[":judge_id"] = $filters["judge_id"]."%";
        }

        // sorting
        if(isset($filters["sort_by"])){
            $sort_by = $filters["sort_by"];
            if($sort_by == "first_name"){
                $sql .= " ORDER BY first_name";
            } elseif($sort_by == "last_name"){
                $sql .= " ORDER BY last_name";
            } elseif($sort_by == "age"){
                $sql .= " ORDER BY age";
            } elseif($sort_by == "judge_id"){
                $sql .= " ORDER BY judge_id";
            }
        }

        return $this->paginate($sql, $query_values, 'judges');
    }

    /**
     * Summary of handleGetJudgeById
     * @param mixed $judge_id
     * @return mixed
     */
    public function handleGetJudgeById($judge_id) {
        $sql = "SELECT * FROM $this->table_name WHERE judge_id = :judge_id";
        $query_values = [":judge_id" => $judge_id];

        return $this->run($sql, $query_values)->fetchAll();
    }

    /**
     * Inserts a Judge in the database
     * @param array $judge_data
     */
    public function createJudge(array $judge_data) {
        return $this->insert($this->table_name, $judge_data);
    }

     /**
     * Updates a Judges
     * @param int $id 
     * @param mixed $data 
     */
    public function updateJudges($id, $data) {
        $this->update($this->table_name, $data, ['judge_id' => $id]);
    }

    /**
     * Delete a Judge
     * @param int $id
     */
    public function deleteJudge($id)
    {
        // Delete the customer from the database
        $result = $this->delete($this->table_name, ['judge_id' => $id]);

        // Return the number of rows affected
        return $result->rowCount();
    }
}
