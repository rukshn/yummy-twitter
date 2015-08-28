<?php

  session_start();

  require_once('../build/oAuth.php');
  require_once('../build/functions.php');

  if (isset($_SESSION['access_token'])) {
    $status = "Maybe he'll finally find his keys. #peterfalk";
    $parameters = array('ststus' => $status, );

    $request = make_request('post', $parameters ,'statuses/update');
    print_r($request);
  }
  else {
    redirect('./build/reqirect.php');
  }
?>
