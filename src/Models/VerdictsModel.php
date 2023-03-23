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
        
        if(isset($filters["name"])){
            $sql .= " AND name LIKE CONCAT(:name,'%') ";
            $query_values[":name"] = $filters["name"]."%";
        }

        return $this->paginate($sql, $query_values);
    }












}
