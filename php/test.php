<?php
$public = openssl_pkey_get_public(file_get_contents("D:/wamp64/www/mooncake/php/rsa_public_key.pem"));
$private = openssl_pkey_get_private(file_get_contents("D:/wamp64/www/mooncake/php/rsa_private_key.pem"));
$data = json_decode(file_get_contents("php://input"));
// $data = openssl_private_encrypt($data, $encrypted, $private) ? base64_encode($encrypted) : null;
// echo base64_encode($encrypted) . "\n";
$data = (openssl_private_decrypt(base64_decode("
Qb9OUjFVhQ26aSPSYMiG/p1EEtjCFb4jQNkn/NKDFp4mm2hNxAdKTOBNzevj0npVMsbx9340yL/l4Tt29l9Dw+mGb42DAhD7McJ3bbZeEuva005fcgJJ6f5RiWXCch8UyVWuUigzotbXa6Ywv10k8c3xRdrArU/7A+8Es50fuDQ=
"), $data, $private)) ? $data : null;
// $data = (openssl_private_decrypt(base64_decode($data->values), $data, $private)) ? $data : null;
echo $data;
// var_dump($encrypted);