<?php

class ImmapModelField {

    public $name, $type;

    public function __construct($params = array()) {
        $this->name = isset($params["name"]) ? $params["name"] : '';
        $this->type = isset($params["type"]) ? $params["type"] : '';
    }

}

?>
