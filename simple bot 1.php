<?php

define('API_KEY', "7457376878:AAGukVTUqhNDkoNNRi_v2eaFu-TQWAm0sg0");

$manager = "862935999";
$compane = "movies.uz";
// https://api.telegram.org/bot7457376878:AAGukVTUqhNDkoNNRi_v2eaFu-TQWAm0sg0/setWebhook?url=https://dilshodbekbro.uz/movie_bot.php
function bot($method, $datas = []){
    $url = "https://api.telegram.org/bot".API_KEY."/" . $method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    curl_close($ch);
    if (!curl_error($ch)) return json_decode($res);
}

function html($text){
    return str_replace(['<', '>'] , ['&#60;', '&#62'], $text);
}

$update = json_decode(file_get_contents('php://input'));

// file_put_contents("log.txt", file_get_contents('php://input'));


// message variables
$message = $update->message;
$text = html($message->text);
$chat_id = $message->chat->id;
$from_id = $message->from->id;
$message_id = $message->message_id;
$first_name = $message->from->$first_name;
$last_name = $message->from->$last_name;
$full_name = html($first_name." ".$last_name);

// replymessage

$reply_to_message = $message->reply_to_message;
$reply_chat_id = $message->reply_to_message->forward_from->id;
$reply_text = $message->text;


if ($chat_id != $manager) {
    if($text == "/start" ){
        $reply = "Assalomu aleykum <b>" . $full_name . "</b>" . $compane . "Qabul botiga hush kelibsiz !\n Murojat yo'llashingiz mumkin 👇";
    bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => $reply,
        'parse_mode' => "HTML",
    ]);

    $reply = "Yangi mijoz : \n" . $full_name . "\n 👉👉 <a href='tg:user?id=". $from_id."'>" . $from_id . "</a> \n" . date('Y-m-d H:i:s');
    bot('sendmessage', [
        'chat_id' => $manager,
        'text' => $reply,
        'parse_mode' => "HTML",
    ]);
    bot('forwardMessage', [
        'chat_id' => $manager,
        'from_chat_id' => $chat_id,
        'message_id' => $message_id,
    ]);
    }elseif($text != "/start"){
        bot('forwardMessage', [
            'chat_id' => $manager,
            'from_chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
    }
}elseif($chat_id == $manager){
    if(isset($reply_to_message)){
        bot('sendmessage', [
            'chat_id' => $reply_chat_id,
            'text' => $reply_text,
            'parse_mode' => "HTML",
        ]);
    }
    if($text == 'hi' or $text == "/start"){
        bot('sendmessage', [
            'chat_id' => $manager,
            'text' => "Xush kelibsiz hurmatli boshliq",
            'parse_mode' => "HTML",
        ]);
    }
}
