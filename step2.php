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

$articles=file($file_list);
$count=count($articles);

if (isset($articles[$num])) {
    $article_id=intval($articles[$num]);
    
    $item_src = new HolyHabrAPI();
    $content = serialize($item_src->get_article($article_id));

    file_put_contents("tmp/articles/".$article_id.".html", $content . "\n");
    
    echo $step->this_step("обработка статьи с id {$article_id} завершена (статья номер {$next_num} из {$count})", "?num={$next_num}");
} else {
    echo $step->next_step();
}
?>