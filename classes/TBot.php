<?php
    
class TBot
{
    
    public $telega_url;
    
    public function __construct()
    {
        $base = new BaseAPI;
        $token = 'https://api.telegram.org/bot' .  $base->getToken();
        define('TELEGA_URL', $token);
        //print_r(TELEGA_URL);
    }
    
    function sendMes($chat_id, $text) //возвращает message_id
    {
        $method = 'sendMessage';
        $data_to_send = [
            'chat_id'    => $chat_id,
            'text'       => $text,
            'parse_mode' => 'HTML',
            ];
        $res = $this->sendPost($method, $data_to_send);
        return $res['result']['message_id'];
    }
    
    function sendKeyboard($chat_id, $text, $keyboard)
    {
        $method = 'sendMessage';
        $data_to_send = [
                            'chat_id' => $chat_id,
                            'text' => $text,
                            'parse_mode' => 'HTML',
                            'reply_markup' => $keyboard,
                        ];
        $res = $this->sendPost($method, $data_to_send);
        //return $res['result']['message_id'];
    }
    function answerCallbackQuery($callback_id, $text, $alert)
    {
        $method = 'answerCallbackQuery';
        $data_to_send['callback_query_id'] = $callback_id;
        $data_to_send['text'] = $text;
        $data_to_send['show_alert'] = 'true';//$alert
        
        $res = $this->sendPost($method, $data_to_send);
    }
    function sendAction($chat_id)
    {
        $data['chat_id'] = $chat_id;
        $data['action'] = 'typing';
        $this->sendPost('sendChatAction', $data);
    }
    //~~~~~~~~~~~~~~~~~~~~
    function sendPost($method, $data, $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_POST            => 1,
            CURLOPT_HEADER          => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => TELEGA_URL . '/' . $method,
            CURLOPT_POSTFIELDS      => json_encode($data),
            CURLOPT_HTTPHEADER      => array_merge(array("Content-Type: application/json"), $headers),
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return (json_decode($result, 1) ? json_decode($result, 1) : $result);
    }


  //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    function forwardMessage($chat_id, $mes_id)
    {
        $data['chat_id'] = BOT_GROUP;
        $data['from_chat_id'] = $chat_id;
        $data['message_id'] = $mes_id;
        $res = $this->sendPost("forwardMessage", $data);
        return $res;
    }
    function delMess($chat_id,$mes_id)
    {
        $data['chat_id'] = $chat_id;
        $data['message_id'] = $mes_id;
        $res = $this->sendPost("deleteMessage", $data);
        return $res;
    }    
    function sendPhoto($chat_id, $text, $photo_url)
    {
        $data['chat_id'] =  $chat_id;
        $data['photo'] = $photo_url;//"https://drive.google.com/uc?export=view&id=1C3wJXOGNirdsbg77dcB8SAbhfogAN9cc";
        $data['caption'] = $text;
        $res = $this->sendPost('sendPhoto', $data);
        //return $res['result']['message_id'];
    }
    function sendMediaGroup($chat_id, $arrImages, $text)
    {
        $media = [
            ['type' => 'photo', 'media' => $arrImages[0], 'caption' => $text],
        ];
        for($i = 1; $i < count($arrImages); $i++) 
        {
            $media[$i] = ['type'=>'photo', 'media'=> $arrImages[$i]];
        }
        $data = [
          'chat_id'=> $chat_id, //'968407066'
          'media'=> $media,
        ];
        $res = $this->sendPost('sendMediaGroup', $data);
        return $res['result']['message_id'];
    }
    function getChat($chat_id)
    {
        $data['chat_id'] = $chat_id;
        $res = $this->sendPost('getChat', $data);
        return $res;
    }
    function getChatMember($chat_id, $user_id)
    {
        $data['chat_id'] = $chat_id;
        $data['user_id'] = $user_id;
        $res = $this->sendPost('getChatMember', $data);
        return $res['result']['user'];
    }
}
/*
"result":{"id":968407066,
            "first_name":"Alex",
            "last_name":"\ud83c\udf97",
            "username":"BlrAlex",
            "type":"private",
            "active_usernames":["BlrAlex"],
            "emoji_status_custom_emoji_id":"5418063924933173277",
            "bio":"\u041d\u0435\u043c\u043d\u043e\u0433\u043e \u0437\u043d\u0430\u044e \u043e \u0431\u043e\u0442\u0430\u0445",
            "photo":{"small_file_id":"AQADAgADsqcxGxq4uDkACAIAAxq4uDkABKHaFiJLtdCaLwQ",
                    "small_file_unique_id":"AQADsqcxGxq4uDkAAQ",
                    "big_file_id":   "AQADAgADsqcxGxq4uDkACAMAAxq4uDkABKHaFiJLtdCaLwQ",
                    "big_file_unique_id":"AQADsqcxGxq4uDkB"
                }
        }
        */