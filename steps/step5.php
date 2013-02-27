<?php

include_once(dirname(dirname(__FILE__)) . "/config.php");
create_dirs();

if (!isset($_GET['num'])) {
    $_GET['num'] = 0;
} else {
    $_GET['num'] = intval($_GET['num']);
}

if ($_GET['num'] == 0)
    $_GET['num'] = 0;

$num = $_GET['num'];
$next_num = $num + 1;

function get_comments_from_array($comments,$add_text="") {
    $content = "";
    if (is_array($comments)) {
        foreach ($comments as $_comment) {
            $content.="</p></empty-line><p>{$add_text}" .
                    "<b>" . $_comment['user_info']['name'] . "</b> " .
                    "(" . $_comment['time'] . ")" . " " .
                    "[" . $_comment['score'] . "]" . " " .
                    "</p><p>{$add_text}" .
                    $_comment['html']
            ;
            if (isset($_comment['childs'])) {
                if (count($_comment['childs']) > 0) {
                    $add_text.="&#160&#160";
                    $content.="<p>" . get_comments_from_array($_comment['childs'],$add_text);
                }
            }
        }
    }
    return $content;
}

$file = new HolyFB2(dirname(dirname(__FILE__)) . $out_file);

$articles = file(dirname(dirname(__FILE__)) . $file_list);
$count = count($articles);

if (isset($articles[$num])) {
    $article_id = intval($articles[$num]);

    $content = unserialize(file_get_contents(dirname(dirname(__FILE__)) . $folder_tmp_articles . "/" . $article_id . ".html"));
    $content_out = $content['content_ok']['text'];

    if ($comments) {
        //print_pr($content['comments']);
        $comments_text = HolyFB2::prepare_text(get_comments_from_array($content['comments']), true, $convert_br_to_p);
        $content_out.="</p></empty-line><p><b>Комментарии</b></p></empty-line><p>";
        $content_out.=$comments_text;
        //echo $content_out;
        //die();
    }

    $file->add_section($content['caption'], $content_out, $convert_br_to_p);
    $log->add("сохранение в FB2-файл статьи с id {$article_id} завершена");
    echo $step->this_step("сохранение в файл статьи с id {$article_id} завершена (статья номер {$next_num} из {$count})", "?num={$next_num}");
} else {
    $file->write_end_body();
    echo $step->next_step();
}
?>