<?php

namespace mooncake_game\infor;

use conn\db;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: content-type");

require_once("conn.php");
$private = openssl_pkey_get_private(file_get_contents("D:/wamp64/www/mooncake/php/rsa_private_key.pem"));

$db = db::getInstance();
$data = json_decode(file_get_contents("php://input"));
$data = (openssl_private_decrypt(base64_decode($data->nick), $data, $private)) ? $data : null;


$db->myRank($data);