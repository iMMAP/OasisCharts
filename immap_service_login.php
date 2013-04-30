<?php

include_once 'immap_lib_dbconnect.php';
include_once 'immap_lib_utilities.php';

function login($db_name, $username, $password) {
    $tsql = "SELECT u.[id], u.[user], u.[Fname], u.[Lname], g.[Name] AS [GName] FROM Users AS u INNER JOIN [userGroups] AS g ON g.id = u.UserGroupID";
    #$tsql .= " WHERE u.[user] = ? and u.[pwd]=? AND (g.Name LIKE 'iMMAP');";
    $tsql .= " WHERE u.[user] = ? and u.[pwd]=?";
    $conn = get_connection($db_name);

    $stmt = sqlsrv_query($conn, $tsql, array($username, get_md5($password)));
    $isPass = FALSE;
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $isPass = TRUE;
        immap_session_start();
        $_SESSION['isLogin'] = TRUE;
        setcookie('Fname', $username, time() + (20 * 365 * 24 * 60 * 60), '/');
        setcookie('Lname', $username, time() + (20 * 365 * 24 * 60 * 60), '/');
        setcookie('UName', $username, time() + (20 * 365 * 24 * 60 * 60), '/');
        setcookie('DbName', $db_name, time() + (20 * 365 * 24 * 60 * 60), '/');
        setcookie('GName', $row['GName'], time() + (20 * 365 * 24 * 60 * 60), '/');
    }
    sqlsrv_close(conn);
    return $isPass;
}

function is_login() {
    immap_session_start();
    session_regenerate_id();
    if (isset($_SESSION['isLogin']) && (isset($_COOKIE['DbName'])) && (isset($_COOKIE['GName']))) {
        if ($_SESSION['isLogin'] === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    } else {
        return FALSE;
    }
    //return FALSE;
}

function logout() {
    immap_session_start();
    $_SESSION = array();
    session_destroy();
}

// End of File
