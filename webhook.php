<?php
// Agar Updatelarni webhook orqali olmoqchi bo'lsangiz bu faylni webhook ga ulang
require_once(__DIR__."/config.php");
$ws = $_GET['ws'] ?? null;
// Webhook qilishda tokenni GET metodi orqali ws paramatrida ko'rsatish kerak.
// config.php faylida Tokenni o'zini emas shifrlangan token hashini saqlash maqsadga muvofiq

if(!empty($ws) && md5($ws) == $secret){
  if (file_exists("bot.php")){
      require_once "bot.php";
  } else {
      echo "Please include bot.php file!";
      exit();
  }
  $token = $ws;
  $bot = new Bot($token, $ADMIN, $logsch, $admins); //bot obyekti
  echo "Bot ish faoliyatida";
  $update = $bot->getWebhookData();
  if (!empty($update)){
      try{
          include __DIR__ . '/main.php';
      } catch(Exception $e){
          $e = json_encode($e->getMessage(), JSON_PRETTY_PRINT);
          $bot->sm($bot->logsch ?? $bot->ADMIN, "#Exception\n<pre>{$e}</pre>", "html");
      }
      if((isset($update['message']) && in_array($update['message']['from']['id'], $bot->admins)) || (isset($update['callback_query']) && in_array($update['callback_query']['from']['id'], $bot->admins))){
        try{
            include __DIR__ . '/admin.php';
        } catch(Exception $e){
            $e = json_encode($e->getMessage(), JSON_PRETTY_PRINT);
            $bot->sm($bot->logsch ?? $bot->ADMIN, "#Exception\n<pre>{$e}</pre>", "html");
        }  
      }
  }
} else {
  echo("<b>Warning! You don't permission danied!</b>");
}
 ?>
