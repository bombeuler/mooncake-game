<?php
header("Access-Control-Allow-Origin: *"); 
for($i=0;$i<20;$i++){
    $res[]=['name' => "cyc".$i, 'score' => mt_rand(0,100)];
}
echo json_encode($res);
