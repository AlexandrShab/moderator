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

$db = new BaseAPI;
$baned_user = $db->getBanedUser('968407066');
echo "<pre>";
var_dump($baned_user);
