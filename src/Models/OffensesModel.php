<?php

namespace Vanier\Api\models;


use Vanier\Api\Models\BaseModel;

use Exception;

/**
 * Summary of OffensesModel
 */
class OffensesModel extends BaseModel
{

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Summary of getOffenses
     * @param mixed $case_id
     * @param array $filters
     * @return mixed
     * ? filter by name, description, classification
     * ? sort_by columName.asc|desc
     */
    public function getOffensesById($table, $whereClause)
    {
        $offense = $this->getById($table, $whereClause);
        if (!$offense) {
            return null;
        }
        return $offense;
    }

    /**
     * Summary of getOffenses
     * @param array $filters
     * @return array
     */
    public function getOffenses(array $filters)
    {
        $query_values = [];

        $sql = "SELECT * from offenses WHERE 1";

        if (isset($filters['name'])) {
            $sql .= " AND offenses.name LIKE CONCAT(:name, '%') ";
            $query_values[':name'] = $filters['name'];
        }

        if (isset($filters['description'])) {
            $sql .= " AND description LIKE CONCAT('%', :description, '%')";
            $query_values['description'] = $filters['description'];
        }

        if (isset($filters['classification'])) {
            $sql .= " AND classification LIKE CONCAT('%', :classification, '%')";
            $query_values['classification'] = $filters['classification'];
        }

        if (isset($filters['sort_by'])) {
            // Append GROUP BY before ORDER BY
            $sql .= " GROUP BY offense_id ";

            if (!empty($filters['sort_by'])) {
                $keyword = explode(".", $filters['sort_by']);
                $column = $keyword[0] ?? "";
                $order_by = $keyword[1] ?? "";
                $sql .= " ORDER BY " .   $column . " " .  $order_by;
            }
        }
        if (!isset($filters["sort_by"])) {
            $sql .= " GROUP BY offense_id ";
        }

        return $this->paginate($sql, $query_values, 'offenses');
    }

    /**
     * Summary of updateOffense
     * @param mixed $offense
     * @return void
     */
    public function updateOffense($offense)
    {
        $offense_id = $offense['offense_id'];
        unset($offense['offense_id']);
        $this->update('offenses', $offense, ['offense_id' => $offense_id]);
    }

    /**
     * Summary of createOffenses
     * @param mixed $offense
     * @return bool|string
     */
    public function createOffenses($offense)
    {
        unset($offense['offense_id']);
        return $this->insert('offenses', $offense);
    }

    /**
     * Summary of deleteOffense
     * @param mixed $id
     * @return void
     */
    public function deleteOffense($id)
    {
        $this->delete('offenses', ['offense_id' => $id]);
    }
}
