<?php
// Agar Updatelarni webhook orqali olmoqchi bo'lsangiz bu faylni webhook ga ulang
if (file_exists("bot.php")){
    require_once "bot.php";
} else {
    echo "Please include bot.php file!";
    exit();
}
include "config.php";
$bot = new Bot($token, $ADMIN, $logsch, $admins); //bot obyekti

$update = $bot->getWebhookData();
if (!empty($update)){
    try{
        include __DIR__ . '/main.php';
    } catch(Exception $e){
        $e = json_encode($e, JSON_PRETTY_PRINT);
        $bot->sm($bot->logsch, "#Exception\n<pre>{$e}</pre>", "html");
    }
}
 ?>