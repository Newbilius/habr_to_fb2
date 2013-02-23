<?
header('Content-Type: text/html; charset=utf-8');
include_once("habrahabr_parser/holy_habr_api.php");
include_once("holy_steps/holy_stepbystep.php");

$favorites_url="http://habrahabr.ru/users/newbilius/favorites/";
$file_list = 'tmp/list.txt';

$step = new Holy_stepbystep();
$step->add("step1.php", "Шаг 1 - скачиваем список статей");
$step->add("step2.php", "Шаг 2 - скачиваем каждую статью");
$step->add("step3.php", "Шаг 3 - обрабатываем статью (фильтр лишних тэгов, скачивание картинок)");
$step->add("step4.php", "Шаг 4 - записываем начало FB2");
$step->add("step5.php", "Шаг 5 - записываем статьи в FB2");
$step->add("step6.php", "Шаг 6 - записываем конец FB2");

if (!file_exists("tmp"))
    mkdir("tmp");
if (!file_exists("tmp/articles"))
    mkdir("tmp/articles");
?>