<?php

namespace Vanier\Api\models;

use Exception;
use Vanier\Api\Models\BaseModel;


/**
 * Summary of CasesModel
 */
class CasesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Summary of getCaseById
     * @param mixed $table
     * @param mixed $whereClause
     * @return object
     * ? No supported filtering and sort by
     */
    public function getCaseById($table, $whereClause)
    {
        $case = $this->getById($table, $whereClause);

        if (!$case)
        {
            return null;
        }
        $crime_sceneID = $case['crime_sceneID'];
        $investigator_id = $case['investigator_id'];
        $court_id = $case['court_id'];
        unset($case['crime_sceneID']);
        unset($case['investigator_id']);
        unset($case['court_id']);
        $crime_scene = $this->getById('crime_scenes', ['crime_sceneID' => $crime_sceneID]);
        $investigators = $this->getById('investigators', ['investigator_id' => $investigator_id]);
        $courts = $this->getById('courts', ['court_id' => $court_id]);
        $offenses = $this->offenses($whereClause['case_id']);
        $victims = $this->victims($whereClause['case_id']);
        $offenders = $this->offenders($whereClause['case_id']);
        $case['crime scene'] = $crime_scene;
        $case['investigator'] = $investigators;
        $case['court'] = $courts;
        $case['offenses'] = $offenses;
        $case['victims'] = $victims;
        $case['offenders'] = $offenders;

        return $case;
    }

    /**
     * Summary of offensesByCase
     * @param mixed $table
     * @param mixed $whereClause
     * @return object|null
     * ? No supported filtering and sort by
     */
    public function offensesByCase($table, $whereClause)
    {
        $case = $this->getById($table, $whereClause);

        if (!$case)
        {
            return null;
        }
        $crime_sceneID = $case['crime_sceneID'];
        $investigator_id = $case['investigator_id'];
        $court_id = $case['court_id'];
        unset($case['crime_sceneID']);
        unset($case['investigator_id']);
        unset($case['court_id']);
        $crime_scene = $this->getById('crime_scenes', ['crime_sceneID' => $crime_sceneID]);
        $investigators = $this->getById('investigators', ['investigator_id' => $investigator_id]);
        $courts = $this->getById('courts', ['court_id' => $court_id]);
        $offenses = $this->offenses($whereClause['case_id']);

        $case['crime scene'] = $crime_scene;
        $case['investigator'] = $investigators;
        $case['court'] = $courts;
        $case['offenses'] = $offenses;
        return $case;
    }

    /**
     * Summary of offendersByCase
     * @param mixed $table
     * @param mixed $whereClause
     * @return object|null
     */
    public function offendersByCase($table, $whereClause)
    {
        $case = $this->getById($table, $whereClause);

        if (!$case)
        {
            return null;
        }
        $crime_sceneID = $case['crime_sceneID'];
        $investigator_id = $case['investigator_id'];
        $court_id = $case['court_id'];
        unset($case['crime_sceneID']);
        unset($case['investigator_id']);
        unset($case['court_id']);
        $crime_scene = $this->getById('crime_scenes', ['crime_sceneID' => $crime_sceneID]);
        $investigators = $this->getById('investigators', ['investigator_id' => $investigator_id]);
        $courts = $this->getById('courts', ['court_id' => $court_id]);
        $offenders = $this->offenders($whereClause['case_id']);

        $case['crime scene'] = $crime_scene;
        $case['investigator'] = $investigators;
        $case['court'] = $courts;
        $case['offenders'] = $offenders;
        return $case;
    }

    /**
     * Summary of victimsByCase
     * @param mixed $table
     * @param mixed $whereClause
     * @return object|null
     */
    public function victimsByCase($table, $whereClause)
    {
        $case = $this->getById($table, $whereClause);

        if (!$case)
        {
            return null;
        }
        $crime_sceneID = $case['crime_sceneID'];
        $investigator_id = $case['investigator_id'];
        $court_id = $case['court_id'];
        unset($case['crime_sceneID']);
        unset($case['investigator_id']);
        unset($case['court_id']);
        $crime_scene = $this->getById('crime_scenes', ['crime_sceneID' => $crime_sceneID]);
        $investigators = $this->getById('investigators', ['investigator_id' => $investigator_id]);
        $courts = $this->getById('courts', ['court_id' => $court_id]);
        $victims = $this->victims($whereClause['case_id']);

        $case['crime scene'] = $crime_scene;
        $case['investigator'] = $investigators;
        $case['court'] = $courts;
        $case['victims'] = $victims;
        return $case;
    }

    /**
     * Summary of offenses
     * @param mixed $case_id
     * @return mixed
     * ? No supported filtering and sort by
     */
    private function offenses($case_id)
    {
        $sql = "SELECT offenses.* from offenses inner JOIN cases_offenses ON cases_offenses.offense_id = offenses.offense_id" .
        " INNER JOIN cases on cases.case_id = cases_offenses.case_id WHERE 1";

        $sql .= " AND cases.case_id =:id";
        $query_values['id'] = $case_id;

        $sql .= " GROUP BY offenses.offense_id ";

        return $this->run($sql, $query_values)->fetchAll();
    }

    /**
     * Summary of victims
     * @param mixed $case_id
     * @return mixed
     * ? No supported filtering and sort by
     */
    private function victims($case_id)
    {
        $sql = "SELECT victims.* from victims inner JOIN cases_victims ON cases_victims.victim_id = victims.victim_id" .
        " INNER JOIN cases on cases.case_id = cases_victims.case_id WHERE 1";

        $sql .= " AND cases.case_id =:id";
        $query_values['id'] = $case_id;

        $sql .= " GROUP BY victims.victim_id ";

        return $this->run($sql, $query_values)->fetchAll();
    }

    /**
     * Summary of offenders
     * @param mixed $case_id
     * @return mixed
     * ? No supported filtering and sort by
     */
    private function offenders($case_id)
    {
        $sql = "SELECT offenders.* from offenders inner JOIN offender_details ON offenders.offender_id = offender_details.offender_id" .
        " INNER JOIN cases on cases.case_id = offender_details.case_id WHERE 1";

        $sql .= " AND cases.case_id =:id";
        $query_values['id'] = $case_id;

        $sql .= " GROUP BY offenders.offender_id ";

        return $this->run($sql, $query_values)->fetchAll();
    }


    /**
     * Summary of getAll
     * @param array $filters
     * ? sort_by = {anyColumn.asc|desc}
     * ? supported filters ['description','date_from','date_to', 'misdemeanor]
     * @return array
     * 
     */
    public function getAll(array $filters)
    {
        // Queries the DB and return the list of all films
        $query_values = [];

        $sql = "SELECT cases.*, crime_scenes.crime_sceneID, investigators.investigator_id, courts.court_id FROM cases" .
            " inner join crime_scenes on crime_scenes.crime_sceneID = cases.crime_sceneID" .
            " inner join investigators on investigators.investigator_id = cases.investigator_id" .
            " inner join courts on courts.court_id = cases.court_id WHERE 1";

        if (isset($filters['description'])) {
            $sql .= " AND description LIKE CONCAT('%', :description, '%')";
            $query_values['description'] = $filters['description'];
        }

        if (isset($filters['misdemeanor'])){
            $sql .= " AND misdemeanor =:misdemeanor";
            $query_values['misdemeanor'] = $filters['misdemeanor'];
        }

        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $sql .= " AND DATE(date_reported) BETWEEN :date_from AND :date_to ";
            $query_values['date_from'] = $filters['date_from'];
            $query_values['date_to'] = $filters['date_to'];
        }

        // If sort_by filters are added
        if (isset($filters['sort_by'])) {
            $sql .= ' GROUP BY cases.case_id';

            if (!empty($filters['sort_by'])) {
                $keyword = explode(".", $filters['sort_by']);
                $column = $keyword[0] ?? "";
                $order_by = $keyword[1] ?? "";

                $sql .= " ORDER BY " .   $column . " " .  $order_by;
            }
        }

        // if sort_by doesn't exists then append GROUP BY AT THE END 
        if (!isset($filters["sort_by"])) {
            $sql .= " GROUP BY cases.case_id ";
        }

        $cases = $this->paginate($sql, $query_values, 'cases');

        // merge crime_scene , investigator, court
        foreach ($cases['cases'] as $key => $value) {
            // ? You can add filters too

            $crime_scene = $this->getById('crime_scenes',  ['crime_sceneID' => $value['crime_sceneID']]);
            $investigator = $this->getById('investigators', ['investigator_id' => $value['investigator_id']]);
            $courts = $this->getById('courts', ['court_id' => $value['court_id']]);

            unset($cases['cases'][$key]['crime_sceneID']);
            unset($cases['cases'][$key]['investigator_id']);
            unset($cases['cases'][$key]['court_id']);

            $cases['cases'][$key]['crime_scene'] = $crime_scene;

            $offenses = $this->offenses($cases['cases'][$key]['case_id']);
            $victims = $this->victims($cases['cases'][$key]['case_id']);
            $offenders = $this->offenders($cases['cases'][$key]['case_id']);
    
            $cases['cases'][$key]['investigator'] = $investigator ?? '';
            $cases['cases'][$key]['court'] = $courts ?? '';
            $cases['cases'][$key]['offenses'] =  $offenses ?? '';
            $cases['cases'][$key]['victims'] =  $victims ?? '';
            $cases['cases'][$key]['offenders'] =  $offenders ?? '';
            
           
        }

        return $cases;
        
    }

    /**
     * Summary of createCases
     * @param array $cases
     * @return bool|string
     */
    public function createCases(array $cases) : bool
    {
        
        $offense_ids = $cases['offense_id'];
        $victim_ids = $cases['victim_id'];
        $offender_ids = $cases['offender_id'];
        unset($cases['offense_id']);
        unset($cases['victim_id']);
        unset($cases['offender_id']);
        $case_id = $this->insert("cases", $cases);
        // insert the rest to the junction table
        foreach ($offense_ids as $id)
        {
            $cases_offenses = array("case_id"=>$case_id, "offense_id"=> $id);
            $this->insert('cases_offenses', $cases_offenses);

        }
        foreach ($victim_ids as $id)
        {
            $cases_victims = array("case_id"=>$case_id, "victim_id"=> $id);
            $this->insert('cases_victims', $cases_victims);

        }
        foreach ($offender_ids as $id)
        {
            $offender_details = array("offender_id"=>$id, "case_id"=> $case_id);
            $this->insert('offender_details', $offender_details);

        }
       
        return true;
        
    }

    /**
     * Summary of updateCase
     * @param mixed $cases
     * @return bool
     */
    public function updateCase($cases) : bool
    {
        $case_exists = $this->getById('cases',['case_id'=>$cases['case_id']]);
        // check if the ID exists in the DB
        if (!$case_exists){
            return false;
        }
        $case_id = $cases['case_id'];
        $offense_id = $cases['offense_id'];
        $victim_id = $cases['victim_id'];
        $offender_id = $cases['offender_id'];
        unset($cases['case_id']);
        unset($cases['offense_id']);
        unset($cases['victim_id']);
        unset($cases['offender_id']);
        $cases_offenses = array("case_id"=>$case_id, "offense_id"=> $offense_id);
        $cases_victims = array("case_id"=>$case_id, "victim_id"=> $victim_id);
        $offender_details = array("offender_id"=>$offender_id, "case_id"=> $case_id);

        // updates
        $this->getPdo()->beginTransaction();
        try {
            $this->update("cases", $cases, ['case_id'=>$case_id]);
            $this->getPdo()->commit();
         
        } catch (Exception $e)
        {
            $this->getPdo()->rollBack();
            return false;
        }
        // ? original query UPDATE `cases_offenses` SET `case_id`=15,`offense_id`=2 WHERE case_id=15 AND offense_id=1;
     
        return true;
    }

    /**
     * Summary of checkIfResourceExists
     * @param mixed $table
     * @param mixed $whereClause
     * @return bool
     */
    public function checkIfResourceExists($table, $whereClause) : bool
    {

        if  (!$this->getById($table,$whereClause))
        {
            return false;
        }
        return true;
             
    }
}
