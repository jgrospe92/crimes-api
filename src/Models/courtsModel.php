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
            }
            elseif ($sort_by == "name") {
                $sql .= " ORDER BY name";
            }elseif ($sort_by == "name.asc") {
                $sql .= " ORDER BY name asc";
            }elseif ($sort_by == "name.desc") {
                $sql .= " ORDER BY name desc";
            }
            elseif ($sort_by == "date") {
                $sql .= " ORDER BY date";
            }elseif ($sort_by == "date.asc") {
                $sql .= " ORDER BY date asc";
            }elseif ($sort_by == "date.desc") {
                $sql .= " ORDER BY date desc";
            }
            elseif ($sort_by == "time") {
                $sql .= " ORDER BY time";
            }elseif ($sort_by == "time.asc") {
                $sql .= " ORDER BY time asc";
            }elseif ($sort_by == "time.desc") {
                $sql .= " ORDER BY time desc";
            }
        }

        $courts= $this->paginate($sql,$query_values, 'courts');

        foreach ($courts['courts'] as $key => $value) {
            // ? You can add filters too
            $verdict_id = $value['verdict_id'];
            $verdicts = $this->getById('verdicts', ['verdict_id' => $verdict_id]);
     
            $judge_id = $value['judge_id'];
            $judges = $this->getById('judges', ['judge_id' => $judge_id]);

            $address_id = $value['address_id'];
            $court_addresses = $this->getById('court_addresses', ['address_id' => $address_id]);

            unset($courts['courts'][$key]['verdict_id']);
            unset($courts['courts'][$key]['address_id']);
            unset($courts['courts'][$key]['judge_id']);

            $courts['courts'][$key]['verdicts'] = $verdicts ?? '';
            $courts['courts'][$key]['judges'] = $judges ?? '';
            $courts['courts'][$key]['court_addresses'] = $court_addresses ?? '';
        }

        return $courts;
    }

    public function handleGetCourtById(String $court_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE court_id = :court_id ";
        $courts = $this->run($sql,["court_id"=>$court_id])->fetch();

        $verdict_id = $courts['verdict_id'];
        if ($verdict_id) {
            $sql = "SELECT * from verdicts WHERE verdict_id = :verdict_id";
            $verdicts_params = [":verdict_id" => $verdict_id];
            $verdicts = $this->run($sql, $verdicts_params)->fetch();
          
        } else {
            $verdicts = null;
        }
        unset($courts['verdict_id']);

        $judge_id = $courts['judge_id'];
        if ($judge_id) {
            $sql = "SELECT * FROM judges WHERE judge_id = :judge_id";
            $judges_params = [":judge_id" => $judge_id];
            $judges = $this->run($sql, $judges_params)->fetch();
        } else {
            $judges= null;
        }
        unset($courts['judge_id']);
        $address_id = $courts['address_id'];
        if ($address_id) {
            $sql = "SELECT * FROM court_addresses WHERE address_id = :address_id";
            $address_params = [":address_id" => $address_id];
            $addresses = $this->run($sql, $address_params)->fetch();
        } else {
            $addresses = null;
        }
        unset($courts['address_id']);
        $courts['verdicts'] = $verdicts;
        $courts['judges'] = $judges;
        $courts['addresses'] = $addresses;
        return $courts;
    }

    public function handleCreateCourts(array $courts)
    {
        return $this->insert($this->table_name, $courts);
    }

    public function handleUpdateCourtsById(array $court, String $court_id)
    {
        return $this->update('courts',$court, ["court_id" => $court_id]);
    }

    public function handleDeleteCourts($court_id)
    {
        return $this->delete('courts', ["court_id" => $court_id]);
    }

    public function checkIfResourceExists($table, $whereClause): bool
    {
        if (!$this->getById($table, $whereClause)) {
            return false;
        }
        return true;
    }
}
