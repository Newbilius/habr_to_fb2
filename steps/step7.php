<?php

include_once(dirname(dirname(__FILE__)) ."/config.php");

$file = new HolyFB2(dirname(dirname(__FILE__)).$out_file);
$file->write_footer();
echo $step->next_step();
?>