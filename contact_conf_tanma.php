<?php
session_start();

require_once "util.inc.php";
require_once "libs/qd/qdsmtp.php";
require_once "libs/qd/qdmail.php";

//----------------------------------------------
// セッション変数が登録されている場合は読み出す
//----------------------------------------------
if (isset($_SESSION["contact"])) {
  $contact = $_SESSION["contact"];
  $name    = $contact["name"];
  $kana    = $contact["kana"];
  $email   = $contact["email"];
  $phone   = $contact["phone"];
  $inquiry = $contact["inquiry"];
  $token   = $contact["token"];
  // CSRF対策
  if($token !== getToken()){
  	header("Location: contact.php");
  	exit();
  }
}
else {
  // 不正なアクセス
  // 入力ページへ戻る
  header("Location: contact.php");
  exit;
}

//--------------------
// 「送信」ボタン
//--------------------
if (isset($_POST["send"])) {
  $mail = new Qdmail();
  $mail->errorDisplay(false);
  $mail->smtpObject()->error_display = false;
  // SMTP用設定
  $param = array(
    "host"     => "w1.sim.zdrv.com",
    "port"     => 25,
    "from"     => "zd1b07@sim.zdrv.com",
    "protocol" => "SMTP",
  );
  $mail->smtp(TRUE);
  $mail->smtpServer($param);

  // 管理者宛て基本設定
  $senderAdrs = "zd1b07@sim.zdrv.com";
  $senderName = "Crescent Shoes Web";

// 連想配列を作ります
  $mailParams = [
    [ // 0: For User
      "fromAdrs"  => $senderAdrs,
      "fromName"  => $senderName,
      "toAdrs"    => $email,
      "toName"    => "{$name} 様",
      "subject"   => "Crescent Shoes 問い合わせ Thank You",
      "header"    => "{$name}様、以下のお問合せをいただきまして、ありがとうございます。",
      "footer"    => "3営業日以内に、担当者より返信いたします。"
    ],
    [ // 1: Admin
      "fromAdrs"  => $senderAdrs,
      "fromName"  => $senderName,
      "toAdrs"    => $senderAdrs,
      "toName"    => "Z Drive Account",
      "subject"   => "Crescent Shoes 問い合わせ",
      "header"    => "{$name}様より以下のお問合せをいただきました、ご対応ください。",
      "footer"    => "問合せ内容を確認の上で、返信ください。"
    ],
  ];

  $results = array();

  foreach ($mailParams as $key => $value) :
    $body = <<<EOT
{$value['header']}

■お名前
{$name}

■フリガナ
{$kana}

■メールアドレス
{$email}

■電話番号
{$phone}

■お問い合わせ内容
{$inquiry}

{$value['footer']}

EOT;

    $mail->from($value['fromAdrs'], $value['fromName']);
    $mail->to  ($value['toAdrs'], $value['toName']);
    $mail->subject($value['subject']);
    $mail->text($body);

    // 送信
    $results[$key] = $mail->send();
  endforeach;


  // error check
  foreach ($results as $key => $result) {
    if (!$result) {
      // 送信失敗
      switch($key) {
        case 0: // to user
          $_SESSION['msg'] = 'メールアドレスをもう一度...';
          break;
        case 1: // to admin
          $_SESSION['msg'] = '内部エラー...';
          break;
        default:
          // 本当は↓には入らないんだけども、保険で記述するとＣＯＤＥにミスがあるのに気づいちゃいます
          $_SESSION['msg'] = 'unknown error';
          break;
      }
      // エラー画面へ移動
      // セッション変数は破棄しない
      header("Location: contact_error.php");
      exit;
    }
  }

  // 送信成功
  // セッション変数を破棄
  unset($_SESSION["contact"]);
  // 完了画面へ移動
  header("Location: contact_done.php");
  exit;
}

//--------------------
// 「修正」ボタン
//--------------------
if (isset($_POST["back"])) {
  // 入力ページへ戻る
  $_SESSION["contact"]["contactOnly"] = TRUE;
  header("Location: contact.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="クレセントシューズは靴の素材と履き心地にこだわる方に満足をお届けする東京の靴屋です。後悔しない靴選びはぜひクレセントシューズにお任せください。">
  <meta name="keyword" content="Crescent,shoes,クレセントシューズ,東京,新宿区,メンズシューズ,レディースシューズ,キッズシューズ,ベビーシューズ">

  <title>Contact | Crescent Shoes</title>

  <link rel="shortcut icon" href="images/favicon.ico">
  <link rel="stylesheet" href="css/styles.css">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
      <!--[if lt IE 9]>
    <script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv-printshiv.min.js"></script>
    <script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!--[if lt IE 8]>
    <![endif]-->
  </head>
  <body class="pageTop" id="pageTop">
    <header class="navbar navbar-default navbar-fixed-top" role="banner">
      <div class="container">
        <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <h1 class="navbar-header">
          <a href="index.html" class="navbar-brand"><img src="images/logo01.png" alt="LOGO"></a>
        </h1><!-- /.navbar-header -->
        <nav class="navbar-collapse collapse" id="navigation" role="navigation">
          <ul class="nav navbar-nav">
            <li><a href="index.html">ホーム<span>HOME</span></a></li>
            <li><a href="about.html">会社概要<span>ABOUT</span></a></li>
            <li><a href="news.php">ニュース<span>NEWS</span></a></li>
            <li><a href="shop.html">ショップ<span>ONLINE SHOP</span></a></li>
            <li><a href="contact.php">お問い合わせ<span>CONTACT</span></a></li>
          </ul>
          <form class="navbar-form" role="search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="keyword">
              <span class="input-group-btn"><button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button></span>
            </div><!-- /.input-group -->
          </form>
        </nav>
      </div>
    </header>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <nav>
            <h1 class="page-header">Contact</h1>
            <ol class="breadcrumb">
              <li><a href="index.html">Home</a></li>
              <li><a href="contact.php">Contact</a></li>
              <li class="active">送信内容確認</li>
            </ol>
          </nav>
        </div>
      </div>
        <div class="row">
          <div class="col-md-4 hidden-sm hidden-xs">
            <div class="contact-img">
            <img src="images/contact.jpg">
            </div>
          </div>
          <div class="col-md-8">
            <h3 class="page-header">Message Confirmation</h3>
            <p>内容を修正される場合は「修正する」ボタンを、送信される場合は「送信する」ボタンを押してください。</p>
            <table class="table table-hover table-bordered">
              <tr>
                <th>お名前</th>
                <td><?php echo h($name); ?></td>
              </tr>
              <tr>
                <th>フリガナ</th>
                <td><?php echo h($kana); ?></td>
              </tr>
              <tr>
                <th>メールアドレス</th>
                <td><?php echo h($email); ?></td>
              </tr>
              <tr>
                <th>電話番号</th>
                <td><?php echo h($phone); ?></td>
              </tr>
              <tr>
                <th>お問い合わせ内容</th>
                <td><?php echo nl2br(h($inquiry)); ?></td>
              </tr>
            </table>
            <form class="form-horizontal" action="" method="post">
                <div class="form-group">
                <div class="col-sm-10">
                  <button type="submit" name="send" class="btn btn-success btn-lg">送信する</button>
                  <button type="submit" name="back" class="btn btn-success btn-lg">修正する</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="pagetop margin-top-md">
        <a href="#pageTop" class="center-block text-center" onclick="$('html,body').animate({scrollTop: 0}); return false;"><i class="fa fa-chevron-up center-block "></i>Page Top</a>
      </div>
      <footer class="margin-top-md" role="contentinfo">
        <div class="container">
          <div class="row">
            <div class="text-center">
              <ul class="list-inline">
                <li><a href="index.html">HOME</a></li>
                <li><a href="about.html">ABOUT</a></li>
                <li><a href="news.php">NEWS</a></li>
                <li><a href="shop.html">ONLINE SHOP</a></li>
                <li><a href="contact.php">CONTACT</a></li>
              </ul>
              <small>&copy; Crescent Shoes.All Rights Reserved.</small>
            </div><!-- /.text-center -->
          </div><!-- /.row -->
        </div><!-- /.container -->
      </footer>
      <script src="js/jquery-2.1.4.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/main.js"></script>
      <script>
      $(document).ready(function(){
        $('th').css({
            width: '20%',
            minWidth: '80px'
        });
        $('.contact-img').css({
            marginTop:'40px',
            overflow: 'hidden',
            height: $('.col-md-8').height() - 55
        });
      });
      </script>
  </body>
</html>