<?php
/**
 * 管理者のログイン認証の確認
 *
 * 管理者アカウントでログイン認証済みでなければログイン画面へ移動する
 */
function auth_confirm()
{
  if ($_SESSION["admin_login"] != TRUE) {
    header("Location: login.php");
    exit;
  }
}
