<?php

class UserRequest {
    public $email;
    public $password;
    public $nom ="";
    public $prenom = "";
    public function __construct($body) {
        var_dump($body);
        $this->name = $name;
        $this->password = $password;
    }
}