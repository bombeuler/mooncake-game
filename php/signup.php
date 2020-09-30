<?php

namespace mooncake_game\signup;

use conn\db;

require_once("./config.php");
require_once("./conn.php");

$db = db::getInstance();

$data = json_decode(file_get_contents("php://input"));
$data = $db::rsaDecrypt($data->values);
$data = json_decode($data);

$db->signUp($data->nick, $data->password);
