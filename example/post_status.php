<?php

  session_start();

  require_once('../build/oAuth.php');
  require_once('../build/functions.php');

  if (isset($_SESSION['access_token'])) {
    $status = "Hacked Raspberry Pi turned into artificial pancreas http://www.businessinsider.com/hacked-raspberry-pi-artificial-pancreas-2015-8";

    $user_id = $_SESSION['access_token']['user_id'];

    $parameters = array('status' => $status);
    $request = make_request('POST', $parameters , 'statuses/update');
    print_r($request);
  }
  else {
    redirect('./build/reqirect.php');
  }
?>
