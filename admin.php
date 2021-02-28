<?php

/**
 * @var $update Webhookdan yoki Pollingdan kelgan Update
*/
include __DIR__."/bot.php";
include __DIR__."/config.php";

$bot = new Bot($ws, $ADMIN, $logsch, $admins);

global $update;
global $db;

if(isset($update['message'])){

    $message = $update['message'];
    $fromID = $message['from']['id'];
    $name = $message['from']['first_name'];
    $msgID = $message['message_id'];
    $chatID = $update['message']['chat']['id'];
    $type = $update['message']['chat']['type'];
    $text = $message['text'];
    if(isset($text)){
        if($type == "private"){
          // code
        }
    }
}
if(isset($update['callback_query'])){
    $callback_query = $update['callback_query'];
    $id = $callback_query['id'];
    $message = $callback_query['message'];
    $chatID = $message['chat']['id'];
    $type = $message['chat']['type'];
    $fromID = $callback_query['from']['id'];
    $name = $callback_query['from']['first_name'];
    $msgID = $message['message_id'];
    $data = $callback_query['data'];

    if($type == "private"){
      // code
    }
}
