<?php
//header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/autoload.php';
//var_dump($_COOKIE);

define('BOT_USERNAME', 'Moder_TopBot'); // place username of your bot here

if (isset($_GET['logout'])) {
  setcookie('tg_user', '');
  header('Location: admin-serv.php');
}

//require_once __DIR__ . '/pages/header.php';

$tg_user = getTelegramUserData();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>SertSale</title>
    <style>
        body {
            font-family: "Playfair Display", Roboto, Helvetica, Arial, sans-serif;
        }
        .main-title {
            font-family: "Playfair Display", Roboto, Helvetica, Arial, sans-serif;
            font-size: 1.2rem;
            line-height: 1.133;
            font-weight: 600;
            letter-spacing: .02em;
            display: block;
            text-align: center;
            margin: 20px auto;
            width: 90%;
            max-width: 960px;
            min-width: 300px;
            
        }
        .brand{
            font-family: "Playfair Display", Roboto, Helvetica, Arial, sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: 0.03em;
            display: grid;
            text-align: center;
            margin: 0 auto;
            width: 100%;
            height:100px;
            color: white;
            background: black;
            min-width: 330px;
            align-items: center;
        } 
        #content {
            margin:0 auto;
            height: 100%;
            /*border: solid black 1px;*/
        }
        #btn {
            justify-self: end;
            height: 40px;
            padding: 10px;
            border-radius: 5px;
        }
        
        .input-item {
            width:70%;
            height: 20px;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            text-align: center;
            border: solid gray 1px;
        }
        thead {
            background:lightgray;
            border: solid gray 1px;
        }
        td, th{
            padding: 5px;
            border: solid gray 1px;
        }
        .admin-menu {
            text-align: center;
            position: fixed;
            background-color: rgb(255,255,255);
            width: max-content;
            box-shadow: 10px 5px 20px gray;
            
        }
        .menu-item {
            padding: 20px;
        }
        .menu-item:hover, .menu-item:active {
            background-color: rgb(200,200,200);
        }
        .menu-item a{
            text-decoration:none;
            color:black;
        }
    </style>
    
</head>
<body>
<?php
 
    $html = "<span></span>";
       
    
    //~~~~~~~~~
    if ($tg_user !== false) {
        if ($tg_user->is_admin == '1'){$isAdmin = true;}//~~~~~~~~~Check isAdmin~~~~~~~~~~~~~~~~~~~~~~
        //var_dump($tg_user);
      $first_name = htmlspecialchars($tg_user->first_name);
      //$html .= "<name style=\"float:right;\">{$first_name}</name>";
      
        $html .= "<a href=\"/admin-serv.php?logout=1\" 
            class=\"dropdown\" style=\"float:right;\">
            <img src=\"/public/img/door1.jpg\"
                style=\"height:32px;margin-right:10px;\">
            </a>";
        
     if (isset($tg_user->photo_url)) {
        $photo_url = htmlspecialchars($tg_user->photo_url);
        //$photo_url = $tg_user['photo_url'];
        $html .= "<img src=\"{$photo_url}\" style=\"width:32px; 
                border-radius:16px;float:right;margin-right:10px;\">";
        
      }else
          {
              $html .= "<name style=\"float:right;margin:10px;
                    color:black;\">{$first_name}</name>";
          }
        $html .= "<a style=\"float:left;\" href=\"https:/t.me/AlexanderShab\">Support</a>";
       print_r($html); 
    }else {
        $bot_username = BOT_USERNAME;
        $authItem = new AuthItem;
        $html = $authItem->content;
        $html .= "<a style=\"float:left;\" href=\"https:/t.me/AlexanderShab\">Support</a>";
        print_r($html);
    }
    
?>
    <div class="brand" style="backgroundColor: black;">
        <text>Moder_TopBot</text>
    </div>
    
<?php
    if (isset($isAdmin) && $isAdmin)
   {
?>
    <div id="content">
        <p1 class="main-title">Страница администратора</p1>
        <div id="burger" style="visibility:visible;">
            <button id="btn" style="margin: 20px"
            onclick="showMenu()">Меню Администратора</button>
       </div>
       <div id="admin-menu" class="admin-menu" style="visibility:hidden;">
            <div class="menu-item">
                <a href="https://bot.shinny-mir.by/admin-serv.php?method=getUsers"><text>Пользователи бота</text></a>
            </div>
            <div class="menu-item">
                <a href="https://bot.shinny-mir.by/admin-serv.php?method=getAdmins"><text>Администраторы бота</text></a>
              
            </div>
            <div class="menu-item">
                <a href="https://bot.shinny-mir.by/admin-serv.php?method=getRequests">
                <text>Запросы по поиску</text></a>
            </div>
            <div class="menu-item">
                <a href="https://bot.shinny-mir.by/admin-serv.php?method=getActions">
                <text>Акции и участники</text></a>
            </div>
            <div class="menu-item">
                <a href="https://bot.shinny-mir.by/admin-serv.php?method=getProducts">
                <text>Редактор товаров базы</text></a>
            </div>
       </div>
       <script>
        function showMenu(){
           let menuState = document.getElementById('admin-menu').style.visibility;
           if (menuState == 'hidden'){
            menuState = 'visible';
           } else{menuState ='hidden'}
           document.getElementById('admin-menu').style.visibility = menuState;
        }
       </script>
<?php
    }else
    {
?>
    <div id="content">
        <p1 class="main-title">Вам необходимо авторизоваться, либо у вас недостаточно прав...</p1><br/><br/>

    
<?php
    exit;        
    }

// ~~~~~~~~~~ Начало контента страницы~~~~~~~~~~~~~~~~~~~~~

//~~~~~~~~~~~~~
if (isset($_GET['method']))
{ 
    if($_GET['method'] == 'getUsers' || $_GET['method'] == 'getAdmins') 
    {
        $base = new BaseAPI;
        $users = $base->getUsers();
        
        
        $output = "<div id=\"list\">
        <table>
            <thead>
                <th>
                    №
                </th>
                <th>
                    ID
                </th>
                <th>
                    Имя пользователя
                </th>
                <th>
                    Юзернейм
                </th>
                <th>
                    Дата старта
                </th>
                <th>
                    Админ?
                </th>
                <th>
                    Написать от имени бота
                </th>
            </thead>
            <tbody>";
        $num = 0;        
        foreach ($users as $user) {
            $num ++;
            $adm = "⚠️";
            $set = '1';
            if($user->is_admin == '1')
            {
                $adm = "✅";
                $set = '0';
            }
            if($_GET['method'] == 'getAdmins' && $user->is_admin == '0') continue;
            $user_name = $user->first_name . '+' . $user->last_name;
            $output .= 
                "<tr>
                    <td>
                        $num
                    </td>
                    <td>
                        $user->id
                    </td>
                    <td>
                        $user->first_name $user->last_name
                    </td>
                    <td>
                        $user->username
                    </td>
                    <td>
                        $user->date
                    </td>
                    <td onclick=\"admin($user->id, $set)\">
                        <button style=\"width:90%;height:90%\">$adm 🔄</button>
                        
                    </td>
                    <td onclick=\"sendMesage($user->id,'$user_name')\">
                        <button style=\"width:90%;height:90%\">Написать</button>
                    </td>
                </tr>";
            }
            $output .= "</tbody>
                </table>
            </div>";
            print_r($output);
            
        
       
    }
    if($_GET['method'] == 'admin') 
    {
        //var_dump($_GET);
        $id = $_GET['user_id'];
        $admin_state = $_GET['set'];
        $arrUser_edit = ['id'=>$id];
        $user_edit = new User($arrUser_edit);
        $user_edit->setAdmin($admin_state);
        echo "<script>document.location.href='/admin-serv.php?method=getAdmins';</script>";
    }
    if($_GET['method'] == 'sendMessage') 
    {
        //var_dump($_GET);
        $chat_id = $_GET['chat_id'];
        $name = $_GET['name'];
        $output = "<h3>Отправка сообщения  от имени бота для <em>$name</em></h3>
        <form class=\"admin-form\" method=\"POST\" action=\"/admin-serv.php\">
            <input hidden name=\"method\" value=\"send\">
            <input hidden name=\"chat_id\" value=\"$chat_id\">
            <input hidden name=\"name\" value=\"$name\">
            <input class=\"input-item\" type=\"text\" name=\"text\" value=\"$name, \" required><br/>
            <button class=\"btn\" type=\"reset\">Очистить</button>
            <button class=\"btn\" type=\"submit\">Отправить</button>
        </form>";
        print_r($output);
        exit;
    }
    
}

if (isset($_POST['method']))
{    
    if($_POST['method'] == 'send') 
        {
            //var_dump($_POST);
            $chat_id = $_POST['chat_id'];
            $text = $_POST['text'];
            $name = $_POST['name'];
            $bot = new TBot;
            $bot->sendMes($chat_id, $text);
            echo "<h1>Сообщение для <em>$name</em> oтправлено...</h1>$text";
        }
}
//~~~~~~~~~~~~~


//exit;

function getTelegramUserData() {
    if (isset($_COOKIE['tg_user'])) {
      
      $auth_data_json = urldecode($_COOKIE['tg_user']);
      //print_r($auth_data_json);
      $auth_data = json_decode($auth_data_json, true);
      $user = new User($auth_data);
      /*if(!$user->isInBase())
      {
          
          $user->addTobase();
      }*/
      $user->checkAdmin();
        
      return $user;
    }
    return false;
  }
  ?>
        
     </div><!-- конец класса content-->
     <script>
        function sendMesage(user_id, name){
            let link = "https://bot.shinny-mir.by/admin-serv.php?method=sendMessage&chat_id=" + user_id + "&name=" + name;
            document.location.href=link;
        }
        function admin(user_id, adm){
            let link = "https://bot.shinny-mir.by/admin-serv.php?method=admin&user_id="+user_id+"&set=" + adm;
            document.location.href=link;
        }
     </script>   
    
</body>
</html>