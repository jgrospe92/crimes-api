<?php
namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;

/**
 * Summary of CourtAddressesModel
 */
class CourtAddressesModel extends BaseModel
{
    private $table_name = "court_addresses";

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Summary of handleGetAllAddresses
     * @param array $filters
     * @return array
     */
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
        // will not work because of the # in the attribute "building_#"!!!!
        if(isset($filters["building_num"])){
            $sql .= " AND building_num LIKE CONCAT(:building_num, '%') ";
            $query_values["building_num"] = $filters["building_num"];
        }
        
        if(isset($filters["postal_code"])){
            $sql .= " AND postal_code LIKE CONCAT(:postal_code, '%') ";
            $query_values["postal_code"] = $filters["postal_code"];
        }

        if(isset($filters["sorted_by"])){
            $sort_by = $filters["sort_by"];
            if($sort_by == "address_id"){
                $sql .= " ORDER BY address_id";
            }
            elseif ($sort_by == "city") {
                $sql .= " ORDER BY city";
            }elseif ($sort_by == "city.asc") {
                $sql .= " ORDER BY city asc";
            }elseif ($sort_by == "city.desc") {
                $sql .= " ORDER BY city desc";
            }
            elseif ($sort_by == "street") {
                $sql .= " ORDER BY street";
            }elseif ($sort_by == "street.asc") {
                $sql .= " ORDER BY street asc";
            }elseif ($sort_by == "street.desc") {
                $sql .= " ORDER BY street desc";
            }
            elseif ($sort_by == "postal_code") {
                $sql .= " ORDER BY postal_code";
            }elseif ($sort_by == "postal_code.asc") {
                $sql .= " ORDER BY postal_code asc";
            }elseif ($sort_by == "postal_code.desc") {
                $sql .= " ORDER BY postal_code desc";
            }
            elseif($sort_by == "building_num"){
                $sql .= " ORDER BY building_num";
            }elseif($sort_by == "building_num.asc"){
                $sql .= " ORDER BY building_num asc";
            }elseif($sort_by == "building_num.desc"){
                $sql .= " ORDER BY building_num desc";
            }
        }

        return $this->paginate($sql,$query_values, 'court_addresses');
    }

    /**
     * Summary of handleGetAddressById
     * @param string $address_id
     * @return mixed
     */
    public function handleGetAddressById(String $address_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE address_id = :address_id ";
        return $this->run($sql,["address_id"=>$address_id])->fetch();
    }

    public function handleCreateAddresses(array $address)
    {
        return $this->insert($this->table_name, $address);
    }

    public function handleUpdateAddressById(array $address, String $address_id)
    {  
        return $this->update($this->table_name, $address, ["address_id" => $address_id]);
    }

    public function checkIfResourceExists($table, $whereClause): bool
    {
        if (!$this->getById($table, $whereClause)) {
            return false;
        }
        return true;
    }

}
