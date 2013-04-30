<?php

include_once "immap_lib_dbconnect.php";
include_once 'immap_lib_utilities.php';
include_once 'immap_model_header.php';
include_once 'immap_model_filed.php';
include_once 'immap_model_chart_series.php';
include_once 'immap_model_response.php';

header('Content-type: text/html; charset=utf-8');
define("COOKIE_DBNAME", "DbName");
define("MSSQL_COMMAND", "MSSQLCommand");
define("QUERY_NAME", "QueryName");
define("WEB_TAMPLATE", "webTemplate");
define("PIE_CHART", "pie");
define("COLUMN_CHART", "column");
define("BAR_CHART", "bar");
define("SPLINE_CHART", "spline");
define("TYPE_DATE", "date");
define("TYPE_INT", "int");
define("TYPE_FLOAT", "float");
define("TYPE_BOOLEAN", "boolean");
define("TYPE_STRING", "string");

function get_dddcombobox($dbName, $GName) {
    $tsql = "SELECT DDDefName,[Description] AS [DDDefDesc] ,EnableReporting FROM {$GName}DynamicDataDefs WHERE EnableReporting=1";
    $conn = get_connection($dbName);
    $stmt = sqlsrv_query($conn, $tsql);
    $selection = "<select name='ddName' id='ddName'>";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $selection .= "<option value='" . getDDFormat($row['DDDefName'], 'ChartSettings') . "'>" . $row["DDDefName"] . "</option> ";
    }
    $selection.='</select>';
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    return $selection;
}

function get_dddname($xDbName = NULL) {
    immap_session_start();
    if ($xDbName == NULL) {
        $xDbName = $_COOKIE[COOKIE_DBNAME];
    }
    $GName = $_COOKIE['GName'];
    $tsql = "SELECT DDDefName AS [DDDefName],[Description] AS [Name] FROM {$GName}DynamicDataDefs WHERE EnableReporting=1";
    $tsql .= (empty($_SESSION['chartdd']) === false) ? " AND DDDefName = ?" : "";
    $chartdd = isset($_SESSION['chartdd']) ? $_SESSION['chartdd'] : NULL ;
    $_SESSION['chartdd'] = null;
    return get_json_from_sql($tsql, $xDbName, array($chartdd));
}

function get_query_name($ddd_name, $xDbName = NULL, $group = null) {
    $tsql = "SELECT GUID1,QueryName,(CASE WHEN [FilterMSSQL] IS NULL THEN 0  WHEN DATALENGTH([FilterMSSQL]) = 0 THEN 0 ELSE  1 END) AS [IsFilter] FROM {$ddd_name}";
    $tsql .= " WHERE UseChart=1 AND (DATALENGTH (MSSQLCommand)>0 OR MSSQLCommand IS NOT NULL)";
    if ($xDbName == NULL) {
        $xDbName = $_COOKIE[COOKIE_DBNAME];
    }
    if ($group !== null) {
        $tsql .= " AND [Group]=?";
    }
    return get_json_from_sql($tsql, $xDbName, array($group));
}

function get_chart_report_group($ddd_name, $xDbName = NULL) {
    $res = new ImmapModelResponse();
    immap_session_start();
    try {
        $tsql = "SELECT DISTINCT [Group] FROM {$ddd_name} WHERE LEN([Group]) > 0";
        if ($xDbName == NULL) {
            $xDbName = $_COOKIE[COOKIE_DBNAME];
        }
        $dbName = $xDbName;
        $conn = get_connection($dbName);
        $stmt = sqlsrv_query($conn, $tsql);
        $rows = array();
        $rows[]["reportgroup"] = "  -- ALL --";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[]["reportgroup"] = reset($row);
        }
        $res->success = true;
        $res->message = "Loaded data";
        $res->total = count($rows);
        $res->data = $rows;
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    } catch (Exception $e) {
        
    }
    return $res->to_json();
}

function get_chart_setting_from_json($chartTitle, $xField, $chartType, $params, $series) {
    $chartConfig = Array();
    $xAxisLabelAngle = isset($params['xAxisLabelAngle']) ? $params['xAxisLabelAngle'] : 0;
    $yAxisLabelAngle = isset($params['yAxisLabelAngle']) ? $params['yAxisLabelAngle'] : 0;

    $xAxisLabelTitle = isset($params['xAxisLabelTitle']) ? $params['xAxisLabelTitle'] : '';
    $yAxisLabelTitle = isset($params['yAxisLabelTitle']) ? $params['yAxisLabelTitle'] : '';

    $stacking = isset($params['chartStack']) ? $params['chartStack'] : "false";
    $chartTitle = isset($params["chartTitle"]) ? $params["chartTitle"] : $chartTitle;
    $subtitleText = isset($params["subtitleText"]) ? $params["subtitleText"] : '';
    $linkText = isset($params["linkText"]) ? $params["linkText"] : '';
    $href = isset($params["href"]) ? $params["href"] : '';
    $legend = isset($params["legend"]) ? $params["legend"] : '';
    $chartConfig['dbSetting']['chartType'] = $chartType;
    $chartConfig['dbSetting']['legend'] = $legend;
    $chartConfig['dbSetting']['stacking'] = $stacking;
    $chartConfig['dbSetting']['xAxisLabelAngle'] = $xAxisLabelAngle;
    $chartConfig['dbSetting']['yAxisLabelAngle'] = $yAxisLabelAngle;
    if ($legend === "right") {
        $chartConfig['dbSetting']['legendIndex'] = 0;
        $chartConfig['chartConfig']['legend']['layout'] = 'vertical';
        $chartConfig['chartConfig']['legend']['align'] = 'right';
        $chartConfig['chartConfig']['legend']['verticalAlign'] = 'top';
        $chartConfig['chartConfig']['legend']['x'] = -10;
        $chartConfig['chartConfig']['legend']['y'] = 20;
    } elseif ($legend === "left") {
        $chartConfig['dbSetting']['legendIndex'] = 1;
        $chartConfig['chartConfig']['legend']['layout'] = 'vertical';
        $chartConfig['chartConfig']['legend']['align'] = 'left';
        $chartConfig['chartConfig']['legend']['verticalAlign'] = 'top';
        $chartConfig['chartConfig']['legend']['x'] = 0;
        $chartConfig['chartConfig']['legend']['y'] = 20;
    } elseif ($legend === "top") {
        $chartConfig['dbSetting']['legendIndex'] = 2;
        $chartConfig['chartConfig']['legend']['layout'] = 'horizontal';
        $chartConfig['chartConfig']['legend']['align'] = 'center';
        $chartConfig['chartConfig']['legend']['verticalAlign'] = 'top';
        $chartConfig['chartConfig']['legend']['y'] = 40;
    } elseif ($legend === "buttom") {
        $chartConfig['dbSetting']['legendIndex'] = 3;
        $chartConfig['chartConfig']['legend']['layout'] = 'horizontal';
        $chartConfig['chartConfig']['legend']['align'] = 'center';
        $chartConfig['chartConfig']['legend']['verticalAlign'] = 'bottom';
    }

    $chartConfig['series'] = $series;
    $chartConfig['xField'] = $xField;
    $chartConfig['animation'] = false;
    $chartConfig['store'] = '';
    $chartConfig['chartConfig']['chart']['zoomType'] = 120;
    $chartConfig['chartConfig']['title']['text'] = $chartTitle;
    $chartConfig['chartConfig']['title']['x'] = -20; //center
    $chartConfig['chartConfig']['subtitle']['text'] = $subtitleText;
    $chartConfig['chartConfig']['subtitle']['x'] = -20; //center
    $chartConfig['chartConfig']['xAxis'] = array('title' => array('text' => $xAxisLabelTitle, 'margin' => 0, 'rotation' => 0),
        'labels' => array('rotation' => $xAxisLabelAngle));
    $chartConfig['chartConfig']['yAxis'] = array('title' => array('text' => $yAxisLabelTitle, 'margin' => 0, 'rotation' => 0),
        'labels' => array('rotation' => $yAxisLabelAngle),
        'plotLines' => array(array('value' => 0, 'width' => 1, 'color' => '#808080')));
    if ($stacking === "true") {
        if ($chartType === COLUMN_CHART) {
            $chartConfig['chartConfig']['plotOptions']['column']['stacking'] = 'normal';
        } elseif ($chartType === BAR_CHART) {
            $chartConfig['chartConfig']['plotOptions']['series']['stacking'] = 'normal';
        }
    }
    if ($chartType === PIE_CHART) {
        $chartConfig['chartConfig']['plotOptions']['pie']['allowPointSelect'] = 'true';
        $chartConfig['chartConfig']['plotOptions']['pie']['size'] = 200;
    }
    $chartConfig['chartConfig']['tooltip']['formatter'] = "";
    $chartConfig['chartConfig']['legend']['borderWidth'] = 1;
    $chartConfig['chartConfig']['credits']['text'] = $linkText;
    $chartConfig['chartConfig']['credits']['href'] = $href;
    return $chartConfig;
}

function get_chart_config($ddd_name, $guid, $width, $height, $jsonconfig = '', $xDbName = NULL) {
    $mssqlcommand = MSSQL_COMMAND;
    $queryname = QUERY_NAME;
    $webTemplate = WEB_TAMPLATE;
    if ($xDbName == NULL) {
        $xDbName = $_COOKIE[COOKIE_DBNAME];
    }
    $dbName = $xDbName;
    $tsql = "SELECT COUNT(object_id) as CNT from sys.columns where LTRIM(RTRIM(UPPER(Name))) = LTRIM(RTRIM(UPPER(N'{$webTemplate}'))) AND Object_ID = Object_ID(N'{$ddd_name}')";
    $resultsets = execute_query($dbName, $tsql);
    $exits = reset($resultsets);
    if ($exits['CNT'] == 0) {
        $tsql = "ALTER TABLE {$ddd_name} ADD {$webTemplate} NTEXT NULL ;";
        execute_nonequery($dbName, $tsql);
    }
    $tsql = "SELECT TOP 1 {$webTemplate} FROM {$ddd_name} WHERE GUID1=?";
    $resultsets = execute_query($dbName, $tsql, array($guid));
    $rowcommand = reset($resultsets);
    if ($jsonconfig === '') {
        If (mb_strlen($rowcommand[$webTemplate], "UTF-8") > 0) {
            $params = json_decode($rowcommand[$webTemplate], true);
        } else {
            $params = array();
        }
    } else {
        $params = json_decode($jsonconfig, true);
    }
    $tsql = "SELECT TOP 1 MSSQLCommand,QueryName FROM {$ddd_name} WHERE GUID1=?";
    $resultsets = execute_query($dbName, $tsql, array($guid));
    $rowcommand = reset($resultsets);
    $chartTitle = $rowcommand[$queryname];
    $chartConfig = array();
    $XxXxX = "-- ALL --";
    If (mb_strlen($rowcommand[$mssqlcommand], "UTF-8") > 0) {
        $tmpSql = $rowcommand[$mssqlcommand];
        $tmpSql = str_replace("XxXxXx", str_replace("'", "''", $XxXxX), $tmpSql);
        $tmpSql = str_replace("= '  -- ALL --'", "<> '  -- ALL --'", $tmpSql);
        $tmpSql = str_replace("='  -- ALL --'", "<> '  -- ALL --'", $tmpSql);
        $tsql = $tmpSql;
        $conn = get_connection($dbName);
        $stmt = sqlsrv_query($conn, $tsql);
        $cnt = 0;
        $imcs = null;
        $series = array();
        $xField = '';
        $chartType = isset($params["chartType"]) ? $params["chartType"] : 'column';
        //$k1 = (220*$width)/1126;
        //$k2 = (120*$height)/371;
        foreach (sqlsrv_field_metadata($stmt) as $fieldMetadata) {
            foreach ($fieldMetadata as $name => $value) {
                if ($name === "Name") {
                    if ($cnt === 0) {
                        $xField = $value;
                    } else { //($cnt !== 0) 
                        $imcs = new ImmapModelChartSeries();
                        if ($chartType === PIE_CHART) {
                            $imcs->type = PIE_CHART;
                            $imcs->categorieField = $xField;
                            $imcs->dataField = $value;
                            $imcs->name = $value;
                            //array_push($series, $imcs->getSeriesPie($k1 + "%", $k2 + "%"));
                            array_push($series, $imcs->getSeriesPie());
                            //$k1 +=600;
                            //$k2 +=0;
                            break;
                        } else {
                            $imcs->visible = true;
                            $imcs->dataIndex = $value;
                            $imcs->name = $value;
                            switch ($chartType) {
                                case BAR_CHART :
                                    array_push($series, $imcs->getSeriesBar());
                                    break;
                                case SPLINE_CHART :
                                    array_push($series, $imcs->getSeriesSpline());
                                    break;
                                case COLUMN_CHART :
                                default :
                                    array_push($series, $imcs->getSeriesColumn());
                            }
                        }
                    } //end else ($cnt !== 0) 
                    $cnt++;
                }
            }
        }
        $chartConfig = get_chart_setting_from_json($chartTitle, $xField, $chartType, $params, $series);
    }
    return json_encode($chartConfig);
}

function set_chart_config($ddd_name, $guid, $jsonconfig, $xDbName = NULL) {
    if ($xDbName == NULL) {
        $xDbName = $_COOKIE[COOKIE_DBNAME];
    }
    $dbName = $xDbName;
    $tsql = "UPDATE {$ddd_name} SET WebTemplate=? WHERE GUID1=?";
    $result = execute_nonequery($dbName, $tsql, array($jsonconfig, $guid));
    return $result;
}

function get_datagridview($ddd_name, $guid, $XxXxX = NULL, $xDbName = NULL) {
    if ($xDbName == NULL) {
        $xDbName = $_COOKIE[COOKIE_DBNAME];
    }
    $dbName = $xDbName;
    $tsql = "SELECT TOP 1 MSSQLCommand FROM {$ddd_name} WHERE GUID1=?";
    $resultsets = execute_query($dbName, $tsql, array($guid));
    $rowcommand = reset($resultsets);
    If (mb_strlen($rowcommand[MSSQL_COMMAND], "UTF-8") > 0) {
        $tmpSql = $rowcommand[MSSQL_COMMAND];
        $allfilter = "-- ALL --";
        if (mb_strlen($XxXxX, "UTF-8") > 0) {
            $tmpSql = str_replace("XxXxXx", str_replace("'", "''", $XxXxX), $tmpSql);
            if (trim($XxXxX) === trim($allfilter)) {
                $tmpSql = str_replace("= '  -- ALL --'", "<> '  -- ALL --'", $tmpSql);
                $tmpSql = str_replace("='  -- ALL --'", "<> '  -- ALL --'", $tmpSql);
            }
            $tsql = $tmpSql;
        } else {
            $tsql = $tmpSql;
        }
    }
    return get_json_from_sql($tsql, $xDbName, NULL, TRUE);
}

function get_filter($ddd_name, $guid, $xDbName = NULL) {
    $res = new ImmapModelResponse();
    immap_session_start();
    try {
        $filter = "FilterMSSQL";
        $tsql = "SELECT TOP 1 FilterMSSQL FROM {$ddd_name} WHERE GUID1=?";
        if ($xDbName == NULL) {
            $xDbName = $_COOKIE[COOKIE_DBNAME];
        }
        $dbName = $xDbName;
        $resultsets = execute_query($dbName, $tsql, array($guid));
        $rowcommand = reset($resultsets);
        $conn = get_connection($dbName);
        $stmt = sqlsrv_query($conn, $rowcommand[$filter]);
        $rows = array();
        $rows[]["filter"] = "  -- ALL --";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
            reset($row);
            $rows[]["filter"] = $row[0];
        }
        $res->success = true;
        $res->message = "Loaded data";
        $res->total = count($rows);
        $res->data = $rows;
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    } catch (Exception $e) {
        
    }
    return $res->to_json();
}

function get_json_from_sql($tsql, $xDbName, $parameters = NULL, $requireMetadata = FALSE) {
    immap_session_start();
    $res = new ImmapModelResponse();
    try {
        $dbName = $xDbName;
        $conn = get_connection($dbName);
        $rows = array();
        if ($parameters === NULL) {
            $stmt = sqlsrv_query($conn, $tsql);
        } else {
            $stmt = sqlsrv_query($conn, $tsql, $parameters);
        }
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            array_push($rows, $row);
        }
        $total = count($rows);
        if ($total === 0) {
            $res->success = false;
        } else {
            $res->success = true;
        }
        $res->message = "Loaded data";
        $res->total = $total;
        $res->data = $rows;

        if ($requireMetadata === TRUE) {
            $metaData = ARRAY();
            $metaData['totalProperty'] = 'total';
            $metaData['successProperty'] = 'success';
            $metaData['root'] = 'data';
            $fileds = ARRAY();
            $columns = ARRAY();
            $cnt = 0;
            foreach (sqlsrv_field_metadata($stmt) as $fieldMetadata) {
                foreach ($fieldMetadata as $name => $value) {
                    if ($name === "Type") {
                        $cnt = 2;
                        if (($value === 91) || ($value === 93)) {
                            $fm->type = TYPE_DATE;
                        } elseif (($value === 2) || ($value === 3) || ($value === 7) || ($value === 6)) {
                            $fm->type = TYPE_FLOAT;
                        } elseif (($value === 5) || ($value === 4)) {
                            $fm->type = TYPE_INT;
                        } elseif ($value === -7) {
                            $fm->type = TYPE_BOOLEAN;
                        } else {
                            $fm->type = TYPE_STRING;
                        }
                    } elseif ($name === "Name") {
                        $fm = new ImmapModelField();
                        $fm->name = $value;
                        $hm = new ImmapModelHeader();
                        $hm->header = $value;
                        $hm->dataIndex = $value;
                        $cnt = 1;
                    }
                    if ($cnt === 2) {
                        array_push($fileds, $fm);
                        array_push($columns, $hm);
                        $cnt = 0;
                    }
                }
            }
            $metaData['fields'] = $fileds;
        }
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    } catch (Exception $e) {
        
    }
    if ($requireMetadata === TRUE) {
        return $res->to_json($metaData, $columns);
    } else {
        return $res->to_json();
    }
}

function is_hidden_leftbar() {
    immap_session_start();
    $is_hidden = $_SESSION['is_hidden_leftbar'];
    return array('is_hidden_leftbar' => $is_hidden);
}

function is_hidden_grid() {
    immap_session_start();
    $is_hidden = $_SESSION['is_hidden_grid'];
    return array('is_hidden_grid' => $is_hidden);
}

function is_hidden_all() {
    immap_session_start();
    $is_hidden_grid = $_SESSION['is_hidden_grid'];
    $is_hidden_leftbar = $_SESSION['is_hidden_leftbar'];
    return array("grid" => $is_hidden_grid, "leftbar" => $is_hidden_leftbar);
}

function get_chartdd() {
    immap_session_start();
    $chartdd = (empty($_SESSION['chartdd']) === false) ? $_SESSION['chartdd'] : "";
    $_SESSION['chartdd'] = null;
    return array('chartdd' => $chartdd);
}

function get_chartguid() {
    immap_session_start();
    $chartguid = (empty($_SESSION['chartguid']) === false) ? $_SESSION['chartguid'] : "";
    $_SESSION['chartguid'] = null;
    return array('chartguid' => $chartguid);
}

?>
