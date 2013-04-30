<?php

class ImmapModelChartSeries {

    public $name, $type, $dataIndex, $visible, $categorieField, $dataField;

    public function __construct($params = array()) {
        $this->name = isset($params["name"]) ? $params["name"] : '';
        $this->type = isset($params["type"]) ? $params["type"] : '';
        $this->dataIndex = isset($params["dataIndex"]) ? $params["dataIndex"] : '';
        $this->visible = isset($params["visible"]) ? $params["visible"] : true;
        $this->categorieField = isset($params["categorieField"]) ? $params["categorieField"] : '';
        $this->dataField = isset($params["dataField"]) ? $params["dataField"] : '';
    }

    public function getSeriesBar() {
        $this->type = 'bar';
        $chartModel = array("name" => $this->name,
            "type" => $this->type,
            "dataIndex" => $this->dataIndex,
            "visible" => $this->visible
        );
        return $chartModel;
    }

    public function getSeriesColumn() {
        $this->type = 'column';
        $chartModel = array("name" => $this->name,
            "type" => $this->type,
            "dataIndex" => $this->dataIndex,
            "visible" => $this->visible
        );
        return $chartModel;
    }

    public function getSeriesSpline() {
        $this->type = 'spline';
        $chartModel = array('name' => $this->name,
            'type' => $this->type,
            'dataIndex' => $this->dataIndex,
            'visible' => $this->visible
        );
        return $chartModel;
    }

    public function getSeriesPie() {
        $this->type = 'pie';
        $chartModel = array("name" => $this->name,
            "type" => $this->type,
            "categorieField" => $this->categorieField,
            "dataField" => $this->dataField,
        );
        
        return $chartModel;
    }

//    public function getSeriesPie($k1, $k2) {
//        $this->type = 'pie';
//        $chartModel = array("name" => $this->name,
//            "type" => $this->type,
//            "categorieField" => $this->categorieField,
//            "dataField" => $this->dataField,
//            center => array($k1, $k2)
//        );
//        return $chartModel;
//    }

}

?>
