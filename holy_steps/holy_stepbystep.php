<?php

class Holy_stepbystep {

    protected $urls_array = array();

    function __constructor() {

    }

    function add($url, $caption) {
        $this->urls_array[] = Array("url"     => $url, "caption" => $caption);
        return $this;
    }

    function draw_list($list_file) {
        $list = $this->urls_array;
        include_once ($list_file);
    }

    public static function get_json($data) {
        return json_encode($data);
    }

    protected function _get_status($status, $text = "", $add_url = "") {
        return Holy_stepbystep::get_json(Array(
                    "status"  => $status,
                    "text"    => $text,
                    "add_url" => $add_url,
                ));
    }

    function next_step($text = "", $add_url = "") {
        return $this->_get_status("next", $text, $add_url);
    }

    function this_step($text = "", $add_url = "") {
        return $this->_get_status("this", $text, $add_url);
    }

}

;
?>