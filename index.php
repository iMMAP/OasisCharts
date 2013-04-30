<?php

include_once "immap_service_chart.php";
include_once "immap_service_login.php";

header('Content-type: text/html; charset=utf-8');
immap_session_start();
$_SESSION['is_hidden_leftbar'] = false;
$_SESSION['is_hidden_grid'] = false;
if (!empty($_GET)) {
    if ((!empty($_GET['ug'])) && (!empty($_GET['db']))) {

        $_SESSION['isLogin'] = TRUE;
        $parameter = "";
        if (!empty($_GET['rlhidden'])) {
            $_SESSION['is_hidden_leftbar'] = $_GET['rlhidden'];
            $parameter .= "?rlhidden={$_GET['rlhidden']}";
        }
        if (!empty($_GET['db'])) {
             $parameter .= strlen($parameter) == 0 ? "?db={$_GET['db']}" : "&db={$_GET['db']}";
        }
        if (!empty($_GET['gridhidden'])) {
            $parameter .= strlen($parameter) == 0 ? "?gridhidden={$_GET['gridhidden']}" : "&gridhidden={$_GET['gridhidden']}";
        }
        if (!empty($_GET['chartdd'])) {
            $parameter .= strlen($parameter) == 0 ? "?chartdd={$_GET['chartdd']}" : "&chartdd={$_GET['chartdd']}";
        } else {
            $_SESSION['chartdd'] = null;
        }
        if (!empty($_GET['chartguid'])) {
            $parameter .= strlen($parameter) == 0 ? "?chartguid={$_GET['chartguid']}" : "&chartguid={$_GET['chartguid']}";
        } else {
            $_SESSION['chartguid'] = null;
        }
        header("Location: immap_view_chart_wordpress.php$parameter");
    } else {
        header("Location: immap_view_login.php");
    }
} else {

    if (is_login() === FALSE) {
        header("Location: immap_view_login.php");
    } else {
        header("Location: immap_view_chart.php");
    }
}
?>
