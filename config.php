<?
header('Content-Type: text/html; charset=utf-8');
include_once("habrahabr_parser/holy_habr_api.php");
include_once("holy_steps/holy_stepbystep.php");
include_once("holy_fb2/holy_fb2.php");

$login="newbilius";

$favorites_url="http://habrahabr.ru/users/{$login}/favorites/";
$file_list = 'tmp/list.txt';
$file_img_list = 'tmp/pictures.txt';
$img_max_size=600;


$step = new Holy_stepbystep();

$step_num=0;
$steps_array=Array(
    1=>"скачиваем список статей",
    2=>"скачиваем каждую статью",
    3=>"скачиваем картинки",
    4=>"записываем начало файла в FB2",
    5=>"записываем начало статьи в FB2",
    6=>"записываем картинки в FB2",
    7=>"записываем конец файла FB2",
);
if (isset($_GET['skip_download']))
    if ($_GET['skip_download']==1){
    unset($steps_array[1]);
    unset($steps_array[2]);
    unset($steps_array[3]);
};

foreach ($steps_array as $step_file_num=>$_step){
    $step_num++;
    $step->add("step{$step_file_num}.php", "Шаг {$step_num} - {$_step}");
}

if (!file_exists("tmp"))
    mkdir("tmp");
if (!file_exists("tmp/articles"))
    mkdir("tmp/articles");
if (!file_exists("tmp/pics"))
    mkdir("tmp/pics");
?>