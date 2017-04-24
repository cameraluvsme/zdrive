<?php
session_start();

//--------------------
// セッションを完全に破棄
//--------------------
$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(), "", time() - 36000,
  $params["path"], $params["domain"],
  $params["secure"], $params["httponly"]
);
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログアウト | Crescent Shoes 管理</title>
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
    <h1>ログアウト</h1>
    <p>ログアウトしました。</p>
    <p><a href="login.php">ログインページへ移動</a></p>
  </main>
  <footer>
    <p>&copy; Crescent Shoes All rights reserved.</p>
  </footer>
</div>
</body>
</html>