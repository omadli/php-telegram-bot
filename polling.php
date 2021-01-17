<?php

// Bu yerda polling yoziladi, skriptni konsoldan (cmd) php polling.php deb ishga tushirish kerak
if (file_exists("bot.php")){
    require_once "bot.php";
} else {
    echo "Please require bot.php file!";
    exit();
}
include "config.php";
$bot = new Bot($token, $ADMIN, $logsch, $admins); //bot obyekti

$offset = 0;
while (true) {
    $updates = $bot->getUpdates($offset);
    // print_r($updates);
    if(!empty($updates)){
        if($updates['ok']) {
            array_walk(
                $updates['result'],
                function ($update, $key) {
                    try{
                    // print_r($update);
                    include __DIR__ . '/main.php';
                    } catch(Exception $e){
                        $e = json_encode($e, JSON_PRETTY_PRINT);
                        $bot->sm($bot->logsch, "<pre>{$e}</pre>", "html");
                    }
                    global $offset;
                    $offset = ++$update['update_id'];
                }
            );
        } else {
            var_dump($updates);
        }
    }
    // Ha deb telegramni bezovta qilavermaslik uchun, olib tashlansa, yoki oshirilsa ham bo'ladi
    sleep(1);
}
