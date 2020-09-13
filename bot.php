<?php

define('TOKEN', 'bot_token');

function bot($method, $datas=[]){
    $url = 'https://api.telegram.org/bot'.TOKEN.'/'.$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    curl_setopt($ch, CURLOPT_HEADER, false);

   $res = curl_exec($ch);

    if(curl_error($res)){
        var_dump(curl_error($ch));
    }
    else{
       return  json_decode($res);
    }
}

function typing($ch){
    return bot('sendChatAction', [
        'chat_id' => $ch,
        'action' => 'typing'
    ]);
}
$admin = 763563100;
$updates = json_decode(file_get_contents('php://input'));
$message = $updates->message;
$chat_id = $message->chat->id;
$from_chat_id = $message->from->id;
$mid = $message->message_id;
$reply_forward_id = $message->reply_to_message->forward_from->id;
$forward_id = $message->forward_from->id;
$reply_forwrad_message_id = $message->reply_to_message->message_id;
$text = $message->text;
$name = $message->username;
$first_name = $message->chat->first_name;
$phone = $message->contact->phone_number;
$user_name = $message->chat->username;
$reply = $message->reply_to_message->text;
$phone = $message->contact->phone_number;
$photo = $message->photo[1]->file_id;
$document = $message->document->file_id;
$audio = $message->audio->file_id;
$voice = $message->voice->file_id;
$keys = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [['text'=>'Men haqimda ✍🏻'], ['text' => 'Manzil 🔍']],
        [ ['text' => 'Xabar jo`natish 📤'], ['text' => 'Tel raqam 📞', 'request_contact' => true]]
    ]
]);
$cansel = json_encode([
    'resize_keyboard'=>true,
    'keyboard' => [
        [
            ['text'=>'Ortga ⬅️']
        ]
    ]
]);


$force = json_encode([
    'force_reply' => true,
    'selective' => true    
    ]);

if(isset($text)){
    typing($chat_id);
}
if($text == '/start'){
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => 'Assalomu aleykum '.$first_name,
        'parse_mode' => 'markdown',
        'reply_markup'=>$keys
    ]);
}

if($text == 'Men haqimda ✍🏻'){
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Mening ismim Baxtiyor hozrda TATUUF talabasiman 👨🏻‍🎓. \n Programming menga hobbi desa ham bo'ladi💻\n[Instagram 🔺](https://instagram.com/baxt1yor_)\n\n [Facebook 🔹](https://facebook.com/baxtiyor.eshametov)\n Email 📧 baxtiyoreshametov@yandex.ru \n [Batafsil](https://bit.ly/3bfrvPI)",
        'parse_mode' => 'markdown',
        'reply_markup'=> $keys
    ]);
}
if($text == 'Ortga ⬅️'){
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => 'Bosh sahifa 🏠',
        'parse_mode' => 'markdown',
        'reply_markup'=>$keys
    ]);
}
if($text == 'Manzil 🔍'){
    bot('sendLocation', [
        'chat_id' => $chat_id,
        'latitude' => 41.285271,
        'longitude' => 61.210172,
        'reply_markup'=>$keys
    ]);
}
if($text == 'Xabar jo`natish 📤'){
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => 'Xabarni kiriting:',
        'reply_to_message_id' => $mid,
        'parse_mode' => 'markdown',
        'reply_markup'=>$force
    ]);
}
if($reply == 'Xabarni kiriting:' and $from_chat_id != $admin){
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "_☝🏻 Sizga shuni aytib o'tishim kerakki siz telegram sozlamalaringizdan \"Uzatilgan xabarlar\" bolimdan hammaga qilib qoyishingiz kerak shundagina admin xabari sizga yetib keladi_\n\n `⚙️ Sozlamalar->Maxfiylik va havfsizlik->Uzatilgan xabarlar->Hamma` \n\n `⚙️ Settings->Privacy and Security->Forwarded Messages->Everybody` \n\n `⚙️ Настройки->Конфиденциальность-Пересылка сообщений->Все` \n\n\n*Sizning xabringiz:* ".$text,
        'parse_mode' => 'markdown',
        'reply_markup'=>$cansel
    ]);
   
    bot('forwardMessage', [
        'chat_id' => $admin,
        'from_chat_id' => $from_chat_id,
        'message_id' => $mid
    ]);
}


if($reply_forward_id and $from_chat_id == $admin){
    bot('sendMessage',[
       'chat_id'=> $reply_forward_id,
       'text'=> "_Admin xabari sizga keldi_ \n".$text,
       'parse_mode' => 'markdown',
       'reply_markup' => $keys
    ]);
    
    bot('sendphoto',[
       'chat_id'=> $reply_forward_id,
       'photo'=> $photo,
       'parse_mode' => 'markdown',
       'reply_markup' => $keys
    ]); 
    
    bot('senddocument',[
       'chat_id'=> $reply_forward_id,
       'document' => $document,
       'parse_mode' => 'markdown',
       'reply_markup' => $keys
    ]);
    
    bot('sendaudio',[
       'chat_id'=> $reply_forward_id,
       'audio' => $audio,
       'parse_mode' => 'markdown',
       'reply_markup' => $keys
    ]);
    
    bot('sendvoice',[
       'chat_id'=> $reply_forward_id,
       'voice' => $voice,
       'parse_mode' => 'markdown',
       'reply_markup' => $keys
    ]);
    
    bot('sendMessage',[
       'chat_id'=> $admin,
       'reply_to_message_id'=> $message_id,
       'text'=> "*Javobingiz yuborildi shu id ga* \n".$reply_forward_id,
       'parse_mode' => 'markdown'
    ]);
    
    
}

if($phone){
    bot('sendContact', [
        'chat_id' => $admin,
        'phone_number' => $phone,
        'first_name' => $first_name
        ]);
}



if($text != 'Men haqimda ✍🏻' and $text != 'Manzil 🔍' and $text != 'Xabar jo`natish 📤' and $reply != 'Xabarni kiriting:' and $text != "Ortga ⬅️" and $text != "/start" and !$phone and !$reply_forward_id and $from_chat_id == $admin){
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "_Sizning so'rovingiz bo'yicha hech narsa topilmadi. \n Iltimos quyidagi menulardan birini tanglang_",
        'parse_mode' => 'markdown',
        'reply_markup' => $keys
    ]);
}

?>
