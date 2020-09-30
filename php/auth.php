<?php

namespace mooncake_game\signup;

use conn\db;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: content-type");

require_once("./conn.php");

$db = db::getInstance();

$data = json_decode(file_get_contents("php://input"));
$data = db::rsaDecrypt($data->values);
$data = json_decode($data);

$db->authen($data->name, $data->uid, $data->tel, $data->nick);
