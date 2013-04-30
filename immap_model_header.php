<?php

class ImmapModelHeader {

    public $header, $dataIndex, $filter, $sortable;

    public function __construct($params = array()) {
        $this->header = isset($params["header"]) ? $params["header"] : '';
        $this->dataIndex = isset($params["dataIndex"]) ? $params["dataIndex"] : '';
        $this->filter = isset($params["filter"]) ? $params["filter"] : true;
        $this->sortable = isset($params["sortable"]) ? $params["sortable"] : true;
        $this->flex = isset($params["flex"]) ? $params["flex"] : 1;
    }

}

?>
