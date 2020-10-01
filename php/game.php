<?php

namespace mooncake_game\game;

use conn\db;
require_once("config.php");
require_once("conn.php");

$db = db::getInstance();
$data = json_decode(file_get_contents("php://input"));
$data = $db::rsaDecrypt($data->values);
$data = json_decode($data);

$db->updateGame($data->nick, $data->score, $data->time);
