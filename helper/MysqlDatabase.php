<?php

class MysqlDatabase{
    private $connection;

    public function __construct($servername, $username, $password, $dbname){
        $conn = mysqli_connect(
            $servername,
            $username,
            $password,
            $dbname
        );

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->connection = $conn;
    }

    public function query($sql){
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_all($result,MYSQLI_ASSOC);
    }

    public function execute($sql){
        mysqli_query($this->connection, $sql);

    }
    public function executeId($sql){
        mysqli_query($this->connection, $sql);
        return mysqli_insert_id($this->connection);
    }
    /*if(!empty(mysqli_error($this->connection)))
            die(var_dump(mysqli_error($this->connection)));*/
}