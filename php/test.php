<?php
$public=openssl_pkey_get_public(file_get_contents("D:/wamp64/www/mooncake-game/php/rsa_public_key.pem"));
$private=openssl_pkey_get_private(file_get_contents("D:/wamp64/www/mooncake-game/php/rsa_private_key.pem"));
$data="33";
$data=openssl_private_encrypt($data,$encrypted,$private) ? base64_encode($encrypted) : null;
echo $data."\n";
$data=(openssl_public_decrypt(base64_decode($data), $data, $public)) ? $data : null;
echo $data;
// var_dump($encrypted);