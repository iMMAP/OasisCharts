<?php

/*
  Plugin Name:  iMMAP plugin
  Plugin URI: http://www.immap.org
  Description:  iMMAP plugin
  version: 1.0
  Author: Immap Team
  Author: http://www.immap.org
 */
include_once(ABSPATH . "/wp-content/plugins/codelibs/func.php"); // just once
//CodeLibs('Ext-Core', 'latest');


global $wpdb;
add_action('init', 'oasis_init');

include_once dirname(__FILE__) . '/oasis-charting-admin.php';
if (class_exists("OasisChartAdmin")) {
    $cons_oca = new OasisChartAdmin();
}

if (isset($cons_oca)) {
    add_action('admin_menu', function () {
                global $cons_oca;
                if (isset($cons_oca)) {
                    $cons_oca->add_admin_page();
                }
            }, 1);
}

function oasis_init() {
    include_once dirname(__FILE__) . '/oasis-charting-shortcode.php';
    //  setup new instance of plugin
    if (class_exists("OasisChartShortcode")) {
        $cons_ocsc = new OasisChartShortcode();
    }

    if (isset($cons_ocsc)) {
        add_shortcode('oasis_charts', array(&$cons_ocsc, 'do_oasis_charts'), 1); // setup charting shortcode
    }
}

?>
