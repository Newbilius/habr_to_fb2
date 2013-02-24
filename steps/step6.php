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

$pics = file(dirname(dirname(__FILE__)).$file_img_list);
$count = count($pics);

$file = new HolyFB2(dirname(dirname(__FILE__)).$out_file);

if (isset($pics[$num])) {
    if ($pics[$num]) {
        $img_tmp = explode("#IMG#", $pics[$num]);
        if (is_array($img_tmp))
            if ($img_tmp[0]) {
                $name = trim($img_tmp[0]);
                if ($name) {
                    $path = dirname(dirname(__FILE__)).$folder_tmp_pics."/".$name;
                    if (file_exists($path)) {
                        $file->add_file($name, $path,$img_max_size);
                    };
                };
            };
        echo $step->this_step("запись картинки {$name} завершено (картинка номер {$next_num} из {$count})", "?num={$next_num}");
    };
} else {
    echo $step->next_step();
}
?>