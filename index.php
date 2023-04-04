<?php
//testing
//echo '<h1>ModerTop_Bot</h1><br/>';
//https://api.telegram.org/bot6089243379:AAHUy2CEpMDH_a3kWATMxv4cU5iWkeA5FpU/setwebhook?url=https://bot.shinny-mir.by/index.php
$input = file_get_contents('php://input');
if(!$input)
{
  echo "<h1>Нет страницы для отображения</h1>";
 /* $new_url = 'https://bot.shinny-mir.by/findform.php';
header('Location: '.$new_url);*/
exit();
}
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
    $chat_id = $msg['chat']['id'];
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
        $bot->sendMes(MY_ID, "Пишет <b>$user->first_name $user->last_name</b> \nДата старта: $user->date");
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
                
                $text2 = "👋 Здравствуйте, <b>" . $tg_user['first_name'] . "!</b>\n\nЕсли вам нужна разрешительная документация на товары для продажи на маркетплейсе(-ах) или есть вопросы по сертификации продукции и услуг, значит вы по адресу.\n\nМы акредитованный орган по сертификации, поэтому стоимость выгодная, а сроки от 1 дня.\n\n👉 пишите @blondin_man для заказа и консультации, мы поможем.";
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
                $textAbout = "Центр сертификации «SertSale» является аккредитованным органом по сертификации, зарегистрированный в Федеральной Службе Аккредитации (ФСА) и предлагает полный комплекс услуг по оформлению разрешительной документации на Ваши товары:\n• Сертификация для маркетплейсов\n• Сертификация для РФ производителей\n• Сертификация для импортеров\n• Сертификация для экспортеров\n• Сертификаты Таможенного союза\n• Декларации Таможенного союза\n• Протоколы лабораторных испытаний\n• Отказные письма на продукцию\n• Регистрация торгового знака\n• Свидетельство о государственной регистрации\n• Экспертное Заключение Роспотребнадзора\n• Сертификация / декларация ТР ТС\n• Сертифткаты ISO\n• Сертификация РПО\n• Сертификация ГОСТ Р\n• Декларирование ГОСТ Р\n• Добровольная сертификация\n• Пожарная сертификация / декларация\n• Сертификация услуг\n• Разработка Технических Условий\n• Разработка Технологической Инструкции\n• Разработка Паспортов Безопасности\n• Штрихкодирование продукции\n• Экологическая Сертификация\n• Сертификация Халяль\n• Сертификат Кошерности\n• Сертификат Биологической безопасности\n• Сертифткат Органической безопасности\n• Сертификат отсутсвия ГМО\n• Сертификация происхождение товара СТ -1\nИ многие другие разрешительные документы🗂\n\n✅ Имеем собственную лабораторию в городе Москва!\n✅ Работаем по договору с указанием сроков и стоимости!\n✅ Сроки готовой документации от 1 рабочего дня!\n✅ Доставляем готовые документы по городам России!\n\n☎️ 8 800 707-40-97 (бесплатно по РФ)";
                $bot->sendKeyboard($chat_id, $textAbout, writeToExpertKeyboard());
                return;
              }
              if (($msg['text'] == ('/admin' . BOT_NAME)) || ($msg['text'] == '/admin'))
              { 
                if($user->is_admin == '1')
                {
                  $bot->sendKeyboard($chat_id, "Меню Администратора", adminMenu());
                  return;
                }
              }
            }// ~~~~~~~~конец обработки команд~~~~~~~
                //~~~ Конец проверки кнопок главного меню ~~~~~
        }//~~~ Конец работы с сущностями~~~~~~~~~
            //~~~ Проверка нажатия кнопок главного меню ~~~~~
            
            if ($mes_text == '🎪 Главное меню') {
                $bot->sendKeyboard($chat_id, "🎪 Главное меню 👇", mainMenuKeys());
                return;
            }
            if ($mes_text == '🔍 ПРОВЕРИТЬ ТОВАР') {
              //$new_url = 'https://sertbot.shinny-mir.by/pages/find-product.php';
              $bot->sendKeyboard($chat_id,"Расширенный поиск", inLineWebAppButton());
              
                //$bot->sendKeyboard($chat_id, "✏️ Введите товар для проверки:👇", secondMenuKyes());
                return;
            } 
            if ($mes_text == '🚀 О нас') {
                
               $textAbout = "Центр сертификации «SertSale» является аккредитованным органом по сертификации, зарегистрированный в Федеральной Службе Аккредитации (ФСА) и предлагает полный комплекс услуг по оформлению разрешительной документации на Ваши товары:\n• Сертификация для маркетплейсов\n• Сертификация для РФ производителей\n• Сертификация для импортеров\n• Сертификация для экспортеров\n• Сертификаты Таможенного союза\n• Декларации Таможенного союза\n• Протоколы лабораторных испытаний\n• Отказные письма на продукцию\n• Регистрация торгового знака\n• Свидетельство о государственной регистрации\n• Экспертное Заключение Роспотребнадзора\n• Сертификация / декларация ТР ТС\n• Сертифткаты ISO\n• Сертификация РПО\n• Сертификация ГОСТ Р\n• Декларирование ГОСТ Р\n• Добровольная сертификация\n• Пожарная сертификация / декларация\n• Сертификация услуг\n• Разработка Технических Условий\n• Разработка Технологической Инструкции\n• Разработка Паспортов Безопасности\n• Штрихкодирование продукции\n• Экологическая Сертификация\n• Сертификация Халяль\n• Сертификат Кошерности\n• Сертификат Биологической безопасности\n• Сертифткат Органической безопасности\n• Сертификат отсутсвия ГМО\n• Сертификация происхождение товара СТ -1\nИ многие другие разрешительные документы🗂\n\n✅ Имеем собственную лабораторию в городе Москва!\n✅ Работаем по договору с указанием сроков и стоимости!\n✅ Сроки готовой документации от 1 рабочего дня!\n✅ Доставляем готовые документы по городам России!\n\n☎️ 8 800 707-40-97 (бесплатно по РФ)";
               $bot->sendKeyboard($chat_id, $textAbout, writeToExpertKeyboard());
              return;
            }   
            if ($mes_text == '✏️ Задать вопрос') {
                $bot->sendKeyboard($chat_id, "✏️", secondMenuKyes());
                $bot->sendKeyboard($chat_id, "Написать эксперту: 👇", writeToExpertKeyboard());                
              return;
            }
            if ($mes_text == '🔗 Ссылки на наши ресурсы') {
                $bot->sendKeyboard($chat_id, '🔗', secondMenuKyes());
                $bot->sendKeyboard($chat_id, "Cсылки 👇", linksMenu());
              return;
            }
            if ($mes_text == '📞 Связаться с нами') {
                $bot->sendKeyboard($chat_id, "Наши контакты: 👇", secondMenuKyes());
                $text = "☎️ 8 800 707-40-97 (бесплатно по РФ)\n\nТелеграм-чат https://t.me/SertSale";
                $bot->sendMes($chat_id, $text);
              return;
            }
        
    }else{return;}//~~~~ Конец работы в приватном чате ~~~~~~~
}// ~~~~~ End обработки апдейта типа message ~~~~~~
echo "<h1>Нет страницы для отображения</h1>";


//~~~~~~~FUNCTIONS~~~~~~~~~~~~~~~~~~
