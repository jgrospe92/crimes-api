<?php
namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

/**
 * Summary of VerdictsModel
 */
class VerdictsModel extends BaseModel
{
    private $table_name = "verdicts";


    /**
     * Summary of __construct
     */
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

        if(isset($filters["sort_by"])){
            $sort_by = $filters["sort_by"];
            if($sort_by == "verdict_id"){
                $sql .= " ORDER BY verdict_id";
            }elseif ($sort_by == "name") {
                $sql .= " ORDER BY name";
            }elseif ($sort_by == "description") {
                $sql .= " ORDER BY description";
            }elseif ($sort_by == "sentence") {
                $sql .= " ORDER BY sentence";
            }elseif ($sort_by == "fine") {
                $sql .= " ORDER BY fine";
            }
        }
        
        return $this->paginate($sql, $query_values, 'verdicts');
    }

    public function handleGetVerdictById(String $verdict_id)
    {
        $sql2 = "SELECT * FROM verdicts WHERE verdict_id = :verdict_id ";
        return $this->run($sql2, ["verdict_id"=> $verdict_id])->fetch();
    }

    public function handleCreateVerdicts(array $verdict)
    {
        return $this->insert($this->table_name, $verdict);
    }

    public function handleUpdateVerdictById(array $verdict, String $verdict_id)
    {
        return $this->update($this->table_name, $verdict, ["verdict_id" => $verdict_id]);
    }









}
