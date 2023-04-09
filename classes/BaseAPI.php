<?php
require_once __DIR__ . '/Connect.php';
class BaseAPI
{
    public function updateChatList($chat)
    {
        $id = $chat['id'];
        $title = $chat['title'];
        $username = $chat['username'];
        $type = $chat['type'];
        $db = new Connect;
        $query = "INSERT INTO chats (id, title, username, type) VALUES ('$id', '$title', '$username', '$type') 
            ON DUPLICATE KEY UPDATE title = bad, username = '$username', type = '$type';";

        $data = $db->prepare($query);
        $data->execute();
        
        return true;
    }
    public static function getToken()
    {
        $base = new Connect;
        $query = "SELECT value FROM vars WHERE name='token'";
        $data = $base->prepare($query);
        $data->execute();
        $arrUsers = array();
        $token = $data->fetch(PDO::FETCH_ASSOC);
        return $token['value'];   
    }
    public function getUsers()
    {
        $base = new Connect;
        $query = "SELECT * FROM users";
        $data = $base->prepare($query);
        $data->execute();
        $arrUsers = array();
        while($user = $data->fetch(PDO::FETCH_OBJ))
        {
            $arrUsers[] = $user;
        }
        return $arrUsers;
    }
    public function getUser($id)
    {
        $db = new Connect;
        $user = array();
        $data = $db->prepare("SELECT * FROM users WHERE id ='$id' LIMIT 1");
        $data->execute();
        $user = $data->fetch(PDO::FETCH_ASSOC);
        
        return $user; 
    }
    public function addUser($user)
    {
        $db = new Connect;
        $id = $user["id"] ;
        $first_name = $user["first_name"];
        $last_name = $user["last_name"];
        $username = $user["username"];
        $query = "INSERT INTO users (id, first_name, last_name, username) VALUES ('$id', '$first_name', '$last_name', '$username');";
        $data = $db->prepare($query);
        $data->execute();
        return true;
    }
    public function addProduct($prod, $doc_type)
    {
         $db = new Connect;
         $query = "INSERT INTO products (name, doc_type) VALUES ('$prod', '$doc_type');";
        $data = $db->prepare($query);
        $data->execute();
        return true;
    }
    public function getAllProducts()
    {
        $base = new Connect;
        $query = "SELECT * FROM products";
        $data = $base->prepare($query);
        $data->execute();
        $products = array();
        while($product = $data->fetch(PDO::FETCH_OBJ))
        {
            $products[] = $product;
        }
        return $products;
    }
    public function findProd($sample)
    {
        $base = new Connect;
        $query = "SELECT * FROM products WHERE name LIKE '%$sample%'";
        $data = $base->prepare($query);
        $data->execute();
        $products = array();
        while($product = $data->fetch(PDO::FETCH_OBJ))
        {
            $products[] = $product;
        }
        return $products;
    }
    public function storeMessage($text, $user_id,  $mes_id)
    {
        $base = new Connect;
        $query = "INSERT INTO users_chats (user_id, message_id, text) VALUES ('$user_id', '$mes_id', '$text');";
        $data = $base->prepare($query);
        $res = $data->execute();
        return $res;
    }
   
    public function getPrivateMessages()
    {
        $base = new Connect;
        $query = "SELECT * FROM users_chats ORDER BY date DESC;";
        $data = $base->prepare($query);
        $data->execute();
        $res = array();
        $i = 0;
        while($req = $data->fetch(PDO::FETCH_OBJ))
        {
            $res[$i] = $req;
            $i++;
        }
        
        return $res;
    }
}   
