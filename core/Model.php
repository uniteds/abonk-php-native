<?php

namespace App\Core;

/**
 * Base Model class
 */
abstract class Model {
    protected $db;

    public function __construct() {
        // Automatically inject database wrapper connection
        $this->db = Database::getInstance();
    }
}
