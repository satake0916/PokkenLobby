<?php

namespace MyApp;

class User {
  private $_db;

  public function __construct() {
    $this->_connectDB();
  }

  private function _connectDB() {
    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      throw new \Exception('Failed to connect DB!');
    }
  }

  public function getUser($twUserId) {
    $sql = sprintf("select * from users where tw_user_id=%d", $twUserId);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetch(\PDO::FETCH_OBJ);
    return $res;
  }

  private function _exists($twUserId) {
    $sql = sprintf("select count(*) from users where tw_user_id=%d", $twUserId);
    $res = $this->_db->query($sql);
    return $res->fetchColumn() === '1';
  }

  public function saveTokens($tokens) {
    if ($this->_exists($tokens['user_id'])) {
      $this->_update($tokens);
    } else {
      $this->_insert($tokens);
    }
  }

  private function _insert($tokens) {
    $sql = "insert into users (
      tw_user_id,
      tw_access_token,
      tw_access_token_secret,
      user_name,
      twitter_id
      ) values (
      :tw_user_id,
      :tw_access_token,
      :tw_access_token_secret,
      :user_name,
      :twitter_id
      )";
    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':tw_user_id', (int)$tokens['user_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':tw_access_token', $tokens['oauth_token'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_access_token_secret', $tokens['oauth_token_secret'], \PDO::PARAM_STR);
    $stmt->bindValue(':user_name',$tokens['screen_name'],\PDO::PARAM_STR);
    $stmt->bindValue(':twitter_id',$tokens['screen_name'], \PDO::PARAM_STR);
    
    
    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to insert user!');
    }
  }

  private function _update($tokens) {
    $sql = "update users set
    tw_access_token = :tw_access_token,
    tw_access_token_secret = :tw_access_token_secret
    where tw_user_id = :tw_user_id";

    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':tw_access_token', $tokens['oauth_token'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_access_token_secret', $tokens['oauth_token_secret'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_user_id', (int)$tokens['user_id'], \PDO::PARAM_INT);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to update user!');
    }
  }
}