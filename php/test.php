<?php
$public = openssl_pkey_get_public(file_get_contents("D:/wamp64/www/mooncake/php/rsa_public_key.pem"));
$private = openssl_pkey_get_private(file_get_contents("D:/wamp64/www/mooncake/php/rsa_private_key.pem"));
$data = json_decode(file_get_contents("php://input"));
// $data = openssl_private_encrypt($data, $encrypted, $private) ? base64_encode($encrypted) : null;
// echo base64_encode($encrypted) . "\n";
$data = (openssl_private_decrypt(base64_decode("
hCPOv4UShEGVn/cekbPqd7LQRNGeR4K7TBHTIe+AE9dQYGgjPsvsd6Fsm1PSAjpEzSpDlLAPD9owNwD96cEOLqhMQcXfh/IKCkg2vx2F+3EmZ4ebPydKSbC/cpqxJ1gndIApcicQTK/lWdPHsAA+jE6xav+bzw7w2LEwgNKuolY=
"), $data, $private)) ? $data : null;
// $data = (openssl_private_decrypt(base64_decode($data->values), $data, $private)) ? $data : null;
echo $data;
// var_dump($encrypted);