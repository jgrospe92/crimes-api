<?php

namespace Vanier\Api\Models;

use DateTime;
use DateTimeZone;

/**
 * A model for managing the Web service users.
 *
 * @author Jeffrey Grospe
 */
class UserDBLogModel extends BaseModel
{

    private $table_name = "ws_log";

    /**
     * A model class for the `ws_users` database table.
     * It exposes operations for creating and authenticating in users.
     */
    function __construct()
    {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Summary of logIntoDB
     * @param mixed $data
     * @return void
     */
    public function logIntoDB(array $data)
    {
        $this->insert($this->table_name, $data);
    }
}
