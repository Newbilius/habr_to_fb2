<?php

include_once("config.php");

if (!isset($_GET['num'])) {
    $_GET['num'] = 0;
} else {
    $_GET['num'] = intval($_GET['num']);
}

if ($_GET['num'] == 0)
    $_GET['num'] = 0;

$num = $_GET['num'];
$next_num=$num+1;

$file = new HolyFB2("habr.fb2");

$articles=file($file_list);
$count=count($articles);

if (isset($articles[$num])) {
    $article_id=intval($articles[$num]);
    
    $content= unserialize(file_get_contents("tmp/articles/{$article_id}.html"));
    $file->add_section($content['caption'], $content['content_ok']['text']);
    echo $step->this_step("сохранение в файл статьи с id {$article_id} завершена (статья номер {$next_num} из {$count})", "?num={$next_num}");
} else {
    $file->write_end_body();
    echo $step->next_step();
}
?>