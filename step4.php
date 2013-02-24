<?php

include_once("config.php");

$file = new HolyFB2("habr.fb2");

$file->write_header(Array());
$file->write_start_body();

echo $step->next_step();
?>