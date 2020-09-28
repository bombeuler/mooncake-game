<?php

namespace mooncake_game\signin;

use conn\db;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: content-type");

require_once("./conn.php");
$private = openssl_pkey_get_private(file_get_contents("D:/wamp64/www/mooncake/php/rsa_private_key.pem"));

$db = db::getInstance();
$data = json_decode(file_get_contents("php://input"));
$data = (openssl_private_decrypt(base64_decode($data->values), $data, $private)) ? $data : null;
$data = json_decode($data);

$db->signIn($data->nick, $data->password);