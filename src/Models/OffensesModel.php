<?php

namespace Vanier\Api\models;

use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\BaseModel;
use Vanier\Api\models\ActorsModel;
use Exception;

class OffensesModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getOffenses($case_id, array $filters)
    {
        $query_values = [];

        $sql = "SELECT offenses.* from offenses inner JOIN cases_offenses ON cases_offenses.offense_id = offenses.offense_id" .
            " INNER JOIN cases on cases.case_id = cases_offenses.case_id WHERE 1";

        $sql .= " AND cases.case_id =:id";
        $query_values['id'] = $case_id;

        if (isset($filters['name']))
        {
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
            $sql .= " GROUP BY offenses.offense_id ";

            if (!empty($filters['sort_by'])) {
                $keyword = explode(".", $filters['sort_by']);
                $column = $keyword[0] ?? "";
                $order_by = $keyword[1] ?? "";
                $sql .= " ORDER BY " .   $column . " " .  $order_by;
            }
        }
        if (!isset($filters["sort_by"])){
            $sql .= " GROUP BY offenses.offense_id ";
        }

        return $this->run($sql, $query_values)->fetchAll();
    }
}
