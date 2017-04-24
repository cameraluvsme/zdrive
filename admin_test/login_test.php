<?php
session_start();

require_once "../util.inc.php";
require_once "../db.inc.php";

//--------------------
// 変数の初期化
//--------------------
$id = "";

//--------------------
// ログイン処理
//--------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // POSTされたデータを取得
  $id   = $_POST["id"];
  $pass = $_POST["pass"];

  // バリデーション済みフラグの初期化
  $isValidated = TRUE;

  // ログインIDのバリデーション
  if ($id === "") {
    // エラー
    $errorId = "ログインIDを入力してください。";
    $isValidated = FALSE;
  }

  // パスワードのバリデーション
  if ($pass === "") {
    // エラー
    $errorPass = "パスワードを入力してください。";
    $isValidated = FALSE;
  }
  // エラーが無ければ認証チェック
  if ($isValidated == TRUE) {
    try {
      $pdo = db_init();
      $sql = "SELECT * FROM admins
              WHERE
                login_id=?
              AND
                login_pass=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($id, hash("sha256", $pass.$id)));
      $info = $stmt->fetch();
      if ($info != FALSE) {
        // ログイン認証成功
        // セッション変数に情報を格納し、お知らせ一覧ページへ移動
        session_regenerate_id();
        $_SESSION["admin_id"]    = $id;
        $_SESSION["admin_login"] = TRUE;
        header("Location: index.php");
        exit;
      }
      else {
        // ログイン認証失敗
        $errorLogin = "ログインIDまたはパスワードに誤りがあります。";
      }
    }
    catch (PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログイン | Crescent Shoes 管理</title>
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<header>
  <div class="inner">
    <span><a href="index.php">Crescent Shoes 管理</a></span>
  </div>
</header>
<div id="container">
  <main>
    <h1>ログイン</h1>
    <?php if (isset($errorId)): ?>
    <p class="error"><?php echo $errorId; ?></p>
    <?php endif; ?>
    <?php if (isset($errorPass)): ?>
    <p class="error"><?php echo $errorPass; ?></p>
    <?php endif; ?>
    <?php if (isset($errorLogin)): ?>
    <p class="error"><?php echo $errorLogin; ?></p>
    <?php endif; ?>
    <form action="" method="post">
    <table id="loginbox">
      <tr>
        <th>ログインID</th>
        <td><input type="text" name="id" value="<?php echo h($id); ?>"></td>
      </tr>
      <tr>
        <th>パスワード</th>
        <td><input type="password" name="pass"></td>
      </tr>
    </table>
    <p><input type="submit" value="ログイン"></p>
    </form>
  </main>
  <footer>
    <p>&copy; Crescent Shoes All rights reserved.</p>
  </footer>
</div>
</body>
</html>