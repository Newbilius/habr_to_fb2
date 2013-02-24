<?php

include_once(dirname(dirname(__FILE__)) ."/config.php");
create_dirs();

if (!isset($_GET['num'])) {
    $_GET['num'] = 0;
} else {
    $_GET['num'] = intval($_GET['num']);
}

if ($_GET['num'] == 0)
    $_GET['num'] = 0;

$num = $_GET['num'];
$next_num = $num + 1;

$pics = file(dirname(dirname(__FILE__)) .$file_img_list);
$count = count($pics);
if (isset($pics[$num])) {
    $img_tmp = explode("#IMG#", $pics[$num]);
    if (is_array($img_tmp)) {
        $img_content=false;
        $name = trim($img_tmp[0]);
        $url = trim($img_tmp[1]);
        $img_content=@file_get_contents($url);
        if ($img_content){
            @file_put_contents(dirname(dirname(__FILE__)).$folder_tmp_pics."/".$name, $img_content);
        };
    };
    echo $step->this_step("скачивание картинки {$name} завершено (картинка номер {$next_num} из {$count})", "?num={$next_num}");
} else {
    echo $step->next_step();
}
?>