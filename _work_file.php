<?php
$step = new Holy_stepbystep();

$step_num = 0;
$steps_array = Array(
    1 => "скачиваем список статей",
    2 => "скачиваем каждую статью",
    3 => "скачиваем картинки",
    4 => "записываем начало файла в FB2",
    5 => "записываем статьи в FB2",
    6 => "записываем картинки в FB2",
    7 => "записываем конец файла FB2",
);
if (isset($_GET['skip_download']))
    if ($_GET['skip_download'] == 1) {
        unset($steps_array[1]);
        unset($steps_array[2]);
        unset($steps_array[3]);
    };
if ($skip_img) {
    unset($steps_array[6]);
}

foreach ($steps_array as $step_file_num => $_step) {
    $step_num++;
    $step->add("steps/step{$step_file_num}.php", "Шаг {$step_num} - {$_step}");
}

function create_dirs() {
    global $folder_tmp;
    global $folder_tmp_articles;
    global $folder_tmp_pics;

    if (!file_exists(dirname(__FILE__) . $folder_tmp))
        mkdir(dirname(__FILE__) . $folder_tmp);
    if (!file_exists(dirname(__FILE__) . $folder_tmp_articles))
        mkdir(dirname(__FILE__) . $folder_tmp_articles);
    if (!file_exists(dirname(__FILE__) . $folder_tmp_pics))
        mkdir(dirname(__FILE__) . $folder_tmp_pics);
}

;
?>