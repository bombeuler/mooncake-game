<?php

namespace mooncake_game\signin;

use conn\db;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: content-type");

require_once("conn.php");

$db = db::getInstance();
$data = json_decode(file_get_contents("php://input"))->values;

$db->signIn($data->nick, $data->password);