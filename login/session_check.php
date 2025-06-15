<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    
    // User is authenticated, trow 200(evertying is good and god is in heaven)
    http_response_code(200);
    exit;

} else {

    // User is not authenticated, trow 401(unathorized)
    http_response_code(401); 
    exit;
}

?>