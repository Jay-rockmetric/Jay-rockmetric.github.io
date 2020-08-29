<?php
use phpseclib\Net\SFTP;
$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['sftp'] = function ($c) {
    $sftp_info = $c['settings']['sftp'];
    $sftp = new SFTP($sftp_info['host'], $sftp_info['port']);

    if (!$sftp->login($sftp_info['user'], $sftp_info['password'])) {
        throw new Exception("Login failed for SFTP");
    }
    return $sftp;
};