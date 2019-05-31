<?php

namespace MyApp;
require_once(__DIR__ . '/config.php');

try {
    $db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  } catch (\PDOException $e) {
    throw new \Exception('Failed to connect DB!');
  }

$sql2 = "update users set
user_name = :user_name,
connection = :connection,
rate_range = :rate_range,
character1 = :character1,
character2 = :character2,
character3 = :character3
where tw_user_id = :id";

$stmt2 = $db->prepare($sql2);

$stmt2->bindValue(':user_name', $_POST['user_name'], \PDO::PARAM_INT);
$stmt2->bindValue(':connection', $_POST['connection'], \PDO::PARAM_INT);
$stmt2->bindValue(':rate_range', $_POST['rate_range'], \PDO::PARAM_INT);
$stmt2->bindValue(':character1', $_POST['character'][0], \PDO::PARAM_STR);
$stmt2->bindValue(':character2', $_POST['character'][1], \PDO::PARAM_STR);
$stmt2->bindValue(':character3', $_POST['character'][2], \PDO::PARAM_STR);
$stmt2->bindValue(':id', $_POST['hidden_id'], \PDO::PARAM_INT);

try {
    $stmt2->execute();
    $_SESSION['me']->user_name = $_POST['user_name'];
    $_SESSION['me']->connection = $_POST['connection'];
    $_SESSION['me']->rate_range = $_POST['rate_range'];
    $_SESSION['me']->character1 = $_POST['character'][0];
    $_SESSION['me']->character2 = $_POST['character'][1];
    $_SESSION['me']->character3 = $_POST['character'][2];
} catch (\PDOException $e) {
    //throw new \Exception('Failed to update user!');
}

header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login/index.php');