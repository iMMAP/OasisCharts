<?php

include_once 'immap_service_chart.php';
header('Content-type: text/html; charset=utf-8');
if ($_POST) {
    if (isset($_POST['request'])) {
        $request = $_POST['request'];
        $db = isset($_POST['db']) ? $_POST['db'] : NULL;
        if ($request === 'get_dddname') {
            print get_dddname($db);
        } elseif ($request === 'get_query_name') {
            if (isset($_POST['dddname']) && (isset($_POST['reportgroup']))) {
                $dddname = "dd_{$_POST['dddname']}_ChartSettings";
                $reportgroup = $_POST['reportgroup'];
                if (strcmp($reportgroup, "  -- ALL --") === 0) {
                    print get_query_name($dddname,$db, null);
                } else {
                    print get_query_name($dddname,$db, $reportgroup);
                }
            }
        } elseif ($request === 'get_chart_report_group') {
            if (isset($_POST['dddname'])) {
                $dddname = "dd_{$_POST['dddname']}_ChartSettings";
                print get_chart_report_group($dddname,$db);
            }
        } elseif ($request === 'get_filter') {
            if (isset($_POST['dddname']) && (isset($_POST['guid1']))) { //&& (isset($_POST['isFilter']))) {
                $guid1 = $_POST['guid1'];
                $dddname = "dd_{$_POST['dddname']}_ChartSettings";
                print get_filter($dddname, $guid1,$db);
            }
        } elseif ($request === 'get_datagridview') {
            if (isset($_POST['dddname']) && (isset($_POST['guid1']))) { //&& (isset($_POST['isFilter']))) {
                $guid1 = $_POST['guid1'];
                $XxXxX = isset($_POST["XxXxX"]) ? $_POST["XxXxX"] : NULL;
                $dddname = "dd_{$_POST['dddname']}_ChartSettings";
                print get_datagridview($dddname, $guid1, $XxXxX,$db);
            }
        } elseif ($request === 'get_chart_config') {
            if (isset($_POST['dddname']) && (isset($_POST['guid1']))) { //&& (isset($_POST['isFilter']))) {
                $guid1 = $_POST['guid1'];
                $dddname = "dd_{$_POST['dddname']}_ChartSettings";
                $chartconfig = isset($_POST["chartconfig"]) ? $_POST["chartconfig"] : '';
                $width = $_POST['width'];
                $height = $_POST['height'];
                if (trim($chartconfig === '')) {
                    print get_chart_config($dddname, $guid1, $width, $height,'',$db);
                } else {
                    print get_chart_config($dddname, $guid1, $width, $height, $chartconfig,$db);
                }
            }
        } elseif ($request === 'set_chart_config') {
            if (isset($_POST['dddname']) && (isset($_POST['chartconfig'])) && (isset($_POST['guid1']))) {
                $dddname = "dd_{$_POST['dddname']}_ChartSettings";
                $chartconfig = $_POST['chartconfig'];
                $guid1 = $_POST['guid1'];
                $result = set_chart_config($dddname, $guid1, $chartconfig,$db);
                return $result;
            }
        } elseif ($request === 'is_hidden_leftbar') {
            print json_encode(is_hidden_leftbar());
        } elseif ($request === 'is_hidden_grid') {
            print json_encode(is_hidden_grid());
        } elseif ($request === 'is_hidden_all') {
            print json_encode(is_hidden_all());
        } elseif ($request === 'get_chartdd') {
            print json_encode(get_chartdd());
        } elseif ($request === 'get_chartguid') {
            print json_encode(get_chartguid());
        }
    }
}
?>
