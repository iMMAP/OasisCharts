<?php

class ImmapModelCombobox {

    public $data;

    public function __construct($params = array()) {
        $this->data = isset($params["name"]) ? $params["name"] : '';
    }

    public function to_json() {
        return json_encode(array('data' => $this->data));
    }

}

?>
