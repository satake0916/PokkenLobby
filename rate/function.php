<?php
function makeRandStr($length) {
    $str = array_merge(range('a', 'z'), range('0', '9'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
        $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }

  function goRate() {
    header('Location: ../rate/index.php');
    exit;
  }