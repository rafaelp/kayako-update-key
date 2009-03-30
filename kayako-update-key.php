#!/usr/bin/php
<?php

/*
kayako-update-key v0.1
Rafael Lima (http://rafael.adm.br) at BielSystems (http://bielsystems.com.br) and Myfreecomm (http://myfreecomm.com.br)
http://github.com/rafaelp/kayako-update-key
License: MIT-LICENSE
*/

if(!(function_exists('curl_init'))) {
  die("Install CURL before running this script.\n");
}

if(!(@include dirname(__FILE__).'/config.php')) {
  die("Rename config.example.php and edit with your information.\n");
}

$ch = curl_init();

$post = array(
        'email'=> $config['email'],
        'password' => $config['password'],
        'islogin' => '1'
);

$kayako_uri = 'https://members.kayako.net/index.php';

curl_setopt($ch, CURLOPT_URL, $kayako_uri);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $config['cookie_path']);
curl_setopt($ch, CURLOPT_COOKIEFILE, $config['cookie_path']);
if(!curl_exec($ch)) {
  die("Could not connect to $kayako_uri\n");
}

$fp = fopen($config['key_path'], "w");
curl_setopt($ch, CURLOPT_URL, $kayako_uri."?_m=home&do=downloadkey&orderid=".$config['order_id']);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch,CURLOPT_POST,0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);

curl_close($ch);
fclose($fp);
if(file_exists($config['cookie_path'])) unlink($config['cookie_path']);

?>
