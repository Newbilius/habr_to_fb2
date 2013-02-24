<?php

include_once(dirname(dirname(__FILE__)) . "/config.php");

if (!isset($_GET['num'])) {
    $_GET['num'] = 0;
} else {
    $_GET['num'] = intval($_GET['num']);
}

if ($_GET['num'] == 0)
    $_GET['num'] = 0;

$num = $_GET['num'];
$next_num = $num + 1;

$articles = file(dirname(dirname(__FILE__)) . $file_list);
$count = count($articles);

if (isset($articles[$num])) {
    $article_id = intval($articles[$num]);

    $item_src = new HolyHabrAPI();
    $content_tmp = $item_src->get_article($article_id);
    $content_tmp['content_ok'] = HolyHabrAPI::prepare_content_for_download($content_tmp['content'], "{$article_id}_");
    $content = serialize($content_tmp);

    if (isset($content_tmp['content_ok']['files'])) {
        $files = $content_tmp['content_ok']['files'];
        if (is_array($files)) {
            foreach ($files as $pic_id => $_file) {
                file_put_contents(dirname(dirname(__FILE__)) . $file_img_list, $pic_id . "#IMG#" . $_file . "\n", FILE_APPEND | LOCK_EX);
            }
        }
    }

    file_put_contents(dirname(dirname(__FILE__)) . $folder_tmp_articles . "/" . $article_id . ".html", $content . "\n");

    echo $step->this_step("обработка статьи с id {$article_id} завершена (статья номер {$next_num} из {$count})", "?num={$next_num}");
} else {
    echo $step->next_step();
}
?>