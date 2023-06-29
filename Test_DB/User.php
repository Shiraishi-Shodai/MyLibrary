<?php

class User
{

    private $user_id;
    private $user_name;
    private $affiliation_id;
    private $affiliation_name;
    private $user_type_name;
    private $user_type_id;
    private $password;

    public function __construct($user_id, $user_name, $affiliation_id, $affiliation_name, $user_type_name,  $user_type_id, $password)
    {

        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->affiliation_id = $affiliation_id;
        $this->affiliation_name = $affiliation_name;
        $this->user_type_name = $user_type_name;
        $this->user_type_id = $user_type_id;
        $this->password = $password;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }
    public function getUserName(): string
    {
        return $this->user_name;
    }
    public function getAffiliationId(): string
    {
        return $this->affiliation_id;
    }
    public function getAffiliationName(): string
    {
        return $this->affiliation_name;
    }
    public function getUserTypeName(): string
    {
        return $this->user_type_name;
    }
    public function getUserTypeId(): string
    {
        return $this->user_type_id;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
}
