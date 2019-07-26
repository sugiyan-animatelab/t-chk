<?php
require dirname(__FILE__)."/../const.php";
require '../vendor/autoload.php';

if(!$_POST){
    header('Location: /');
}
session_start();

if(isset($_POST['id'],$_POST['team'],$_POST['name'],$_POST['email'])){
    $_SESSION['id'] = $_POST['id'];
    $_SESSION['team'] = $_POST['team'];

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];

    $_SESSION['selected_team'] = $_POST['selected_team'];
    $_SESSION['update_flg'] = $_POST['update_flg'];

    if(isset($_POST['comment'])){
        $_SESSION['comment'] = $_POST['comment'];
    }
}
?>
<head>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php

$action = $_POST['action'];
$id = htmlspecialchars($_SESSION['id']);
$team = htmlspecialchars($_SESSION['team']);
$name = htmlspecialchars($_SESSION['name']);
$selected_team = htmlspecialchars($_SESSION['selected_team']);
$email = htmlspecialchars($_SESSION['email']);
$update_flg = htmlspecialchars($_SESSION['update_flg']);
$comment = htmlspecialchars($_SESSION['comment']);


$message = "記入チーム:".$selected_team."\r\n";
$message .= "スタッフID:".$id."\r\n";
$message .= "所属エリア:".$team."\r\n";
$message .= "連絡先email:".$email."\r\n";
$message .= "上書き希望:".$update_flg."\r\n";

if($action == "post"){
    echo '<div class="tb-cell mail-form">';
    echo '<form id="form" action="mail.php" method="post">';
    echo '<div class="row">';
    echo '<div class="cell">';
    echo '<label>記入チーム</label>';
    echo '<!--cell--></div>';
    echo '<div class="cell">';
    echo $_SESSION['selected_team'];
    echo '<!--cell--></div>';
    echo '<!--row--></div>';
    echo '<div class="row">';
    echo '<div class="cell">';
    echo '<label>スタッフID</label>';
    echo '<!--cell--></div>';
    echo '<div class="cell">';
    echo $_SESSION['id'];
    echo '<!--cell--></div>';
    echo '<!--row--></div>';
    echo '<div class="row">';
    echo '<div class="cell">';
    echo '<label>所属エリア</label>';
    echo '<!--cell--></div>';
    echo '<div class="cell">';
    echo $_SESSION['team'];
    echo '<!--cell--></div>';
    echo '<!--row--></div>';
    echo '<div class="row">';
    echo '<div class="cell">';
    echo '<label>名前</label>';
    echo '<!--cell--></div>';
    echo '<div class="cell">';
    echo $_SESSION['name'];
    echo '<!--cell--></div>';
    echo '<!--row--></div>';
    echo '<div class="row">';
    echo '<div class="cell">';
    echo '<label>連絡先email</label>';
    echo '<!--cell--></div>';
    echo '<div class="cell">';
    echo $_SESSION['email'];
    echo '<!--cell--></div>';
    echo '<!--row--></div>';
    echo '<div class="row">';
    echo '<div class="cell">';
    echo '<label>上書き希望</label>';
    echo '<!--cell--></div>';
    echo '<div class="cell">';
    echo $_SESSION['update_flg'];
    echo '<!--cell--></div>';
    echo '<!--row--></div>';

    $i = 1;
    $comment ="";
    while (true) {
        if(empty($_POST['name'.$i])) {
            break;
        }
        echo '<div class="row">';
        echo '<div class="cell">';
        echo $_POST['name'.$i];
        echo '<!--cell--></div>';
        echo '<div class="cell">';
        echo $_POST['comment'.$i];
        echo '<!--cell--></div>';
        echo '<!--row--></div>';

        if(!empty($_POST['comment'.$i])) {
            $comment .= $_POST['name'.$i] .":" .str_replace("\r\n", '', $_POST['comment'.$i]). "\r\n";
        }

        $i = $i+1;
    }
    $_SESSION['comment'] = $message.$comment;
    file_put_contents("../data/". $email .".txt", $message.$comment, FILE_APPEND);

    echo '<div class="cell">';
    echo '<p>入力内容が正しければ送信してください</p><br>';
    echo '<button type="submit" id="sbtn" name="action" value="send">送　信</button>';
    echo '<button type="button" onclick="history.go(-1)">入力フォームに戻る</button>';
    echo '<!--cell--></div>';
    echo '<!--row--></div>';
    echo '</form>';
    echo '<!--tb-cell--></div>';

}elseif($action == "send"){
    $api_key           = SENDGRID_API_KEY;
    $from              = new SendGrid\Email(null, FROM);
//    $tos               = explode(',', $_ENV['TOS']);

    $sendgrid = new \SendGrid($api_key);

    $to = new SendGrid\Email(null, $email);

    $content = new SendGrid\Content("text/plain", $comment);

    $email = new SendGrid\Mail($from, "長所チェック提出(".$name.")", $to, $content);

    $response = $sendgrid->client->mail()->send()->post($email);
    //var_dump($response);

    if ($response->statusCode() == "202") {
        echo '<p class="msg">メッセージは正常に送信されました</p>';
        echo '<p class="msg">お疲れ様でした。</p>';
        echo '<button type="button" onclick="location.href=\\' + SITE_URL + '">エリア選択に戻る</button>';
    } else {
        var_dump($response);
        echo '<p class="msg">メッセージの送信に失敗しました</p>';
        echo '<p class="msg">再度試しても問題が発生した場合は' + MAIL_ADDR + 'にご連絡ください</p>';
        echo '<button type="button" onclick="history.go(-2)">入力フォームに戻る</button>';
    }
    $_SESSION = array();
    session_destroy();
}
?>
</body>
</html>