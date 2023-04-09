<?php
  $input = file_get_contents('php://input');
if(!$input)
{
  echo "<h1>Нет страницы для отображения</h1>";
exit();
}
 //define('TELEGA_URL', 'https://api.telegram.org/bot' . TOKEN);
  define('MY_ID','968407066');
  define('BOT_GROUP', '-973581514');   //Bot_privateMessages
  define('ADMINS_GROUP', '-815687936');   //info From Bots  
  define('WORK_GROUP', '-887008436'); //рабочая группа (тестовая)
  define('BOT_NAME','@Moder_TopBot');
  
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . "/functions/work.php";
$update = json_decode($input, TRUE);
file_put_contents('update.txt', '$update: ' .print_r($update,1)); 

//var_dump($data);

$bot = new TBot;

/*
if (isset($update['update_id']))
{
    $text = 'Пришел новый update!' . json_encode($update);//
    $mes_id = $bot->sendMes(MY_ID, $text);
}*/
//~~~~~~~~~~~ Начало обработки апдейта типа message ~~~~~~
if(isset($update['message']))
{
    $msg = $update['message'];
    $tg_user = $msg["from"];
    $chat = $msg['chat'];
    $chat_id = $msg["from"]['id'];
    $chat_type = $msg['chat']['type'];
    $chat_title = isset($msg['chat']['title']) ? $msg['chat']['title'] : $tg_user['first_name'] . ' ' . $tg_user['last_name'];
    $message_id = $msg['message_id'];
    $mes_text = $msg['text'];
    if (isset($msg['caption'])){
        $mes_text = $msg['caption'];
    }
    $mesHasEntities = false;
    $alarmText = 'Сообщение содержит контент';
    //$menuButton = mb_substr($mes_text, 0, 1);

    if (($msg['text'] == ('/getChat' . BOT_NAME)) || ($msg['text'] == '/getChat'))
              { 
                  $res = $bot->getChat($chat_id);
                  $bot->sendMes(MY_ID, json_encode($res));
                 
              }
    if(isset($msg['web_app_data']))//~~~ Проверяем приход данных из WebApp ~~~~
    {
        $bot->sendMes(MY_ID, 'button_text:' . $msg['web_app_data']['button_text'] . '\n' . 'data:\n' . $msg['web_app_data']['data']);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~Обработка Команд Боту~~~~~~~~~~~~~~~~~~~~~~~~
    if (isset($msg['entities']) && $chatType == 'private'){
      
      if ( $msg['entities'][0]['type'] == 'bot_command')// Если сообщение - команда боту
      {    
            $bot->sendAction($chat_id);
            /*
            var count = varSheet.getRange(2,2).getValue(); // счетчик вызова команд
                count = count + 1;
                varSheet.getRange(2,2).setValue(count);*/
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       /* if (msg.text == ('/getchat'+botName) || msg.text == '/getchat'){ // Получение данных о чате
          sendMess(myId,chatId+'\n'+chatName+'\n'+chatType);
          getChat(chatId,chatName,chatType);return;     
        }*/
        
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        if (($msg['text'] == ('/start' . BOT_NAME)) || ($msg['text'] == '/start'))
        { 
          
          $text2 = "👋 Здравствуйте, <b>" . $tg_user['first_name'] . "!</b>\n\n@Moder_TopBot - помощник в управлении группой.\n\n👉 Если вам ограничили отправку сообщений, - пишите @AlexanderShab.";
          $bot->sendKeyboard($chat_id, $text2, writeToExpertKeyboard());
          return;
        }
        if (($msg['text'] == ('/getChat' . BOT_NAME)) || ($msg['text'] == '/getChat'))
        { 
            $res = $bot->getChat($chat_id);
            $bot->sendMes(MY_ID, json_encode($res));
           return;
        }
        if (($msg['text'] == ('/help' . BOT_NAME)) || ($msg['text'] == '/help'))
        { 
          
          $hi = goodTime();
          $bot->sendMes($chat_id, $hi . ", <b>" . $user->first_name . "</b>");
          $textAbout = "Модератор удаляет из группового чата сообщения, содержащие рекламу, нецензурные и оскорбительные выражения.\nЕсли Вам была ограничена возможность отправки сообщений в группу, - пишите Администратору Бота @AlexanderShab";
          $bot->sendKeyboard($chat_id, $textAbout, writeToExpertKeyboard());
          return;
        }
        if (($msg['text'] == ('/admin' . BOT_NAME)) || ($msg['text'] == '/admin'))
        { 
          if($user->is_admin == '1')
          {
            $bot->sendMes($chat_id, $hi . ", <b>" . $user->first_name . "</b>");
            $bot->sendKeyboard($chat_id, "Сайт Администратора", adminMenu());
            return;
          }else 
          {
            $bot->sendMes($chat_id, '<b>' . $tg_user['first_name'] . '</b>, Вас нет в списке Администраторов!!!');
            return;
          }
        }
      }// ~~~~~~~~конец обработки команд~~~~~~~
       
  }//~~~ Конец работы с сущностями~~~~~~~~~
    //~~~~~~~  Проверяем чат~~~~~~~~~
    if ($chat_type != 'private') //Если чат не личка с ботом
    {
        
        $db = new BaseAPI;
        $db->updateChatList($chat);

        if (isset($msg['entities']) || isset($msg['caption_entities']))
        {
          
          $mesHasEntities = true;
          $alarmText = ', похожий на рекламу';
        }
                  
    }//конец обработки неприватных чатов
    //~~~~~~~~~~~chat checked~~~~~~~~~~~~~~~~~~~~
    if ($chat_type == 'private')// Работаем в личке с ботом
    {   //~~~~~~ Работаем с Юзером и базой ~~~
        $base = new BaseAPI;
        $userFromBase = $base->getUser($tg_user['id']);
        if ($userFromBase == false)
        {
           $base->addUser($tg_user);
           $userFromBase = $base->getUser($tg_user['id']);
        }
        
       
        $user = new User($userFromBase);
        $user->update($tg_user);
        $base->storeMessage($mes_text, $user->id, $message_id);//Сохраняем в базу текст пользователя
        $name_as_link = $user->getNameAsTgLink();
        $user_id = $user->id;
        $bot->sendMes(BOT_GROUP, "Боту пишет <b>$name_as_link</b> ID: $user_id");
        $bot->forwardMessage($chat_id, $message_id);
        
        
        if (hasHello($mes_text))
        {
            $hi = goodTime();
            $bot->sendMes($chat_id, "👋 " . $hi . ", <b>" . $user->first_name . "</b>\n\nМодератор предназначен для работы в групповых чатах.");
        }
        
           
        
    }//~~~~ Конец работы в приватном чате ~~~~~~~

}// ~~~~~ End обработки апдейта типа message ~~~~~~
echo "<h1>Нет страницы для отображения</h1>";


//~~~~~~~FUNCTIONS~~~~~~~~~~~~~~~~~~
