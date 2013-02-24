<?

header('Content-Type: text/html; charset=utf-8');
include_once("habrahabr_parser/holy_habr_api.php");
include_once("holy_steps/holy_stepbystep.php");
include_once("holy_fb2/holy_fb2.php");

$login = "newbilius";

$favorites_url = "http://habrahabr.ru/users/{$login}/favorites/";
$file_list = '/tmp/list.txt';
$file_img_list = '/tmp/pictures.txt';
$folder_tmp_articles = "/tmp/articles";
$folder_tmp_pics = "/tmp/pics";
$folder_tmp = "/tmp";

$img_max_size = 400;    //максимальный размер картинок по обеим сторонам
$skip_img = false;      //true - НЕ сохранять картинки
$convert_br_to_p = true;  //true - превращать тэги <br> в </p><p>. При false совместимость выше, чиатемость - ниже.
$out_file = "/habr.fb2";

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
    if (!file_exists(dirname(__FILE__) . $folder_tmp))
        mkdir(dirname(__FILE__) . $folder_tmp);
    if (!file_exists(dirname(__FILE__) . $folder_tmp_articles))
        mkdir(dirname(__FILE__) . $folder_tmp_articles);
    if (!file_exists(dirname(__FILE__) . $folder_tmp_pics))
        mkdir(dirname(__FILE__) . $folder_tmp_pics);
}

;
?>