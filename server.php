<?php
  require_once __DIR__ . '/autoload.php';
  if(isset($_GET['method'])) 
  {
    if( $_GET['method'] == 'getRequests')//Получение списка поисковых запросов из ВебАпп
    {
        $base = new BaseAPI;
        $requests = $base->getRequests();
        $requests = json_encode($requests);
        
        header('Content-Type: application/json');
        print_r($requests);
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




