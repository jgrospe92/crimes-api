<?php
namespace Vanier\Api\models;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\BaseModel;
use Vanier\Api\models\ActorsModel;
use Exception;

class CasesModel extends BaseModel
{
    private $table_name = "cases";

    public function __construct(){
        parent::__construct();
    }

    public function getCaseById($table, $whereClause)
    {
        $case = $this->getById($table, $whereClause);
        $crime_sceneID = $case['crime_sceneID'];
        $investigator_id = $case['investigator_id'];
        $court_id = $case['court_id'];
        unset($case['crime_sceneID']);
        unset($case['investigator_id']);
        unset($case['court_id']);
        $crime_scene = $this->getById('crime_scenes', ['crime_sceneID'=>$crime_sceneID]);
        $investigators = $this->getById('investigators', ['investigator_id'=>$investigator_id]);
        $courts = $this->getById('courts', ['court_id'=>$court_id]);
        $case['crime scene'] = $crime_scene;
        $case['investigator'] = $investigators;
        $case['court'] = $courts;
        return $case;
    }

    public function getAll(array $filters)
    {
         // Queries the DB and return the list of all films
         $query_values = [];
         
         $sql = "SELECT cases.*, crime_scenes.*, investigators.*, courts.* FROM cases" .
            " inner join crime_scenes on crime_scenes.crime_sceneID = cases.crime_sceneID" .
            " inner join investigators on investigators.investigator_id = cases.investigator_id" .
            " inner join courts on courts.court_id = cases.court_id WHERE 1";

        if (isset($filters['description']))
        {
            $sql .= " AND description LIKE CONCAT('%', :description, '%')";
            $query_values['description'] = $filters['description'];
        }

       


         $cases = $this->paginate($sql, $query_values);
         return $cases;
    }

    
}