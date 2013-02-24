<?php

include_once(dirname(dirname(__FILE__)) ."/config.php");

$file = new HolyFB2(dirname(dirname(__FILE__)).$out_file);

$file->write_header(Array());
$file->write_start_body();

echo $step->next_step();
?>