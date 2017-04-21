<?php
/**
 * htmlspecialcharsの短縮形
 */
function h($string) {
  return htmlspecialchars($string, ENT_QUOTES);
}

/**
 * ハッシュ化したセッションIDの取得
 */
function getToken() {
  return hash('sha256',session_id());
}