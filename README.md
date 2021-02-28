# php-telegram-bot
<p>PHP-Telegram-Bot</p>
<b>PHP dasturlash tilida telegram botlar yaratish uchun qulay kutubxona</b><br><br>
<code>bot.php</code> faylida asosiy kutubxona, <em>Bot</em> CLASSi yozilgan.<br>
<code>config.php</code> faylida konfiguratsiya - bot tokeni, Admin ID si, Loglar kanali ID si, barcha adminlar ro'yxati yoziladi;<br>
Ishlash usuli ikki xil:<ol>
<li>Webhook</li>
<li>Polling</li>
</ol>
Pollingda Updatelar qo'lda olinadi. <code>polling.php</code> faylini qarang.<br>
Webhookda esa Updatelar telegram tomonidan sizning serverga yuboriladi. Bunda sizga server, Domen, SSL sertifikat kerak bo'ladi.<br>
Webhookni <code>webhook.php</code> fayliga qilasiz.<br><br><code>ws</code> parametrida bot tokenini qo'ysangiz maqsadga muvofiq.Namuna:<br>
<code>"https://api.telegram.org/bot<TOKEN>/setWebhook?url=".urldecode("https://yoursite.com/path/to/webhook.php?ws=<TOKEN>")</code><br><br>
<b>Asosiy qism <code>main.php</code> faylida yozilinadi.</b> Bunda Update lar pollingdan keladimi yoki webhookdan buni farqi yoq.


