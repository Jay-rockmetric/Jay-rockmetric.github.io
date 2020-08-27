<?php
    function connect()
    {
        $host = "localhost";
        $user_name = "root";
        $password = "";
        $dbname = "student_data";

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user_name, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
        
    }
    $pdo = connect();
?>