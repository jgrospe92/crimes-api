<?php
namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

class VerdictsModel extends BaseModel
{
    private $table_name = "verdicts";


    public function __construct()
    {
        parent::__construct();
    }

    public function handleGetAllVerdicts(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT * FROM verdicts WHERE 1 ";
        
        if(isset($filters["verdict_id"])){
            $sql .= " AND verdict_id LIKE :verdict_id ";
            $query_values[":verdict_id"] = $filters["verdict_id"];
        }
        if(isset($filters["name"])){
            $sql .= " AND name LIKE CONCAT(:name,'%') ";
            $query_values[":name"] = $filters["name"];
        }
        if(isset($filters["description"])){
            $sql .= " AND description like CONCAT(:description,'%') ";
            $query_values[":description"] = $filters["description"];
        }
        if(isset($filters["sentence"])){
            $sql .= " AND sentence like CONCAT(:sentence,'%') ";
            $query_values[":sentence"] = $filters["sentence"];
        }
        if(isset($filters["fine"])){
            $sql .= " AND fine LIKE CONCAT(:fine, '%') ";
            $query_values[":fine"] = $filters["fine"];
        }
        return $this->paginate($sql, $query_values);
    }

    public function handleGetVerdictById(String $verdict_id)
    {
        $sql2 = "SELECT * FROM verdicts WHERE verdict_id = :verdict_id ";
        return $this->run($sql2, ["verdict_id"=> $verdict_id])->fetch();
    }












}
