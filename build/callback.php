<?php
    session_start();

    require_once('config.php');
    require_once('oAuth.php');
    require_once('functions.php');

    if (isset($_SESSION['oauth_token'])) {
        $oauth_token = $_SESSION['oauth_token'];

        if(isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier'])){
            $request_oauth_token = $_REQUEST['oauth_token'];
            $request_oauth_verifier = $_REQUEST['oauth_verifier'];

            if ($oauth_token == $request_oauth_token) {
                $sig_new['oauth_token'] = $oauth_token;
                $signature = signature_generator('POST', $sig_new, 'https://api.twitter.com/oauth/access_token');
                $sig_new['oauth_signature'] = $signature;

                $access_token = get_access_token($sig_new, $request_oauth_verifier);

                if ($access_token['status'] == 200) {
                    $_SESSION['access_token'] = $access_token;
                    redirect(APP_URL);
                }
                else{
                    print_r($access_token);
                    session_destroy();
                }

            }
            else{
                session_destroy();
                redirect(APP_URL);
            }
        }
        else{
            session_destroy();
            redirect(APP_URL);
        }
    }
    else{
        session_destroy();
        redirect(APP_URL);
    }
?>
