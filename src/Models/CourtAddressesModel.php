<?php
namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

class CourtAddressesModel extends BaseModel
{
    private $table_name = "court_addresses";

    public function __construct()
    {
        parent::__construct();
    }
    public function handleGetAllAddresses(array $filters = [])
    {
        $query_values = [];
        $sql = "SELECT * FROM $this->table_name WHERE 1 ";
        
        if(isset($filters["address_id"])){
            $sql .= " AND address_id LIKE :address_id ";
            $query_values[":address_id"] = $filters["address_id"];
        }
        if(isset($filters["city"])){
            $sql .= " AND city LIKE CONCAT(:city,'%') ";
            $query_values[":city"] = $filters["city"];
        }
        if(isset($filters["street"])){
            $sql .= " AND street LIKE CONCAT(:street, '%') ";
            $query_values["street"] = $filters["street"];
        }
        /* will not work because of the # in the attribute "building_#"!!!!
        if(isset($filters["building_#"])){
            $sql .= " AND `building_#` LIKE CONCAT(`:building_#`, '%') ";
            $query_values["building_#"] = $filters["building_#"];
        }
        */
        if(isset($filters["postal_code"])){
            $sql .= " AND postal_code LIKE CONCAT(:postal_code, '%') ";
            $query_values["postal_code"] = $filters["postal_code"];
        }
        return $this->paginate($sql,$query_values);
    }

    public function handleGetAddressById(String $address_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE address_id = :address_id ";
        return $this->run($sql,["address_id"=>$address_id])->fetch();
    }

}
