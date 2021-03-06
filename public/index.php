<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__.'/../config/setting.php';

spl_autoload_register(function ($classname) {
    require ("../classes/" . $classname . ".php");
});

$app = new \Slim\App(['settings' => $config]);

require __DIR__.'/../config/container.php';

$app->get('/', function (Request $request, Response $response) {
    $msg = array('statusCode' => '0', 'statusInformation' => 'Hello, Jay');
    return $response->withHeader("Content-Type", 'application/json')->withJson($msg);
});

$app->get('/student', function (Request $request, Response $response) {
    $api = new Api();
    $data = $api->getAllStudents($this->db);
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

$app->post('/filesharing', function (Request $request, Response $response) {
    $file_data = $_FILES['file'];
    $paramData = $request->getParsedBody();
    $sftp_class = new SFTP();
    $data = $sftp_class->uploadFile($this->sftp, $file_data, $paramData);
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});

$app->get('/filelist', function (Request $request, Response $response) {
    $sftp_class = new SFTP();
    $data = $sftp_class->getAllFiles($this->sftp); 
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});

$app->post('/getfiles', function (Request $request, Response $response) {
    $paramData = $request->getParsedBody();
    $sftp_class = new SFTP();
    $data = $sftp_class->downloadFiles($this->sftp, $paramData); 
    return $response->withHeader("Content-Type", 'application/json')->withJson($data);
});

$app->run();