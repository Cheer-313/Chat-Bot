<?php
//Kiểm tra verify token trong dashboard
if(isset($_GET['hub_verify_token'])) { 
    if ($_GET['hub_verify_token'] === 'chat_bot_sample') {
        echo $_GET['hub_challenge'];
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
    }
}

//Lấy id và tin nhắn của user
$raw_data = file_get_contents('php://input');
$input = json_decode($raw_data, true);

if(isset($input['entry'][0]['messaging'][0]['sender']['id'])){
    // Id của user
    $id_user = $input['entry'][0]['messaging'][0]['sender']['id'];
    //Tin nhắn của user
    $receive_msg = $input['entry'][0]['messaging'][0]['message']['text'];
    //REST API
    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAAH5CO7cXIoBAFpLVVVIkxsdghZADx8p4HbqCFAOBTBvhalWcznLvXVKWN8SxzovPgRYirSXC46VLE8s6QjoflcI2xwDvbnr84Sd5L4SKajHqVreOH9d70O7PBc5sH5QT7bykrmIyKKFmeGicBuu3oIQrmsz1wewPA569k01ZAGDuNRARX';

    //Tạo tin nhắn trả lời tự động
    $ch = curl_init($url);
    $json = '{
        "recipient":{
            "id":"' . $id_user . '"
            },
            "message":{
                "text":"Xin chào, ' . $receive_msg . '"
            }
        }';

    //Thiết lập curl để gửi data
    curl_setopt($ch, CURLOPT_POST, 1); //Gửi theo method Post
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json); //Data để gửi
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_TIMEOUT,500);

    if(!empty($receive_msg)){
        $result = curl_exec($ch);
    }
}

?>