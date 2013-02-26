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
$next_num=$num+1;

$file = new HolyFB2(dirname(dirname(__FILE__)).$out_file);

$articles=file(dirname(dirname(__FILE__)).$file_list);
$count=count($articles);

if (isset($articles[$num])) {
    $article_id=intval($articles[$num]);
    
    $content= unserialize(file_get_contents(dirname(dirname(__FILE__)).$folder_tmp_articles."/".$article_id.".html"));
    $file->add_section($content['caption'], $content['content_ok']['text'],$convert_br_to_p);
    $log->add("сохранение в FB2-файл статьи с id {$article_id} завершена");
    echo $step->this_step("сохранение в файл статьи с id {$article_id} завершена (статья номер {$next_num} из {$count})", "?num={$next_num}");
} else {
    $file->write_end_body();
    echo $step->next_step();
}
?>