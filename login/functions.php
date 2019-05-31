<?php

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function goHome() {
  header('Location: http://' . $_SERVER['HTTP_HOST']);
  exit;
}