<?php

include_once(dirname(dirname(__FILE__)) . "/config.php");
create_dirs();

function on_load_img($Content, $Info, $Data) {
    global $folder_tmp_pics;
    global $log;
    $name = trim($Data['new_name']);
    if ($Content) {
        @file_put_contents(dirname(dirname(__FILE__)) . $folder_tmp_pics . "/" . $name, $Content);
        $log->add("сохраняем картинку {$url} под именем {$name} [номер картинки {$num}]");
    } else {
        $log->add("ОШИБКА сохранения картинки {$url} под именем {$name} [номер картинки {$num}]");
    }
};

function on_error_img($Error, $Data) {};
    
if (!isset($_GET['num'])) {
    $_GET['num'] = 0;
} else {
    $_GET['num'] = intval($_GET['num']);
}

if ($_GET['num'] == 0)
    $_GET['num'] = 0;

$num = $_GET['num'];
$next_num = $num + $img_multi_load;

$pics = file(dirname(dirname(__FILE__)) . $file_img_list);
$count = count($pics);

if ($img_multi_load == 1) {
    if (isset($pics[$num])) {
        $img_tmp = explode("#IMG#", $pics[$num]);
        if (is_array($img_tmp)) {
            $img_content = false;
            $name = trim($img_tmp[0]);
            $url = trim($img_tmp[1]);
            $img_content = @file_get_contents($url);
            if ($img_content) {
                @file_put_contents(dirname(dirname(__FILE__)) . $folder_tmp_pics . "/" . $name, $img_content);
                $log->add("сохраняем картинку {$url} под именем {$name} [номер картинки {$num}]");
            } else {
                $log->add("ОШИБКА сохранения картинки {$url} под именем {$name} [номер картинки {$num}]");
            }
            echo $step->this_step("скачивание картинки {$name} завершено (картинка номер {$next_num} из {$count})", "?num={$next_num}");
        };
    } else {
        echo $step->next_step();
    }
} else {
    $next_need = false;

    $curl = new phpMultiCurl;
    $curl->setNumTreads($img_multi_load);

    for ($i = $_GET['num']; $i < $next_num;$i++)
        if (isset($pics[$i])) {
            $img_tmp = explode("#IMG#", $pics[$i]);
            if (is_array($img_tmp)) {
                $img_content = false;
                $name = trim($img_tmp[0]);
                $url = trim($img_tmp[1]);
                $curl->addUrl(
                        $url, 'on_load_img', 'on_error_img',
                        //arguments for callbacks (see $Data)
                        array("new_name" => $name)
                );
            };
        } else {
            $next_need = true;
        };

    $curl->load();

    if (!$next_need) {
        echo $step->this_step("скачивание картинки {$name} завершено (картинка номер {$next_num} из {$count})", "?num={$next_num}");
    } else {
        echo $step->next_step();
    }
}
?>