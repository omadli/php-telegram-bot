<?php

// include "bot.php";
// Bu yerda bot logikasi yoziladi

date_default_timezone_set('Asia/Tashkent');

/**
 * @var Array $update
 * $update o'zgaruvchisi webhookdan keladimi, pollingdan keladimi bizga farq qilmaydi
 * @var class $bot
 * $bot obyekti bu bizning bot
 **/
global $bot;

$main_keyboard = $bot->ReplyKeyboard([
      [['text'=>"💌Bog'lanish"],['text'=>"⚙️Sozlamalar"]],
    // ...
    ]);
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
            if($text == "/start"){
                $join = $bot->getJoin($fromID, "@UFC_JanglariUZ");
                $j = $join ? 1 : 0;
              //  $bot->sm($chatID, "Assalomu alaykum [$name](tg://user?id=$fromID)", "markdown");
                if($join){
                  //  $bot->sm($chatID, "Asosiy menyu");
                    $bot->sendWithKeyboard($chatID, "Asosiy menyu", $main_keyboard);
                } else {
                  $keyboard = [
                    [['text'=>"📌Azo bo'lish", 'url'=>"https://t.me/UFC_JanglariUZ"]],
                    [['text'=>"✅Tasdiqlash", 'callback_data'=>"tasdiqlash"]]
                    ];
                  $keyboard = $bot->InlineKeyboard($keyboard);
                    $bot->sendWithKeyboard ($chatID, "Botdan foydalanish uchun @UFC_JanglariUZ kanalimizga azo bo'ling.", $keyboard);
                }
            }
            if($text == "/help"){
                $bot->sm($chatID, "Nima yordam kerak?", "markdown", $msgID);
            }
            if($text=="⚙️Sozlamalar"){
              // pas   
            }
            if($text == "💌Bog'lanish"){
              $keyboard = $bot->InlineKeyboard([
                [['text'=>"💬Bog'lanish", 'url'=>"https://t.me/murodillo17"]] // admin username
              ]);
              $bot->sendWithKeyboard($chatID, "Reklama va boshqalar uchun:\n👨‍💻Dasturchi @murodillo17", $keyboard);
            }       
            if($text=="↪️Orqaga"){
              $bot->sendWithKeyboard($chatID, "Asosiy menyu", $main_keyboard);
            }
            // ...
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
    if($data == "tasdiqlash"){
      $join = $bot->getJoin($fromID, "@UFC_JanglariUZ");
      $j = $join ? 1 : 0;
      if($join){
        $bot->deleteMessage($chatID, $msgID);
        $bot->sendWithKeyboard($chatID, "Asosiy menyu", $main_keyboard);
      } else {
        $bot->answerCallbackQuery($id, "Kechirasiz kanalimizga hali azo emas ko'rinasiz. Azo bo'lgach bu tugmani bosing", true);
      }
    }
  }
}

?>
