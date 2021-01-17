<?php

// Bu yerda bot logikasi yoziladi


/**
 * @var Array $update
 * $update o'zgaruvchisi webhookdan keladimi, pollingdan keladimi bizga farq qilmaydi
 * @var class $bot
 * $bot obyekti bu bizning bot
 **/
global $bot;
if(isset($update['message'])){

    $message = $update['message'];
    $fromID = $message['from']['id'];
    $msgID = $message['message_id'];
    $chatID = $update['message']['chat']['id'];
    $text = $message['text'];
    if(isset($text)){
        if($text == "/start"){
            $bot->sm($chatID, "Salom");
        }
        if($text == "/help"){
            $bot->sm($chatID, "Nima yordam kerak?", "markdown", $msgID);
        }
        if($text=='/test'){
            $data = ['chat_id' => 777, "text"=>"Test"];
            $bot->sendChatAction($chatID);
            sleep(1);
            $bot->sendMessage($data);
        }
    }
}

?>