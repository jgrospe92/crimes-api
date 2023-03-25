<?php
namespace Vanier\Api\models;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\BaseModel;
use Vanier\Api\models\ActorsModel;
use Exception;

/**
 * Summary of CasesModel
 */
class CasesModel extends BaseModel
{
    private $table_name = "cases";

    public function __construct(){
        parent::__construct();
    }

    /**
     * Summary of getCaseById
     * @param mixed $table
     * @param mixed $whereClause
     * @return object
     */
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
        if (isset($filters['date_from']) && isset($filters['date_to']))
        {
            $sql .= " AND DATE(date_reported) BETWEEN :date_from AND :date_to ";
            $query_values['date_from'] = $filters['date_from'];
            $query_values['date_to'] = $filters['date_to'];
        }

        // If sort_by filters are added
        if (isset($filters['sort_by']))
        {
            $sql .= ' GROUP BY cases.case_id';

            if (!empty($filters['sort_by']))
            {
                $keyword = explode(".", $filters['sort_by']);
                $column = $keyword[0] ?? "";
                $order_by = $keyword[1] ?? "";

                $sql .= " ORDER BY " .   $column . " " .  $order_by;
            }
        }
        
        // if sort_by doesn't exists then append GROUP BY AT THE END 
        if (!isset($filters["sort_by"])){
            $sql .= " GROUP BY cases.case_id ";
        }

         $cases = $this->paginate($sql, $query_values);
         return $cases;
    }

    
}