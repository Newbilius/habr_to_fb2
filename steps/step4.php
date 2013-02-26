<?php

include_once(dirname(dirname(__FILE__)) . "/config.php");
create_dirs();

$file = new HolyFB2(dirname(dirname(__FILE__)) . $out_file);

$today_date = date("d.m.Y");
$today_date_time = date("d.m.Y_H:i:s");

$file->write_header(Array(
    "date" => $today_date,
    "id" => $favorites_url . "_" . $today_date_time,
    "annotation" => "Избранное пользователя {$login} с сайта ХабраХабр",
    "book-title" => "Избранное {$login} с ХабраХабра",
    "author" => Array(
        "first-name" => "Множество",
        "last-name" => "Авторов",
        "middle-name" => "Хабра",
    ),
    "document_author" => Array(
        "first-name" => "Дмитрий",
        "last-name" => "Моисеев",
        "middle-name" => "Алексеевич",
        "nickname" => "Newbilius (Nubilius)",
        "email" => "newbilius@gmail.com",
    ),
));
$file->write_start_body();
$log->add("записываем в файл заголовки FB2-файла");

echo $step->next_step();
?>