<?php

namespace mooncake_game\infor;

use conn\db;

require_once("./config.php");
require_once("conn.php");

$db = db::getInstance();
$data = json_decode(file_get_contents("php://input"));
$data = $db::rsaDecrypt($data->nick);


$db->myRank($data);
