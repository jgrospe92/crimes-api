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

    public function getCaseById($table, $id)
    {
        return $this->getById($table, $id);
    }

    
}