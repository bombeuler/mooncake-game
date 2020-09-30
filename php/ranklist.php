<?php

namespace mooncake_game\ranklist;

use conn\db;

require_once("./config.php");
require_once("conn.php");

$db = db::getInstance();

$data = json_decode(file_get_contents("php://input"));
$data = $db::rsaDecrypt($data->values);
$data = json_decode($data);

$num = isset($data->num)?$data->num:20;

$db->getRankList($data->begin, $num);
