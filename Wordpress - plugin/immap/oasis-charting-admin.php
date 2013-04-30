<?php

if (!class_exists('OasisChartAdmin')) {

    class OasisChartAdmin {

        const OASIS_CHART_SETTING_NAME = "OasisChartAdmin";

        public $options;

        public function __construct() {
            $this->get_option();
        }

        public function add_admin_page() {
            add_options_page('Oasis Chart', 'Oasis Chart', 'manage_options', basename(__FILE__), array(&$this, 'print_admin_page'));
        }

        public function print_admin_page() {
            require_once('oasis-charting-admin-page.php');
        }

        public function get_option() {
            $devOptions = get_option(OASIS_CHART_SETTING_NAME);
            if (!empty($devOptions)) {
                foreach ($devOptions as $key => $option)
                    $this->options[$key] = $option;
            }
            update_option(OASIS_CHART_SETTING_NAME, $this->options);
            return $this->options;
        }

        public function set_option($xOptions) {
            $this->options = $xOptions;
            if (isset($_POST['oasis_chart_url_update'])) {
                $adminSettings = array('oasis_chart_url', 'advance_iframe_security_key');
                foreach ($adminSettings as $item) {
                    $text = str_replace("'", '"', trim($_POST[$item]));
                    if (function_exists('sanitize_text_field')) {
                        if ($item === 'oasis_chart_url') {
                            $this->options[$item] = trailingslashit(stripslashes(sanitize_text_field($text)));
                        } else {
                            $this->options[$item] = stripslashes(sanitize_text_field($text));
                        }
                    } else {
                        $this->options[$item] = stripslashes($text);
                    }
                }
                update_option(OASIS_CHART_SETTING_NAME, $this->options);
            }
            return $this->options;
        }

    }

}
?>
