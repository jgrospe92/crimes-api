<?php

namespace Vanier\Api\Models;

class OffendersModel extends BaseModel
{
    private $sql = 
        "SELECT 
            offenders.offender_id,  
            offenders.first_name        AS offender_first_name,       
            offenders.last_name         AS offender_last_name,
            offenders.age               AS offender_age,
            offenders.marital_status,
            defendants.defendant_id,
            defendants.first_name       AS defendant_first_name,
            defendants.last_name        AS defendant_last_name, 
            defendants.age              AS defendant_age,
            defendants.specialization
        FROM offenders
        LEFT JOIN defendants ON offenders.defendant_id = defendants.defendant_id
        WHERE 1 ";

    public function __construct() 
    {
        parent::__construct();
    }

    public function getOffenderById($offender_id) 
    {
        $this->sql .= "AND offender_id = :offender_id ";
        $result = $this->run($this->sql, [':offender_id' => $offender_id])->fetchAll()[0];

        $offender = 
        [
            "offender_id"       => $result["offender_id"],
            "first_name"        => $result["offender_first_name"],
            "last_name"         => $result["offender_last_name"],
            "age"               => $result["offender_age"],
            "marital_status"    => $result["marital_status"]
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

        return $offenders;
    }

    public function getAllOffenders(array $filters = []) 
    {
        $query_values = [];

        if(isset($filters["id"]))
        {
            $this->sql .= " AND offender_id = :offender_id ";
            $query_values[":offender_id"] = $filters["id"];
        }
        
        if(isset($filters["first-name"]))
        {
            $this->sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["first-name"]."%";
        }

        if(isset($filters["last-name"]))
        {
            $this->sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["last-name"]."%";
        }

        if(isset($filters["age"]))
        {
            $this->sql .= " AND age = :age ";
            $query_values[":age"] = $filters["age"];
        }

        if(isset($filters["marital-status"]))
        {
            $this->sql .= " AND marital_status LIKE CONCAT(:marital_status, '%') ";
            $query_values[":marital_status"] = $filters["marital-status"] . "%";
        }

        if(isset($filters["date-min"]) && isset($filters["date-max"]))  // Between 2 dates
        {
            $this->sql .= " AND arrest_date BETWEEN :date_min AND :date_max ";
            $query_values[":date_min"] = $filters["date-min"];
            $query_values[":date_max"] = $filters["date-max"];
        } elseif (isset($filters["date-min"]))                          // From this date and later   
        {
            $this->sql .= " AND arrest_date > :date_min ";
            $query_values[":date_min"] = $filters["date-min"];
        } elseif (isset($filters["date-max"]))                          // From this date and earlier                
        {
            $this->sql .= " AND arrest_date < :date_max ";
            $query_values[":date_max"] = $filters["date-max"];
        }
        
        if(isset($filters["time-min"]) && isset($filters["time-max"]))  // Between 2 times
        {
            $this->sql .= " AND arrest_timestamp BETWEEN :time_min AND :time_max ";
            $query_values[":time_min"] = $filters["time-min"];
            $query_values[":time_max"] = $filters["time-max"];
        } elseif (isset($filters["time-min"]))                          // From this time and later   
        {
            $this->sql .= " AND arrest_timestamp > :time_min ";
            $query_values[":time_min"] = $filters["time-min"];
        } elseif (isset($filters["time-max"]))                          // From this time and earlier                
        {
            $this->sql .= " AND arrest_timestamp < :time_max ";
            $query_values[":time_max"] = $filters["time-max"];
        }

        if(isset($filters["sort"]))
        {
            $sort = $filters["sort"];
            if($sort == "first_name")           { $this->sql .= " ORDER BY offenders.first_name"; } 
            elseif($sort == "last_name")        { $this->sql .= " ORDER BY offenders.last_name"; } 
            elseif($sort == "age")              { $this->sql .= " ORDER BY offenders.age"; }
            elseif($sort == "marital_status")   { $this->sql .= " ORDER BY offenders.marital_status"; } 
            elseif($sort == "date")             { $this->sql .= " ORDER BY offenders.arrest_date"; } 
            elseif($sort == "time")             { $this->sql .= " ORDER BY offenders.arrest_timestamp"; }
        }

        $result = $this->paginate($this->sql, $query_values);

        foreach($result["data"] as $row)
        {
            $offender = 
            [
                "offender_id"       => $row["offender_id"],
                "first_name"        => $row["offender_first_name"],
                "last_name"         => $row["offender_last_name"],
                "age"               => $row["offender_age"],
                "marital_status"    => $row["marital_status"]
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

        $result['data'] = $offenders;
        return $result;
    }
}
