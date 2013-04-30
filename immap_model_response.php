<?php

/**
 * @class Response
 * A simple JSON Response class.
 */
class ImmapModelResponse {

    public $success, $data, $total, $message, $errors, $tid, $trace, $metadata, $columns;

    public function __construct($params = array()) {
        $this->success = isset($params["success"]) ? $params["success"] : false;
        $this->message = isset($params["message"]) ? $params["message"] : '';
        $this->total = isset($params["total"]) ? $params["total"] : '';
        $this->data = isset($params["data"]) ? $params["data"] : array();
        $this->metadata = isset($params["metadata"]) ? $params["metadata"] : array();
        $this->columns = isset($params["columns"]) ? $params["columns"] : array();
    }
    public function to_json($metadata = NULL,$columns = NULL) {
        if ($metadata === NULL) {
            return json_encode(array(
                        'success' => $this->success,
                        'message' => $this->message,
                        'total' => $this->total,
                        'data' => $this->data
                    ));
        } else {
            $this->metadata = $metadata;
            $this->columns = $columns;
            return json_encode(array(
                        'metaData' => $this->metadata,
                        'success' => $this->success,
                        'message' => $this->message,
                        'total' => $this->total,
                        'data' => $this->data,
                        'columns' => $this->columns
                    ));
        }
    }

}

// End of Line
// End of File lib/Reponse.php