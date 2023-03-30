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

        $courts= $this->paginate($sql,$query_values);

        foreach ($courts['data'] as $key => $value) {
            // ? You can add filters too
            $verdict_id = $value['verdict_id'];
 
            $verdicts = $this->getById('verdicts', ['verdict_id' => $verdict_id]);
     
            $court_addresses = $this->getById('court_addresses', ['address_id' => $value['address_id']]);

            unset($courts['data'][$key]['verdict_id']);
            // unset($courts['data'][$key]['investigator_id']);
            // unset($courts['data'][$key]['court_id']);

            

            //$verdicts = $this->verdicts($courts['data'][$key]['case_id']);
            //$court_addresses = $this->court_addresses($courts['data'][$key]['case_id']);

        
            $courts['data'][$key]['verdicts'] = $verdicts;
            // $courts['data'][$key]['verdicts'] = $verdicts ?? '';
            // $courts['data'][$key]['court_addresses'] = $court_addresses ?? '';
        }

        return $courts;
    }

    public function handleGetCourtById(String $court_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE court_id = :court_id ";
        return $this->run($sql,["court_id"=>$court_id])->fetch();
    }


}
