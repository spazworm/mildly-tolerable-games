<?php

class User {
    private $userID;
    private $firstName;
    private $lastName;
    private $unitNo;
    private $streetNo;
    private $streetName;
    private $cityTownName;
    private $state;
    private $country;
    private $developerStatus;
    private $administratorstatus;
    private $password;
    private $email;
    private $postcode;
    private $username;
        
    function __construct($userID = "Not Provided", 
                        $firstName = "Not Provided", 
                        $lastName = "Not Provided", 
                        $unitNo = "Not Provided", 
			$streetNo = "Not Provided", 
                        $streetName = "Not Provided", 
                        $cityTownName = "Not Provided", 
                        $state = "Not Provided", 
                        $country = "Not Provided", 
			$developerStatus = "Not Provided", 
                        $administratorstatus = "Not Provided", 
                        $password = "Not Provided", 
                        $email = "Not Provided", 
			$postcode = "Not Provided",
                        $username = "Not Provided") {
        $this->userID = $userID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->unitNo = $unitNo;
        $this->streetNo = $streetNo;
        $this->streetName = $streetName;
        $this->cityTownName = $cityTownName;
        $this->state = $state;
        $this->country = $country;
        $this->developerStatus = $developerStatus;
        $this->administratorstatus = $administratorstatus;
        $this->password = $password;
        $this->email = $email;
        $this->postcode = $postcode;
        $this->username = $username;
    }
    
    public function getUserID() {
        return $this->userID;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getUnitNo() {
        return $this->unitNo;
    }

    public function getStreetNo() {
        return $this->streetNo;
    }

    public function getStreetName() {
        return $this->streetName;
    }

    public function getCityTownName() {
        return $this->cityTownName;
    }

    public function getState() {
        return $this->state;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getDeveloperStatus() {
        return $this->developerStatus;
    }

    public function getAdministratorstatus() {
        return $this->administratorstatus;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPostcode() {
        return $this->postcode;
    }

    public function getUsername() {
        return $this->username;
    }



}

?>
