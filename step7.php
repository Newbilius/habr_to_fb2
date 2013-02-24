<?php

include_once("config.php");

$file = new HolyFB2("test.fb2");
$file->write_footer();
echo $step->next_step();
?>