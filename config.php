<?php
header('Content-Type: text/html; charset=utf-8');
include_once("habrahabr_parser/holy_habr_api.php");
include_once("holy_steps/holy_stepbystep.php");
include_once("holy_steps/holy_log.php");
include_once("holy_steps/phpMultiCurl.php");
include_once("holy_fb2/holy_fb2.php");

$login = "newbilius";

$favorites_url = "http://habrahabr.ru/users/{$login}/favorites/";
$file_list = '/tmp/list.txt';
$file_img_list = '/tmp/pictures.txt';
$folder_tmp_articles = "/tmp/articles";
$folder_tmp_pics = "/tmp/pics";
$folder_tmp = "/tmp";
$log_file=$_SERVER['DOCUMENT_ROOT']."/log.txt";

$log_on=false;              //true - писать лог, false - не писать (может замедлять работу)
$img_max_size = 400;        //максимальный размер картинок по обеим сторонам
$skip_img = false;          //true - НЕ сохранять картинки
$convert_br_to_emptyline= true;    //true - превращать тэги BR в EMPTY-LINE. При false BR просто удаляется. Во втором вариане совместимость выше, читаемость - ниже.
$comments=false;             //сохранять комментарии (увеличивает размер файла и время генерации, комментарии отображаются линейно, из комментариев удаляются картинки)
$out_file = "/habr_{$login}.fb2"; //имя получающегося файла
$img_multi_load=1;      //сколько картинок скачивать за один проход скрипта. По-умолчанию - одну за проход.

include_once("_work_file.php");
?>