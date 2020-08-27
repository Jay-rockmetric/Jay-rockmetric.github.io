<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello Jay!");
    return $response;
});



$app->get('/student', function (Request $request, Response $response) {

    $sql = "SELECT * FROM  student";
    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->query($sql);
        $student = $stmt->fetchAll(PDO::FETCH_OBJ);
        $pdo = null;
        $response->getBody()->write("Data is : ". json_encode($student, JSON_PRETTY_PRINT));
        return $response;
    } catch (\PDOException $e) {
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});



$app->get('/student/{student_id}', function (Request $request, Response $response, array $args) {
    $student_id = $request->getAttribute('student_id');

    $sql = "SELECT * FROM student where student_id = $student_id";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->query($sql);
        $student = $stmt->fetchAll(PDO::FETCH_OBJ);
        $pdo = null;
        $response->getBody()->write("Data is : ". json_encode($student, JSON_PRETTY_PRINT));
        return $response;
    } catch (\PDOException $e) {
        $response->getBody()->write($e->getMessage());
        return $response;
    }
    
});


$app->post('/student/add', function (Request $request, Response $response, array $args) {
    $first_name = $request->getQueryParams()["first_name"];
    $last_name = $request->getQueryParams()["last_name"];
    $email_id = $request->getQueryParams()["email_id"];
    $contact_no = $request->getQueryParams()["contact_no"];
    $city = $request->getQueryParams()["city"];
    $state = $request->getQueryParams()["state"];

    try {
        $db = new db();
        $pdo = $db->connect();
        $sql = "INSERT INTO student (first_name, last_name, email_id, contact_no, city, state) VALUES (?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$first_name, $last_name, $email_id, $contact_no, $city, $state]);
        $response->getBody()->write("User : ". $first_name ." has been just added now");
        $pdo = null;
        return $response;
    } catch (\PDOException $e) {
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});



$app->put('/student/update/{student_id}', function (Request $request, Response $response, array $args) {
    $student_id = $request->getAttribute('student_id');

    $first_name = $request->getQueryParams()["first_name"];
    $last_name = $request->getQueryParams()["last_name"];
    $contact_no = $request->getQueryParams()["contact_no"];

    try {
        $db = new db();
        $pdo = $db->connect();
        $sql = "UPDATE student SET first_name =?, last_name=?, contact_no=? WHERE student_id=?";
        $pdo->prepare($sql)->execute([$first_name, $last_name, $contact_no, $student_id]);
        $response->getBody()->write("User : ". $first_name ." Updated Successfully");
        $pdo = null;
        return $response;
    } catch (\PDOException $e) {
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});


$app->delete('/student/delete/{student_id}', function (Request $request, Response $response, array $args) {
    $student_id = $request->getAttribute('student_id');

    try {
        $db = new db();
        $pdo = $db->connect();
        $sql = "DELETE FROM student WHERE student_id=?";
        $pdo->prepare($sql)->execute([$student_id]);
        $response->getBody()->write("User : ". $student_id ." Deleted Successfully");
        $pdo = null;
        return $response;

    } catch (\PDOException $e) {
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});
 


$app->run();