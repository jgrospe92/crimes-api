<?php

namespace Vanier\Api\Models;

/**
 * Summary of OffendersModel
 */
class OffendersModel extends BaseModel
{
    private $table_name;
    private $sql;

    /**
     * Summary of __construct
     */
    public function __construct() 
    {
        parent::__construct();
        $this->table_name = "offenders";
        $this->sql =  
            "SELECT 
                offenders.offender_id,  
                offenders.first_name        AS offender_first_name,       
                offenders.last_name         AS offender_last_name,
                offenders.age               AS offender_age,
                offenders.marital_status,
                offenders.arrest_date,
                offenders.arrest_timestamp,
                defendants.defendant_id,
                defendants.first_name       AS defendant_first_name,
                defendants.last_name        AS defendant_last_name, 
                defendants.age              AS defendant_age,
                defendants.specialization
            FROM $this->table_name
            LEFT JOIN defendants ON offenders.defendant_id = defendants.defendant_id
            WHERE 1 ";
    }

    /**
     * Summary of getOffenderById
     * @param mixed $offender_id
     * @return array<array>
     */
    public function getOffenderById($offender_id) 
    {
        $this->sql .= "AND offender_id = :offender_id ";
        $result = $this->run($this->sql, [':offender_id' => $offender_id])->fetchAll();

        // If $result is not empty, put the person's data in an associative array
        if ($result)
        {
            $result = $result[0];
            $offender = 
                [
                    "offender_id"       => $result["offender_id"],
                    "first_name"        => $result["offender_first_name"],
                    "last_name"         => $result["offender_last_name"],
                    "age"               => $result["offender_age"],
                    "marital_status"    => $result["marital_status"],
                    "arrest_date"       => $result["arrest_date"],
                    "arrest_timestamp"  => $result["arrest_timestamp"]
                ];
    
            $defendant = 
                [
                    "defendant_id"      => $result["defendant_id"],
                    "first_name"        => $result["defendant_first_name"],
                    "last_name"         => $result["defendant_last_name"],
                    "age"               => $result["defendant_age"],
                    "specialization"    => $result["specialization"]
                ];
                
            $offenders[] = [ "offender" => $offender, "defendant" => $defendant ];
        } 
        else
        {   
            // else return an empty array, will throw exception in controller method
            return [];
        }
        
        return $offenders;
    }

    /**
     * Summary of getAllOffenders
     * @param array $filters
     * @return array
     * Supported filters for ID, first_name, last_name, age, marital_status, date, and time
     */
    public function getAllOffenders(array $filters = []) 
    {
        $query_values = [];

        if (isset($filters["id"]))
        {
            $this->sql .= " AND offender_id = :offender_id ";
            $query_values[":offender_id"] = $filters["id"];
        }
        
        if (isset($filters["first-name"]))
        {
            $this->sql .= " AND offenders.first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["first-name"] . "%";
        }

        if (isset($filters["last-name"]))
        {
            $this->sql .= " AND offenders.last_name  LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last-name"] . "%";
        }

        if (isset($filters["age"]))
        {
            $this->sql .= " AND offenders.age = :age ";
            $query_values[":age"] = $filters["age"];
        }

        if (isset($filters["marital-status"]))
        {
            $this->sql .= " AND marital_status LIKE CONCAT(:marital_status, '%') ";
            $query_values[":marital_status"] = $filters["marital-status"] . "%";
        }

        if (isset($filters["date-min"]) && isset($filters["date-max"])) // Between 2 dates
        {
            $this->sql .= " AND arrest_date BETWEEN :date_min AND :date_max ";
            $query_values[":date_min"] = $filters["date-min"];
            $query_values[":date_max"] = $filters["date-max"];
        } 
        elseif (isset($filters["date-min"]))                            // From this date and later   
        {
            $this->sql .= " AND arrest_date >= :date_min ";
            $query_values[":date_min"] = $filters["date-min"];
        } 
        elseif (isset($filters["date-max"]))                            // From this date and earlier                
        {
            $this->sql .= " AND arrest_date <= :date_max ";
            $query_values[":date_max"] = $filters["date-max"];
        }
        
        if (isset($filters["time-min"]) && isset($filters["time-max"])) // Between 2 times
        {
            $this->sql .= " AND arrest_timestamp BETWEEN :time_min AND :time_max ";
            $query_values[":time_min"] = $filters["time-min"];
            $query_values[":time_max"] = $filters["time-max"];
        } 
        elseif (isset($filters["time-min"]))                            // From this time and later   
        {
            $this->sql .= " AND arrest_timestamp >= :time_min ";
            $query_values[":time_min"] = $filters["time-min"];
        } 
        elseif (isset($filters["time-max"]))                            // From this time and earlier                
        {
            $this->sql .= " AND arrest_timestamp <= :time_max ";
            $query_values[":time_max"] = $filters["time-max"];
        }

        // Sorting filters
        if (isset($filters["sort"]))
        {
            $sort = $filters["sort"];
            if($sort == "first-name")           { $this->sql .= " ORDER BY offenders.first_name"; } 
            elseif($sort == "last-name")        { $this->sql .= " ORDER BY offenders.last_name"; } 
            elseif($sort == "age")              { $this->sql .= " ORDER BY offenders.age"; }
            elseif($sort == "marital_status")   { $this->sql .= " ORDER BY offenders.marital_status"; } 
            elseif($sort == "date")             { $this->sql .= " ORDER BY offenders.arrest_date"; } 
            elseif($sort == "time")             { $this->sql .= " ORDER BY offenders.arrest_timestamp"; }
        } 
        else 
        {
            $this->sql .= " ORDER BY offenders.offender_id";
        }

        $result = $this->paginate($this->sql, $query_values, 'offenders');

        $offenders = [];
        // Put each person's data in an associative array
        foreach ($result["offenders"] as $row)
        {
            $offender = 
            [
                "offender_id"       => $row["offender_id"],
                "first_name"        => $row["offender_first_name"],
                "last_name"         => $row["offender_last_name"],
                "age"               => $row["offender_age"],
                "marital_status"    => $row["marital_status"],
                "arrest_date"       => $row["arrest_date"],
                "arrest_timestamp"  => $row["arrest_timestamp"]
            ];
        
            $defendant = 
            [
                "defendant_id"      => $row["defendant_id"],
                "first_name"        => $row["defendant_first_name"],
                "last_name"         => $row["defendant_last_name"],
                "age"               => $row["defendant_age"],
                "specialization"    => $row["specialization"]
            ];

            $offenders[] = [ "offender" => $offender, "defendant" => $defendant ];
        }

        $result['offenders'] = $offenders;
        return $result;
    }

    /**
     * Summary of getDefendantOfOffender
     * @param mixed $offender_id
     * @return array
     */
    public function getDefendantOfOffender($offender_id) 
    {
        $this->sql .= "AND offender_id = :offender_id ";
        $result = $this->run($this->sql, [':offender_id' => $offender_id])->fetchAll();

        // If $result is not empty, put the person's defendant's data in an associative array
        if ($result)
        {
            $result = $result[0];
            $defendant = 
                [
                    "defendant_id"      => $result["defendant_id"],
                    "first_name"        => $result["defendant_first_name"],
                    "last_name"         => $result["defendant_last_name"],
                    "age"               => $result["defendant_age"],
                    "specialization"    => $result["specialization"]
                ];
            return $defendant;    
        } 
        else
        {   
            // else return an empty array, will throw exception in controller method
            return [];
        }
    }

    /**
     * Summary of getCaseOfOffender
     * @param mixed $offender_id
     * @return array<array>
     */
    public function getCaseOfOffender($offender_id) 
    {
        $this->sql = 
            "SELECT 
                offenders.offender_id,
                offender_details.*,
                cases.*,
                crime_scenes.*,
                investigators.*,
                courts.*
            FROM offenders
            LEFT JOIN offender_details  ON offenders.offender_id = offender_details.offender_id
            LEFT JOIN cases             ON offender_details.case_id = cases.case_id
            LEFT JOIN crime_scenes      ON cases.crime_sceneID = crime_scenes.crime_sceneID
            LEFT JOIN investigators     ON cases.investigator_id = investigators.investigator_id
            LEFT JOIN courts            ON cases.court_id = courts.court_id
            WHERE offenders.offender_id = :offender_id
            ";
        $result = $this->run($this->sql, [':offender_id' => $offender_id])->fetchAll();

        // If $result is not empty, put the person's defendant's data in an associative array
        if ($result)
        {
            $result = $result[0];
            $case = 
                [
                    "case_id"           => $result["case_id"],
                    "description"       => $result["description"],
                    "date_reported"     => $result["date_reported"],
                    "misdemeanor"       => $result["misdemeanor"],
                    "crime_sceneID"     => $result["crime_sceneID"],
                    "investigator_id"   => $result["investigator_id"],
                    "court_id"          => $result["court_id"],

                ];

            $crime_scene = 
                [
                    "crime_sceneID"     => $result["crime_sceneID"],
                    "province"          => $result["province"],
                    "city"              => $result["city"],
                    "street"            => $result["street"],
                    "building_number"   => $result["building_number"]
                ];

            $investigator = 
                [
                    "investigator_id"   => $result["investigator_id"],
                    "badge_number"      => $result["badge_number"],
                    "first_name"        => $result["first_name"],
                    "last_name"         => $result["last_name"],
                    "rank"              => $result["rank"]
                ];

            $court =
                [
                    "court_id"          => $result["court_id"],
                    "name"              => $result["badge_number"],
                    "date"              => $result["name"],
                    "time"              => $result["date"],
                    "address_id"        => $result["address_id"],
                    "judge_id"          => $result["judge_id"],
                    "verdict_id"        => $result["verdict_id"]
                ];

            $offender_case = [ "case" => $case, "crime scene" => $crime_scene, "investigator" => $investigator, "court" => $court ];    
            return $offender_case;
        } 
        else
        {   
            // else return an empty array, will throw exception in controller method
            return [];
        }
    }

    /**
     * Summary of postOffender
     * @param array $data
     * @return bool|string
     */
    public function postOffender(array $data) 
    {
        return $this->insert($this->table_name, $data);
    }

    /**
     * Summary of putOffender
     * @param mixed $offender
     * @return mixed
     */
    public function putOffender($offender) 
    {
        $offender_id = $offender['offender_id'];
        unset($offender['offender_id']);
        return $this->update($this->table_name, $offender, ['offender_id' => $offender_id]);
    }

    public function deleteOffender($offender) 
    {
        $where = ['offender_id' => $offender['offender_id']];
        unset($offender['offender_id']);
        return $this->delete($this->table_name, $where);
    }
}
