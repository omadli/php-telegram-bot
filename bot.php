<?php

//Bot Class
class Bot{
    protected $token;
    public $ADMIN;
    public $logsch;
    public $admins;
    /**
     * Bot obyekti konstruktori
     * @param String $token Bot tokeni | Kiritish majburiy
     * @param Int $ADMIN Botning bosh admini ID raqami | Optional
     * @param String $logsch Loglar kanali ID si yoki @username | Optional
     * @param Array $admins Botni boshqarishi mumkin bo'lgan adminlar ro'yxati
     * @return Bool Barcha qiymatlar to'g'ri kiritilsa true, aks holda false
     */
    public function __construct(string $token, int $ADMIN=0, string $logsch='', array $admins=[]){
        if (isset($token)) {
            $this->token = $token;
            $this->ADMIN = $ADMIN;
            $this->logsch = $logsch;
            $this->admins = $admins;
            return true;
        } else{
            return false;
        }
        
    }
    /**
     * @method getToken() Ushbu bot tokenini olish metodi
     * @return string Bot tokeni
     */
    public function getToken(){
        return $this->token;
    }

    /**
     * @method request() Telegram serveriga so'rovlar yuborish metodi
     * @param string $metod Metod nomi, qo'llash mumkin metodlar $actions massivi ichida joylashgan
     * @param array $datas Telegram serveriga yuboriladigan so'rov ma'lumotlari, POSTFIELDS
     * @return array Telegram serveri qaytargan ma'lumotlar massivi
     */
    public function request(string $metod, array $datas){
        if(!in_array($metod, $this->actions)){
            throw new Exception("Undefined method");
        } else {
            $url = "https://api.telegram.org/bot". $this->token . "/" . $metod;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
            $res = curl_exec($ch);
            if(curl_error($ch)){
                $ret = ["ok"=>false,"result"=>["error"=>"curl_error", "error_code"=>curl_errno($ch)]];
            }
            $ret = json_decode($res, true);
            if (!empty($ret) && !$ret['ok']){
                $log = json_encode($ret, JSON_PRETTY_PRINT);
                $datas = json_encode($datas, JSON_PRETTY_PRINT);
                $logger = $this->logsch ?? $this->ADMIN ?? null;
                $this->sm($logger, "#xatolik\nMetod => <code>{$metod}</code> \nDatas => <pre>{$datas}</pre>\n\nResult => <pre>{$log}</pre>", "html");
            }
            return $ret;
        }
    }


    // Xabar yuborish
    /**
     * @method sendMessage() Xabar yuborish metodi
     * @param array $datas Requestda yuboriladigan ma'lumotlar
     * @return array Request qaytargan massiv
     */
    public function sendMessage(array $datas){
        return $this->request("sendMessage", $datas);
    }
    /** sm - SendMessage
     * Matnli xabarlar yuborish uchun sodda metod.
     * @param int $chat_id  xabar yuboriladigan chat_id si | kiritish majburiy
     * @param string $message_text  xabar matni | kiritish majburiy
     * @param string $parse_mode  formatlash turi | optional | default qiymati "markdown"
     * @param int $reply_to_message_id  reply qilib yuboriladigan xabar id si | optioal | default qiymati 0 ya'ni replysiz
     * @param Bool $disable_webpage_preview xabardagi linklar previyevsiz yuborish | optional | default qiymati false
     * @return Array Request qaytargan massiv
     */
    public function sm(string $chat_id, string $message_text, string $parse_mode="markdown", int $reply_to_message_id=0, bool $disable_webpage_preview = false){
        $data = [
            'chat_id'=>$chat_id,
            'text'=>$message_text,
            'parse_mode'=>$parse_mode,
            'disable_web_page_preview'=>$disable_webpage_preview
        ];
        if(!empty($reply_to_message_id)){
            $data['reply_to_message_id'] = $reply_to_message_id;
            return $this->sendMessage($data);
        } else {
            return  $this->sendMessage($data);
        }
    }


    /* 
    * Updatelarni olish
    * Buning 2 xil usuli bor:
    * 1) Webhook -> getWebhookData()
    * 2) Polling -> getUpdates()
    */

    /**
     * @method getWebhookData() Webhook da kelgan Updatelarni olish metodi
     * @return array Updatelar
     */
    public function getWebhookData(){
        $update = json_decode(file_get_contents("php://input"), true);
        if(!empty($update)){
            return $update;
        }
    }

    /**
     * @method getUpdates() bu metod Update larni qo'lda olishda foydalaniladi.
     * @param int $offset update_id Qaytariladigan birinchi yangilanishning identifikatori. Oldindan olingan yangilanishlar identifikatorlari orasida eng yuqori ko'rsatkichdan bittaga kattaroq bo'lishi kerak.
     * @param int $limit Olish kerak bo'lgan Update lar soni, limit | Optional | Default = 100
     * @param int $timeout Polling timeout vaqti | Optional | Default = 0, to'xtovsiz
     * @param array $allowed_updates Olinishi kerak bo'lgan Updatelar turi | Optional | Default=[] barcha turddagi Updatelar
     * @return array Updatelar massivi
     */
    public function getUpdates(int $offset, int $limit=100, int $timeout=0, array $allowed_updates=[]){
        $datas = ['offset'=>$offset, 'limit'=>$limit, 'timeout'=>$timeout];
        if(!empty($allowed_updates)){
                $datas[] = $allowed_updates;
        }
        return $this->request("getUpdates", $datas);
    }
    /**
    * SendChatAction bu bot tomonidan Action yuborishdir
    * Masalan kattaroq jarayon qilayotganda "typing",
    * Rasm yuborayotkanda "upload_photo"
    * Kattaroq fayl yuborayotkanda "upload_document"
    * yuborish maqsadga muvofiq.
    * @param int $chat_id yuboriladigan chat ID si | kiritish majburiy
    * @param string $action Action turi | optional | default qiymati = "typing"
    * @return Array
    **/
    public function sendChatAction(int $chat_id, string $action = "typing"){
        return $this->request("sendChatAction", ['chat_id'=>$chat_id, 'action'=>$action]);
    }
    protected $actions = [
        'getUpdates',
        'setWebhook',
        'deleteWebhook',
        'getWebhookInfo',
        'getMe',
        'logOut',
        'close',
        'sendMessage',
        'forwardMessage',
        'copyMessage',
        'sendPhoto',
        'sendAudio',
        'sendDocument',
        'sendSticker',
        'sendVideo',
        'sendAnimation',
        'sendVoice',
        'sendVideoNote',
        'sendMediaGroup',
        'sendLocation',
        'editMessageLiveLocation',
        'stopMessageLiveLocation',
        'sendVenue',
        'sendContact',
        'sendPoll',
        'sendDice',
        'sendChatAction',
        'getUserProfilePhotos',
        'getFile',
        'kickChatMember',
        'unbanChatMember',
        'restrictChatMember',
        'promoteChatMember',
        'setChatAdministratorCustomTitle',
        'setChatPermissions',
        'exportChatInviteLink',
        'setChatPhoto',
        'deleteChatPhoto',
        'setChatTitle',
        'setChatDescription',
        'pinChatMessage',
        'unpinChatMessage',
        'unpinAllChatMessages',
        'leaveChat',
        'getChat',
        'getChatAdministrators',
        'getChatMembersCount',
        'getChatMember',
        'setChatStickerSet',
        'deleteChatStickerSet',
        'answerCallbackQuery',
        'answerInlineQuery',
        'setMyCommands',
        'getMyCommands',
        'editMessageText',
        'editMessageCaption',
        'editMessageMedia',
        'editMessageReplyMarkup',
        'stopPoll',
        'deleteMessage',
        'getStickerSet',
        'uploadStickerFile',
        'createNewStickerSet',
        'addStickerToSet',
        'setStickerPositionInSet',
        'deleteStickerFromSet',
        'setStickerSetThumb',
        'sendInvoice',
        'answerShippingQuery',
        'answerPreCheckoutQuery',
        'setPassportDataErrors',
        'sendGame',
        'setGameScore',
        'getGameHighScores',
    ];
}


?>
