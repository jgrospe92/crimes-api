<?php
namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

class CourtsModel extends BaseModel
{
    private $table_name = "courts";

    public function __construct()
    {
        parent::__construct();
    }

    public function handleGetAllCourts(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT * FROM $this->table_name WHERE 1 ";

        if(isset($filters["courts_id"])){
            $sql .= " AND courts_id LIKE :courts_id ";
            $query_values[":courts_id"] = $filters["courts_id"];
        }
        if(isset($filters["name"])){
            $sql .= " AND name LIKE :name ";
            $query_values["name"] = $filters["name"];
        }
        if(isset($filters["date"])){
            $sql .= " AND date LIKE :date ";
            $query_values["date"] = $filters["date"];
        }
        if(isset($filters["time"])){
            $sql .= " AND time LIKE :time ";
            $query_values["time"] = $filters["time"];
        }

        if(isset($filters["sort_by"])){
            $sort_by = $filters["sort_by"];
            if($sort_by == "courts_id"){
                $sql .= " ORDER BY courts_id";
            }elseif ($sort_by == "name") {
                $sql .= " ORDER BY name";
            }elseif ($sort_by == "date") {
                $sql .= " ORDER BY date";
            }elseif ($sort_by == "time") {
                $sql .= " ORDER BY time";
            }
        }

        return $this->paginate($sql, $query_values);
    }

    public function handleGetCourtById(String $court_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE court_id = :court_id ";
        return $this->run($sql,["court_id"=>$court_id])->fetch();
    }


}
