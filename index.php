<?php
  $input = file_get_contents('php://input');
if(!$input)
{
  echo "<h1>Нет страницы для отображения</h1>";
exit();
}
 //define('TELEGA_URL', 'https://api.telegram.org/bot' . TOKEN);
  define('MY_ID','968407066');
  define('BOT_GROUP', '-1001523457115');   //SertBot_privateMessages
  define('ADMINS_GROUP', '-1001630215811');   //Инфа от SertSale ботов
  define('BOT_NAME','@Moder_TopBot');
  
require_once __DIR__ . '/autoload.php';

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
    $chat_id = ['id'];
    $chat_type = $msg['chat']['type'];
    $chat_title = isset($msg['chat']['title']) ? $msg['chat']['title'] : $tg_user['first_name'] . ' ' . $tg_user['last_name'];
    $message_id = $msg['message_id'];
    $mes_text = $update['message']['text'];
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
    //~~~~~~~  Проверяем чат~~~~~~~~~
    if ($chat_type != 'private') //Если чат не личка с ботом
    {
        $db = new BaseAPI;
        $db->updateChatList($chat);
                  
    }
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
        $bot->sendMes(MY_ID, "Пишет <b>$user->first_name $user->last_name</b> \nДата старта: $user->date\nAdmin? - $user->is_admin");
        $bot->sendMes(MY_ID, $mes_text);
        require_once __DIR__ . "/functions/work.php";
       
        if (hasHello($mes_text))
        {
            $hi = goodTime();
            
            $bot->sendMes($chat_id, "👋 " . $hi . ", <b>" . $user->first_name . "</b>\n\nМодератор предназначен для работы в групповых чатах.");
        }
        if (isset($msg['entities'])){
            //~~~~~~~~~~~~~~~~~~~~~~~~~Обработка Команд Боту~~~~~~~~~~~~~~~~~~~~~~~~
            if ( $msg['entities'][0]['type'] == 'bot_command')
            {    // Если сообщение - команда боту
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
              if (($mes_text == '/menu'. BOT_NAME) || ($mes_text == '/menu')){ 
                    $bot->sendKeyboard($chat_id, '🎪 Главное меню 👇', mainMenuKeys());
                return;
              } 
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
                  $bot->sendKeyboard($chat_id, "Меню Администратора", adminMenu());
                  return;
                }
              }
            }// ~~~~~~~~конец обработки команд~~~~~~~
                //~~~ Конец проверки кнопок главного меню ~~~~~
        }//~~~ Конец работы с сущностями~~~~~~~~~
           
        
    }//~~~~ Конец работы в приватном чате ~~~~~~~

}// ~~~~~ End обработки апдейта типа message ~~~~~~
echo "<h1>Нет страницы для отображения</h1>";


//~~~~~~~FUNCTIONS~~~~~~~~~~~~~~~~~~
