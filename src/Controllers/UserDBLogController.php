<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\UserDBLogModel;


class UserDBLogController extends BaseController
{


    private $userDBlogger = null;

    public function __construct()
    {
        $this->userDBlogger = new UserDBLogModel();
    }

    public function handleDBLogger(array $data)
    {
        $this->userDBlogger->logIntoDB($data);
    }
}
