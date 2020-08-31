<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');


require __DIR__ . '/../vendor/autoload.php';
require __DIR__.'/../config/setting.php';
require __DIR__.'/../classes/Api.php';

// spl_autoload_register(function ($classname) {
//     require ("../classes/" . $classname . ".php");
// });

$app = new \Slim\App(['settings' => $config]);

require __DIR__.'/../config/container.php';



$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello Jay!");
    return $response;
});

$app->get('/student', function (Request $request, Response $response) {

    $api = new Api();
    $data = $api->getAllStudents($this->db);
    $file_sftp = $api->newSftpOperation($this->sftp);
    print_r($file_sftp);
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});



$app->get('/student/{student_id}', function (Request $request, Response $response) {
    $student_id = $request->getAttribute('student_id');
    $api = new Api();
    $data = $api->getSingleStudent($this->db, $student_id);
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});


$app->post('/student/add', function (Request $request, Response $response) {
    $student_data = $request->getParsedBody();
    $api = new Api();
    $data = $api->addNewStudent($this->db, $student_data);
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});



$app->post('/student/update/{student_id}', function (Request $request, Response $response) {
    $student_id = $request->getAttribute('student_id');
    $student_data = $request->getParsedBody();
    $api = new Api();
    $data = $api->updateStudent($this->db, $student_data, $student_id);
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});


$app->post('/student/delete/{student_id}', function (Request $request, Response $response) {
    $student_id = $request->getAttribute('student_id');
    $api = new Api();
    $data = $api->removeStudent($this->db, $student_id);
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});

$app->run();