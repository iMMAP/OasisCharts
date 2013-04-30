<?php

include_once dirname(__FILE__) . '/immap_lib_dbconnect.php';
include_once dirname(__FILE__) . '/oasis-charting-admin.php';

if (!class_exists('OasisChartShortcode')) {

    class OasisChartShortcode {

        public $cons_oca = NULL;

        public function __construct() {
            
        }

        public function do_oasis_charts($atts) {
            extract(shortcode_atts(array(
                        'db' => '',
                        'ug' => '',
                        'gridhidden' => "false",
                        'rlhidden' => "false",
                        'chartdd' => '',
                        'chartguid' => ''
                            ), $atts));
            if (isset($_POST['submit_oasis_charting'])) {
                $db = $_POST['dbname'];
            }
            if (class_exists("OasisChartAdmin")) {
                $cons_oca = new OasisChartAdmin();
                $cons_oca->get_option();
            }
            if (!empty($cons_oca->options)) {
                $key = $cons_oca->options['advance_iframe_security_key'];
                $url = "{$cons_oca->options['oasis_chart_url']}?db={$db}&ug={$ug}&gridhidden={$gridhidden}&rlhidden={$rlhidden}";
                $url .= "&chartdd={$chartdd}&chartguid={$chartguid}";
                $output = $output . "<div id=\"oasis_charting\">" . do_shortcode("[advanced_iframe securitykey = \"{$key}\" src=\"{$url}\"]"); //. "</div>";
            }
            return $output;
        }

    }

}
?>
