<?php

include_once "immap_service_login.php";
header('Content-type: text/html; charset=utf-8');

if ($_POST) {
    //if (isset($_POST['password']) && isset($_POST['username']) && isset($_POST['dbname'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];
    $dbName = $_POST['dbname'];
    if (login($dbName, $username, $password) === FALSE) {
        #header("Location: chartingapp/login.php?q=false");
        header("Location: ./immap_view_login.php?q=false");
    } else {
        header("Location: ./immap_view_chart.php");
    }
    //}
}
if ($_GET) {
    $request = isset($_GET["request"]) ? $_GET["request"] : '';
    if (strcmp($request, logout())) {
        logout();
        header("Location: ./immap_view_login.php");
    }
}
?>