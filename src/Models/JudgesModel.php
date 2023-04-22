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

    public function judgeExists($judge_id) {
        $sql = "SELECT COUNT(*) FROM $this->table_name WHERE judge_id = :judge_id";
        $query_values = [":judge_id" => $judge_id];
    
        $result = $this->run($sql, $query_values)->fetchColumn();
    
        return ($result > 0);
    }

    /**
     * Inserts a Judge in the database
     * @param $judge
     */
    public function createJudge($judge) {
        return $this->insert('judges', $judge);
    }

     /**
     * Summary of updateJudge
     * @param mixed $judge
     * @return void
     */
    public function updateJudges($judges)
    {
        foreach ($judges as $judge) {
            if (!is_array($judge) || !array_key_exists('judge_id', $judge)) {
                continue;
            }
            $judge_id = $judge['judge_id'];
            unset($judge['judge_id']);
            $this->update('judges', $judge, ['judge_id' => $judge_id]);
        }
    }

     /**
     * Summary of deleteJudges
     * @param mixed $judgeIds
     * @return void
     */
    public function deleteJudge($judgeId)
    {
        $where = ['judge_id' => $judgeId];
        $deletedCount = $this->delete($this->table_name, $where);
        return $deletedCount;
    }
    
    public function checkIfResourceExists($table, $whereClause): bool
    {
        if (!$this->getById($table, $whereClause)) {
            return false;
        }
        return true;
    }
}
