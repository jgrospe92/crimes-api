<?php
namespace Vanier\Api\models;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\BaseModel;
use Vanier\Api\models\ActorsModel;
use Exception;

class CasesModel extends BaseModel
{
    private $table_name = "cases";

    public function __construct(){
        parent::__construct();
    }

    public function getCaseById($table, $filters)
    {
        return $this->getById($table, $filters);
    }

    public function getAll(array $filters)
    {
         // Queries the DB and return the list of all films
         $query_values = [];
         
         $sql = "SELECT * FROM " . $this->table_name;


         $cases = $this->paginate($sql, $query_values);
         return $cases;
    }

    
}