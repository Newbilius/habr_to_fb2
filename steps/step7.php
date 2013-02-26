<?php

include_once(dirname(dirname(__FILE__)) ."/config.php");
create_dirs();

$file = new HolyFB2(dirname(dirname(__FILE__)).$out_file);
$file->write_footer();
$log->add("записываем в файл конец FB2-файла");

echo $step->next_step();
?>