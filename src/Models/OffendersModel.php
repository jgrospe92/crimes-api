<?php

namespace Vanier\Api\Models;

class OffendersModel extends BaseModel
{
    private $sql = 
        "SELECT 
            offenders.*,
            defendants.*
        FROM offenders
        INNER JOIN defendants ON offenders.defendant_id = defendants.defendant_id
        WHERE 1 ";

    public function __construct() 
    {
        parent::__construct();
    }

    public function getOffenderById($offender_id) 
    {
        $this->sql .= "AND offender_id = :offender_id ";
        $result = $this->run($this->sql, [':offender_id' => $offender_id])->fetchAll();

        $table = [];
        $offender = 
        [
            "offender_id"       => $result[0]["offender_id"],
            "first_name"        => $result[0]["first_name"],
            "last_name"         => $result[0]["last_name"],
            "age"               => $result[0]["age"],
            "marital_status"    => $result[0]["marital_status"],
            "arrest_date"       => $result[0]["arrest_date"],
            "arrest_timestamp"  => $result[0]["arrest_timestamp"]
        ];
    
        $defendant = 
        [
            "defendant_id"      => $result[0]["defendant_id"],
            "first_name"        => $result[0]["first_name"],
            "last_name"         => $result[0]["last_name"],
            "age"               => $result[0]["age"],
            "specialization"    => $result[0]["specialization"]
        ];

        $table[] = 
        [
            "offender"          => $offender,
            "defendant"         => $defendant
        ];

        $result = $table;
        return $result;
    }

    public function getAllOffenders(array $filters = []) 
    {
        $query_values = [];

        if(isset($filters["id"]))
        {
            $this->sql .= " AND offender_id = :offender_id ";
            $query_values[":offender_id"] = $filters["id"];
        }
        
        if(isset($filters["firstName"]))
        {
            $this->sql .= " AND first_name LIKE CONCAT(:first_name,'%') ";
            $query_values[":first_name"] = $filters["firstName"]."%";
        }

        if(isset($filters["lastName"]))
        {
            $this->sql .= " AND last_name LIKE CONCAT(:last_name,'%') ";
            $query_values[":last_name"] = $filters["lastName"]."%";
        }

        if(isset($filters["age"]))
        {
            $this->sql .= " AND age = :age ";
            $query_values[":age"] = $filters["age"];
        }

        if(isset($filters["marital"]))
        {
            $this->sql .= " AND marital_status LIKE CONCAT(:marital_status, '%') ";
            $query_values[":marital_status"] = $filters["marital"] . "%";
        }

        if(isset($filters["dateMin"]))
        {
            $this->sql .= " AND arrest_date > :arrest_date ";
            $query_values[":arrest_date"] = $filters["dateMin"];
        }

        if(isset($filters["dateMax"]))
        {
            $this->sql .= " AND arrest_date < :arrest_date ";
            $query_values[":arrest_date"] = $filters["dateMax"];
        }
        
        if(isset($filters["timeMin"]))
        {
            $this->sql .= " AND arrest_timestamp >= :arrest_timestamp ";
            $query_values[":arrest_timestamp"] = $filters["timeMin"];
        }

        if(isset($filters["timeMax"]))
        {
            $this->sql .= " AND arrest_timestamp <= :arrest_timestamp ";
            $query_values[":arrest_timestamp"] = $filters["timeMax"];
        }

        if(isset($filters["sort"]))
        {
            $sort = $filters["sort"];
            if($sort == "first_name")
            {
                $this->sql .= " ORDER BY offenders.first_name";
            } elseif($sort == "last_name")
            {
                $this->sql .= " ORDER BY offenders.last_name";
            } elseif($sort == "age")
            {
                $this->sql .= " ORDER BY offenders.age";
            } elseif($sort == "marital_status")
            {
                $this->sql .= " ORDER BY offenders.marital_status";
            } elseif($sort == "date")
            {
                $this->sql .= " ORDER BY offenders.arrest_date";
            } elseif($sort == "time")
            {
                $this->sql .= " ORDER BY offenders.arrest_timestamp";
            }
        }

        $result = $this->paginate($this->sql, $query_values);

        $table = [];
        foreach($result['data'] as $row)
        {
            $offender = 
            [
                "offender_id"       => $row["offender_id"],
                "first_name"        => $row["first_name"],
                "last_name"         => $row["last_name"],
                "age"               => $row["age"],
                "marital_status"    => $row["marital_status"],
                "arrest_date"       => $row["arrest_date"],
                "arrest_timestamp"  => $row["arrest_timestamp"]
            ];
        
            $defendant = 
            [
                "defendant_id"      => $row["defendant_id"],
                "first_name"        => $row["first_name"],
                "last_name"         => $row["last_name"],
                "age"               => $row["age"],
                "specialization"    => $row["specialization"]
            ];

            $table[] = 
            [
                "offender" => $offender,
                "defendant" => $defendant
            ];
        }

        $result["data"] = $table;
        return $result;
    }
}
