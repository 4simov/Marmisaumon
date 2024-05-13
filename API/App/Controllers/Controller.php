<?php

namespace Controllers;
use System;
use System\DatabaseConnector;

class Controller {
    public $db;
    public function __construct() {
        $this->db = new DatabaseConnector();
    }
}