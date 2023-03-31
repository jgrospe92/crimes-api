<?php

namespace Vanier\Api\models;


use Vanier\Api\Models\BaseModel;

use Exception;

/**
 * Summary of InvestigatorsModel
 */
class InvestigatorsModel extends BaseModel
{
    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Summary of getAll
     * @param mixed $filters
     * @return array
     */
    public function getAll($filters)
    {
        $query_values = [];
        $sql = "SELECT * FROM investigators WHERE 1";


        if (isset($filters['badge_number'])) {
            $sql .= " AND badge_number LIKE CONCAT(:badge_number, '%')";
            $query_values['badge_number'] = $filters['badge_number'];
        }

        if (isset($filters['first_name'])) {
            $sql .= " AND first_name LIKE CONCAT(:first_name, '%')";
            $query_values['first_name'] = $filters['first_name'];
        }


        if (isset($filters['last_name'])) {
            $sql .= " AND last_name LIKE CONCAT(:last_name, '%')";
            $query_values['last_name'] = $filters['last_name'];
        }

        if (isset($filters['rank'])) {
            $sql .= " AND rank LIKE CONCAT('%', :rank, '%')";
            $query_values['rank'] = $filters['rank'];
        }

        // If sort_by filters are added
        if (isset($filters['sort_by'])) {
            $sql .= ' GROUP BY investigator_id ';

            if (!empty($filters['sort_by'])) {
                $keyword = explode(".", $filters['sort_by']);
                $column = $keyword[0] ?? "";
                $order_by = $keyword[1] ?? "";

                $sql .= " ORDER BY " . $column . " " . $order_by;
            }
        }

        // if sort_by doesn't exists then append GROUP BY AT THE END 
        if (!isset($filters["sort_by"])) {
            $sql .= " GROUP BY investigator_id ";
        }

        return $this->paginate($sql, $query_values, 'investigators');

    }
}