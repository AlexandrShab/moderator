<?php
  require_once __DIR__ . '/autoload.php';
  if(isset($_GET['method'])) 
  {
    if( $_GET['method'] == 'getChats')//Получение списка поисковых запросов из ВебАпп
    {
        $base = new BaseAPI;
        $chats = $base->getChatList();
        $chats = json_encode($chats);
        
        header('Content-Type: application/json');
        print_r($chats);
        exit;
    }
    if( $_GET['method'] == 'getMessages')//Получение списка сообщений в личку боту
    {
        $base = new BaseAPI;
        $messages = $base->getPrivateMessages();
        $messages = json_encode($messages);
        
        header('Content-Type: application/json');
        print_r($messages);
        exit;
    }
  }

  $base = new BaseAPI;
  $userFromBase = $base->getUser('968407066');
  if ($userFromBase == false)
  {
     $base->addUser($tg_user);
     $userFromBase = $base->getUser($tg_user['id']);
  }
  
 
  $user = new User($userFromBase);
  $user->update($tg_user);
  $base->storeMessage($mes_text, $user->id, $message_id);//Сохраняем в базу текст пользователя
  $name_as_link = $user->getNameAsTgLink();
  $user_id = $user->id
  $bot->sendMes(BOT_GROUP, "Боту пишет <b>$name_as_link</b> ID: $user_id");
  $bot->sendMes(MY_ID, $mes_text);  




