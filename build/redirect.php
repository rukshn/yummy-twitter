<?php

session_start();

require_once('config.php');
require_once('functions.php');
require_once('oAuth.php');

$sig_new = array('oauth_callback' => CALLBACK_URL);

$signature = signature_generator('POST', $sig_new, 'https://api.twitter.com/oauth/request_token');

$sig_new['oauth_signature'] = $signature;

$token = get_request_token($sig_new);

if ($token['status'] == 200) {
  $_SESSION['request_token'] = $token;

  $_SESSION['oauth_token'] = $token['oauth_token'];

  $redirect_url = 'https://api.twitter.com/oauth/authorize?oauth_token=' . $token['oauth_token'];

  redirect($redirect_url);
}
else {
  print_r($token);
  session_destroy();
}

?>
