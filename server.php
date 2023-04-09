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


$badWords = ["херасе", " хер ", " хер", "нахер", "похер", "ублюдки","ублюдок", "6лядь", "6лять", "b3ъeб", "cock", "cunt", "e6aль", "ebal", "eblan", "eбaл", "eбaть", "eбyч", "eбёт", "eблантий", "fuck", "fucker", "fucking", "xyёв", "хyй","xyй","хуй", "xyя", "xуе","xуй", "xую", "zaeb", "zaebal", "zaebali", "zaebat", "архипиздрит", "ахуел", "ахуеть", "бздение", "бздеть", "бздех", "бздецы", "бздит", "бздицы", "бздло", "бзднуть", "бздун", "бздунья", "бздюха", "бздюшка", "бздюшко", "бля", "блябу", "блябуду", "бляд", "блять", "вафел", "вафлёр", "взъебка", "взьебка", "взьебывать", "въеб", "въебался", "въебенн", "въебусь", "въебывать", "выеб", "выссаться", "вьебен", "гавно", "гавнюк", "гавнючка", "гамно", "гандон", "гнид", "говенка", "говенный", "говешка", "говназия", "говнецо", "говнище", "говно", "говнюк", "говнюха", "говнядина", "говняк", "говняный", "говнять", "гондон", "доебываться", "долбоеб", "долбоёб", "долбоящер", "дрисня", "дрист", "дристануть", "дристать", "дристун", "дристуха", "дрочелло", "дрочена", "дрочил", "дрочистый", "дрочка", "дрочун", "е6ал", "е6ут", "еб твою мать", "ёб твою мать", "ёбaн", "ебaть", "ебyч", "ебал", "ебало", "ебальник", "ебан", "ёбаная", "ёбаную", "ебарь", "ебат", "ёбат", "ебатория", "ебать", "ебашить", "ебёна", "ебет", "ебёт", "ебец", "ебик", "ебин", "ебись", "ебическая", "ебки", "ебла", "еблан", "ебливый", "еблище", "ебло", "еблыст", "ебля", "ёбн", "ебнуть", "ебнуться", "ебня", "ебошить", "ебская", "ебский", "ебтвоюмать", "ебун", "ебут", "ебуч", "ебущ", "ебырь", "елдак", "елдачить", "жопа", "жопу", "заговнять", "задрачивать", "задристать", "задрота", "зае6", "заё6", "заеб", "заёб", "заеба", "заебал", "заебастая", "заебастый", "заебашить", "заебистое", "заёбистое", "заебистые", "заёбистые", "заебистый", "заёбистый", "заебись", "заебошить", "заебываться", "залуп", "залупа", "залупаться", "залупить", "залупиться", "замудохаться", "запиздячить", "засерать", "засерун", "засеря", "засирать", "засрун", "захуячить", "заябестая", "злоеб", "ибанамат", "ибонех", "изговнять", "изговняться", "изъебнуться", "ипать", "ипаться", "ипаццо", "Какдвапальцаобоссать", "конча ", " конча", "курва", "курвятник", "лох", "лошарa", "лошара", "лошары", "лошок", "лярва", "малафья", "манда", "мандавошек", "мандавошка", "мандавошки", "мандей", "мандень", "мандеть", "мандища", "мандой", "манду", "мандюк", "минет", "мокрощелка", "мокрощёлка", "мразь", "мудak", "мудaк", "мудаг", "мудак", "муде", "мудель", "мудеть", "муди", "мудил", "мудила", "мудистый", "мудня", "мудоеб", "мудозвон", "мудоклюй", "на хер", "на хуй", "пнх", "набздел", "набздеть", "наговнять", "надристать", "надрочить", "наебать", "наебет", "наебнуть", "наебнуться", "наебывать", "напиздел", "напиздели", "напиздело", "напиздили", "нахер", "нахрен", "нахуй", "нахуйник", "невротебучий", "невъебенно", "нехира", "нехрен", "нехуй", "нехуйственно", "ниибацо", "ниипацца", "ниипаццо", "ниипет", "никуя", "нихера", "нихуя", "обдристаться", "обосранец", "обосцать", "обосцаться", "обсирать", "объебос", "обьебать обьебос", "однохуйственно", "опездал", "опизде", "опизденивающе", "остоебенить", "остопиздеть", "мудохать", "пиздячить", "отпороть", "отъебись", "охуевательский", "охуевать", "охуевающий", "охуел", "охуенно", "охуеньчик", "охуеть", "охуительно", "охуительный", "охуяньчик", "охуячивать", "охуячить", "очкун", "падла", "падонки", "падонок", "паскуда", "педерас", "педик", "педрик", "педрила", "педрилло", "педрило", "педрилы", "пездень", "пездит", "пездишь", "пездо", "пездят", "пердануть", "пердеж", "пердение", "пердеть", "пердильник", "перднуть", "пёрднуть", "пердун", "пердуха", "пердь", "переёбок", "пернуть", "пёрнуть", "пи3д", "пи3де", "пи3ду", "пиzдец", "пидар", "пидарaс", "пидарас", "пидарасы", "пидары", "пидор", "пидорасы", "пидорка", "пидорок", "пидоры", "пидрас", "пизда", "пизденка", "пизденыш", "пиздёныш", "пиздеть", "пиздец", "пиздит", "пиздить", "пиздишь", "пиздища", "пиздище", "пиздобол", "пиздоболы", "пиздобратия", "пиздоватая", "пиздоватый", "пиздолиз", "пиздонутые", "пиздорванец", "пиздорванка", "пиздострадатель", "пизду", "пиздуй", "пиздун", "пиздунья", "пизды", "пиздюга", "пиздюк", "пиздюлина", "пиздюля", "пиздят", "пиздячить", "писбшки", "писька", "писькострадатель", "писюн", "писюшка", "по хуй", "по хую", "подговнять", "подонки", "подонок", "подъебнуть", "подъебнуться", "поебать", "поебень", "поёбываает", "поскуда", "потаскуха", "потаскушка", "похер", "похеру", "похрен", "похрену", "похуй", "похуист", "похуистка", "похую", "придурок", "приебаться", "припизд", "пробзд", "проблядь", "проеб", "проебать", "промандеть", "промудеть", "пропизделся", "пропиздеть", "пропиздячить", "раздолбай", "разхуячить", "разъеб", "разъеба", "разъебай", "разъебать", "распиздяй", "распиздяйство", "распроеть", "сволота", "сволочь", "сговнять", "секель", "серун", "серька", "сестроеб", "сикель", "сирать", "сирывать", "соси", "спиздел", "спиздеть", "спиздил", "спиздила", "спиздили", "срака", "сраку", "сраный", "сранье", "срать", "срун", "ссака", "ссышь", "стерва", "страхопиздище", "сука", "суки", "суходрочка", "сучара", "сучий", "сучка", "сучко", "сучонок", "сучье", "сцание", "сцать", "сцука", "сцуки", "сцуконах", "сцуль", "сцыха", "сцыш", "съебаться", "сыкун", "трахае6", "трахаеб", "трахаёб", "трахатель", "ублюдок", "уебать", "уёбища", "уебище", "уёбище", "уебищное", "уёбищное", "уебк", "уебки", "уёбки", "уебок", "уёбок","млн","тыс","меня зовут","работу","зарабат", "заработ","помогу","поможем","приглаш","закреп", "помогли","аренд", "bot", "расскажем", "расскажу", "развитие", "инфограф", "посоветуем", "разместим", "размещайте", "фулфил", "доставим", "страхуем"];
$words = json_encode($badWords);
var_dump($words);
