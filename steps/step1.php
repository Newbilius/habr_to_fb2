<?php

include_once(dirname(dirname(__FILE__)) ."/config.php");
create_dirs();

if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
} else {
    $_GET['page'] = intval($_GET['page']);
}

if (isset($_GET['next_url'])) {
    $favorites_url = $_GET['next_url'];
}

$page = $_GET['page'];
$page++;

$list_src = new HolyHabrAPI();
$list_src->change_page($favorites_url);
$list = $list_src->get_article_list(array("next_url"));

if (isset($list['next_url'])) {
    $next_page = $list['next_url'];
    echo $step->this_step("обработка страницы номер {$page}", "?page={$page}&next_url=http://habrahabr.ru{$next_page}");
} else {
    echo $step->next_step();
}

if (isset($list['items'])){
    if (is_array($list['items'])){
        foreach($list['items'] as $_item){
            file_put_contents(dirname(dirname(__FILE__)).$file_list, $_item['id']."\n", FILE_APPEND | LOCK_EX);
        }
    }
}
?>