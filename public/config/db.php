<?php
// $settings['db'] = [
//     'driver' => 'mysql',
//     'host' => 'localhost',
//     'username' => 'root',
//     'database' => 'test',
//     'password' => '',
//     'flags' => [
//         // Turn off persistent connections
//         PDO::ATTR_PERSISTENT => false,
//         // Enable exceptions
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         // Emulate prepared statements
//         PDO::ATTR_EMULATE_PREPARES => true,
//         // Set default fetch mode to array
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         // Set character set
//         PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
//     ],
// ];
class db
{

    public function connect()
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
}