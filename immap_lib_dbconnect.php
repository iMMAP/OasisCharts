<?php
header('Content-type: text/html; charset=utf-8');
function get_connection($database = "OasisDB-Atlantis") {
    $serverName ="your server here=";
    $connectionInfo = array("Database" => $database, "UID" => "usernamehere", "PWD" => "passwordhere");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    return $conn;
}

function get_database_list() {
    $conn = get_connection();
    $tsql = "SELECT [name] FROM sys.sysdatabases WHERE name LIKE 'OasisDB-%';";
    $stmt = sqlsrv_query($conn, $tsql);
    $dbList = "<select name='dbname' id='dbname'>";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $name = $row["name"];
        if ((isset($_COOKIE['DbName'])) && (strcmp($name, $_COOKIE["DbName"]) === 0)) {
            $dbList .= "<option selected='selected' value='" . $name . "'>" . $name . "</option> ";
        } else {
            $dbList .= "<option value='" . $name . "'>" . $name . "</option> ";
        }
    }
    $dbList.='</select>';
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    return $dbList;
}

function execute_query($db_name, $tsql, $parameters = NULL) {
    $conn = get_connection($db_name);
    if ($parameters === NULL) {
        $stmt = sqlsrv_query($conn, $tsql);
    } else {
        $stmt = sqlsrv_query($conn, $tsql, $parameters);
    }
    if (!$stmt) {
        echo "Execute query failed.\n";
        die(print_r(sqlsrv_errors(), true));
    }
    $rows = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($rows, $row);
    }
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    return $rows;
}

function execute_nonequery($db_name, $tsql, $parameters = NULL) {
    $conn = get_connection($db_name);
    $result = TRUE;
    if ($parameters === NULL) {
        $stmt = sqlsrv_query($conn, $tsql);
    } else {
        $stmt = sqlsrv_query($conn, $tsql, $parameters);
    }
    if (!$stmt) {
        echo "Execute nonequery failed.\n";
        die(print_r(sqlsrv_errors(), TRUE));
        $result = FALSE;
    }
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    return $result;
}

?>