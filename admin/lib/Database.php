<?php

//Class for database functions
class DataBase{
    public $pdo = '';

    const DB_DEBUG = false;

    //Function to construct a database link
    public function __construct($dataBaseUser, $dataBaseName){
        $this -> pdo = null;

        include 'pass.php';
        $DataBasePassword = '';

        switch(substr($dataBaseUser, strpos($dataBaseUser, "_") + 1)){
            case 'reader':
                $DataBasePassword = $dbReader;
                break;
            case 'writer':
                $DataBasePassword = $dbWriter;
                break;
        }

        $query = NULL;

        $dsn = 'mysql:host=webdb.uvm.edu;dbname=';

        if(self::DB_DEBUG){
            echo "<p>Try connecting with phpMyAdmin with these credentials.</php>";
            echo '<p>Username: ' . $dataBaseUser;
            echo '<p>DSN: ' . $dsn . $dataBaseName;
            echo '<p>Password: ' . $DataBasePassword;
        }

        try{
            $this -> pdo = new PDO($dsn . $dataBaseName, $dataBaseUser, $DataBasePassword);

            if(!$this -> pdo){
                if(self::DB_DEBUG) echo '<p>You are NOT connected to the database!</p>';
                return 0;
            }else{
                if(self::DB_DEBUG) echo'<p>You are connected to the database!</p>';
                return $this -> pdo;
            }
        } catch(PDOException $e){
            $error_message = $e -> getMessage();
            if(self::DB_DEBUG) echo "<p>An error occured while connecting to the database: $error_message</p>";
        }
    }

    //Function to select a value
    public function select($query, $values = ''){
        $statement = $this -> pdo -> prepare($query);

        if(is_array($values)){
            $statement -> execute($values);
        }else{
            $statement -> execute();
        }

        $recordSet = $statement -> fetchAll(PDO::FETCH_ASSOC);

        $statement -> closeCursor();

        return $recordSet;
    }

    //Function to create an insert query for SQL
    function displayQuery($query, $values = '') {
        if ($values != '') {
            $needle = '?';
            $haystack = $query;
            foreach ($values as $value) {
                $pos = strpos($haystack, $needle);
                if ($pos !== false) {
                    
                    $haystack = substr_replace($haystack, '"' . $value . '"', $pos, strlen($needle));
                }
            }
            $query = $haystack;
        }
        return $query;
    }

    //Function to insert a query
    public function insert($query, $values = ''){
        $status = false;
        $statement = $this -> pdo -> prepare($query);

        if(is_array($values)){
            $status = $statement -> execute($values);
        }else{
            $status = $statement -> execute();
        }
        return $status;
    }

    //Function to update a record
    public function update($query, $values = ''){
        $status = false;
        $statement = $this -> pdo -> prepare($query);

        if(is_array($values)){
            $status = $statement -> execute($values);
        } else {
            $status = $statement -> execute();
        }
        return $status;
    }

    //Function to delete a record
    public function delete($query, $values = ''){
        $status = false;
        $statement = $this -> pdo -> prepare($query);

        if(is_array($values)){
            $status = $statement -> execute($values);
        } else {
            $status = $statement -> execute();
        }
        return $status;
    }
}
?>