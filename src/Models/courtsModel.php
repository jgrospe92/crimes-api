<?php
namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

/**
 * Summary of CourtsModel
 */
class CourtsModel extends BaseModel
{
    private $table_name = "courts";

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Summary of handleGetAllCourts
     * @param array $filters
     * @return array
     */
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
     
            $judge_id = $value['judge_id'];
            $judges = $this->getById('judges', ['judge_id' => $judge_id]);

            $address_id = $value['address_id'];
            $court_addresses = $this->getById('court_addresses', ['address_id' => $value['address_id']]);

            unset($courts['data'][$key]['verdict_id']);
            unset($courts['data'][$key]['address_id']);
            unset($courts['data'][$key]['judge_id']);

            $courts['data'][$key]['verdicts'] = $verdicts ?? '';
            $courts['data'][$key]['judges'] = $judges ?? '';
            $courts['data'][$key]['court_addresses'] = $court_addresses ?? '';
        }

        return $courts;
    }

    public function handleGetCourtById(String $court_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE court_id = :court_id ";
        $courts = $this->run($sql,["court_id"=>$court_id])->fetchAll();

        $verdict_id = $courts[0]['verdict_id'];
        if ($verdict_id) {
            $sql = "SELECT * FROM $this->table_name WHERE verdict_id = :verdict_id";
            $verdicts_params = [":verdict_id" => $verdict_id];
            $verdicts = $this->run($sql, $verdicts_params)->fetchAll();
            $verdict_data = $verdicts[0];
        } else {
            $verdict_data = null;
        }
        $courts_data = $courts[0];
        unset($courts_data['verdict_id']);

        $judge_id = $courts[0]['judge_id'];
        if ($judge_id) {
            $sql = "SELECT * FROM $this->table_name WHERE judge_id = :judge_id";
            $judges_params = [":judge_id" => $judge_id];
            $judges = $this->run($sql, $judges_params)->fetchAll();
            $judge_data = $judges[0];
        } else {
            $judge_data = null;
        }
        $courts_data = $courts[0];
        unset($courts_data['judge_id']);

        $address_id = $courts[0]['address_id'];
        if ($address_id) {
            $sql = "SELECT * FROM $this->table_name WHERE address_id = :address_id";
            $address_params = [":address_id" => $address_id];
            $addresses = $this->run($sql, $address_params)->fetchAll();
            $address_data = $addresses[0];
        } else {
            $address_data = null;
        }
        $courts_data = $courts[0];
        unset($courts_data['address_id']);

        return ['courts' => $courts_data, 'verdicts' => $verdict_data, 'judges' => $judge_data, 'addresses' => $address_data];
    }


}
