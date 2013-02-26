<?php

class Holy_log {

    protected $file_name = "";
    protected $log_on = true;

    function Holy_log($filename) {
        $this->set_file($filename);
    }

    function set_file($filename){
        $this->file_name=$filename;
    }
    
    function turn_off() {
        $this->log_on = false;
    }

    function turn_on() {
        $this->log_on = true;
    }

    function add($text) {
        if ($this->log_on) {
            if ($this->file_name) {
                file_put_contents($this->file_name, date("d.m.Y H:i:s") . " " . $text."\r\n", FILE_APPEND);
            }
        }
    }

}

;
?>