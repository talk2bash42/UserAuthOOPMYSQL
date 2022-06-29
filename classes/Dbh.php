<?php

class Dbh
{
    private $hostname;
    private $username;
    private $password;
    private $dbname;

    public function connect()
    {
        $this->hostname = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "zuriphp";

        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
        if (!$conn) {
            echo "<script> alert('Error connecting to the database') </script>";
        } else
            return $conn;
           //echo "<script> alert('Connection Successful') </script>";
    }

    
}
