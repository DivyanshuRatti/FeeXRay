<?php

//Make sure that the session is on so that we can store the token
function startSessionIfNeeded(){
    if(session_status()===PHP_SESSION_NONE){
        session_start();
    }
}

//Get the user's token. If it does not exist then make one.
function csrfToken(){
    startSessionIfNeeded();
    if(empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

//Print the hidden box that goes inside every form
function csrfField(){
    $token=csrfToken();
    echo "<input type='hidden' name='csrf_token' value='$token'>";
}

//check if the token is sent with the form. Stop if it is wrong
function verifyCsrf(){
    startSessionIfNeeded();
    $sent=$_POST['csrf_token']??'';
    $saved=$_SESSION['csrf_token']??'';
     if ($saved === '' || !hash_equals($saved, $sent)) {
        http_response_code(403);
        echo 'Your form expired or was not valid. Go back, refresh the page and try again.';
        exit;
    }
}