<?php

session_start();
require_once('config.php');
require_once('functions.php');

define(TIME_STAMP , time());
define(RANDOM_STRING, md5(generateRandomString()));

function signature_generator($method, $sig_parm, $request_url)
{

    $method = strtoupper($method);

    $consumer_secret = CONSUMER_SECRET;
    $enc_consumer_secret = urlencode($consumer_secret);

    $enc_request_url = urlencode($request_url);

    $sig_text = array('oauth_nonce' => RANDOM_STRING,
                      'oauth_signature_method' => "HMAC-SHA1",
                      'oauth_timestamp' => TIME_STAMP,
                      'oauth_consumer_key' => CONSUMER_KEY,
                      'oauth_version' => '1.0' );


    if (isset($sig_parm)) {
        foreach ($sig_parm as $key => $value) {
                $sig_text[$key] = $value;
            }
    }

    foreach ($sig_text as $key => $value) {
        $enc_key = rawurlencode($key);
        $enc_value = rawurlencode($value);

        $new_sig_text[$enc_key] = $enc_value;
    }

    ksort($new_sig_text);

    foreach($new_sig_text as $key => $value){
        $pair[] = $key . '=' . $value;
    }

    $enc_sig_text = implode('&', $pair);
    $enc_sig_text = urlencode($enc_sig_text);

    $sig_base = $method . '&' . $enc_request_url . '&' . $enc_sig_text;

    if (isset($_SESSION['access_token']['oauth_token_secret'])) {
      $ots = $_SESSION['access_token']['oauth_token_secret'];
      $ots = urlencode($ots);
      $key = $enc_consumer_secret . '&' . $ots;
    }
    else {
      $key = $enc_consumer_secret . '&';
    }


    $sign_enc = base64_encode(hash_hmac('SHA1', $sig_base, $key, TRUE));

    return($sign_enc);
}

function get_request_token($sig_parm)
{

    $access_token_url = 'https://api.twitter.com/oauth/request_token';

    $sig_text = array('oauth_nonce' => RANDOM_STRING,
                      'oauth_signature_method' => "HMAC-SHA1",
                      'oauth_timestamp' => TIME_STAMP,
                      'oauth_consumer_key' => CONSUMER_KEY,
                      'oauth_version' => '1.0' );


    if(isset($sig_parm)){
        foreach ($sig_parm as $key => $value) {
            $sig_text[$key] = $value;
        }
    }

    ksort($sig_text);

    foreach ($sig_text as $key => $value) {
        $new_pair[] = $key . '="' . urlencode($value) . '"';
    }

    $get_header = implode(', ', $new_pair);

    $get_header = 'Authorization: OAuth ' . $get_header;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $access_token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($get_header));
    curl_setopt($ch, CURLOPT_TIMEOUT, '3');

    $result = curl_exec ($ch);

    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
        $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response['message'] = 'oAuth Error';
        $json = json_encode($response);

        return($json);
    }
    else {
      $response = explode('&', $result);

      foreach ($response as $key => $value) {
          $response_pair = explode('=', $value);
          $token[$response_pair[0]] = $response_pair[1];
      }

      $token['status'] = 200;
      return($token);
    }

}

function get_access_token($sig_parm, $verifier)
{
    $access_token_url = "https://api.twitter.com/oauth/access_token";

        $sig_text = array( 'oauth_nonce' => RANDOM_STRING,
                                    'oauth_signature_method' => "HMAC-SHA1",
                                    'oauth_timestamp' => TIME_STAMP,
                                    'oauth_consumer_key' => CONSUMER_KEY,
                                    'oauth_version' => '1.0' );


    if(isset($sig_parm)){
        foreach ($sig_parm as $key => $value) {
            $sig_text[$key] = $value;
        }
    }

    ksort($sig_text);

    foreach ($sig_text as $key => $value) {
        $new_pair[] = $key . '="' . urlencode($value) . '"';
    }

    $get_header = implode(', ', $new_pair);

    $get_header = 'Authorization: OAuth ' . $get_header;

    $post_data = array('oauth_verifier' => $verifier );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $access_token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($get_header));
    curl_setopt($ch, CURLOPT_TIMEOUT, '3');

    $result = curl_exec ($ch);

    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
        $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response['message'] = 'oAuth Error';
        $json = json_encode($response);

        return($json);
    }
    else{
        $response = explode('&', $result);

        foreach ($response as $key => $value) {
            $response_pair = explode('=', $value);
            $token[$response_pair[0]] = $response_pair[1];
        }
        $token['status'] = 200;
        return($token);
    }

    curl_close ($ch);
}

function make_request($method, $sig_parm, $url){

  $base_url = 'https://api.twitter.com/1.1/';

  $request_url = 'https://api.twitter.com/1.1/' . $url . '.json';

  if(isset($_SESSION['access_token'])){
    $ot = $_SESSION['access_token']['oauth_token'];

    $post_data = $sig_parm;

    $sig_parm['oauth_token'] = $ot;

    $sig_text = array('oauth_nonce' => RANDOM_STRING,
                      'oauth_signature_method' => "HMAC-SHA1",
                      'oauth_timestamp' => TIME_STAMP,
                      'oauth_consumer_key' => CONSUMER_KEY,
                      'oauth_version' => '1.0',
                      'oauth_token' => $ot);

    $signature = signature_generator($method, $sig_parm, $request_url);


    $sig_text['oauth_signature'] = $signature;
    ksort($sig_text);

     foreach ($sig_text as $key => $value) {
         $new_pair[] = $key . '="' . urlencode($value) . '"';
     }

     $get_header = implode(', ', $new_pair);

     $get_header = 'Authorization: OAuth ' . $get_header;

     $ch = curl_init();

     if($method === 'get' or $method === 'GET'){
        curl_setopt($ch, CURLOPT_URL, $request_url . '?' . http_build_query($post_data));
     }
     elseif($method === 'post' or $method === 'POST'){
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));  //Post Fields
        curl_setopt($ch, CURLOPT_POST, 1);
     }

     curl_setopt($ch, CURLOPT_TIMEOUT, '3');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array($get_header));

     $result = curl_exec ($ch);

     if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
         $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         $response['message'] = 'oAuth Error';
         $json = json_encode($response);

         return($json);
     }
     else {
      $response['status'] = 200;
      $response['message'] = $result;

      return($response);
     }

     curl_close ($ch);

  }
  else {
    $response['status'] = 400;
    $response['message'] = 'not authorized, please login using twitter and make the request';

    $json = json_encode($response);
    return($response);
  }
}
