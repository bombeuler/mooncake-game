<?php

namespace mooncake_game\signup;

use conn\db;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: content-type");

require_once("conn.php");

$db = db::getInstance();

$data = file_get_contents("php://input");
$data = json_decode($data)->values;
$db->signUp($data->uid, $data->name, $data->tel, $data->nick, $data->password);
