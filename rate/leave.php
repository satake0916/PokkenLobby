<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');

session_start();

if (!isset($_SESSION['me'])) {
    header("Location: ../login/index.php");
    exit;
}else{
    $me = $_SESSION['me'];
}

try {
    $db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
}catch(\PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}


$now_room = $_POST["room"];
$insert_or_entry = $_POST["insert_or_entry"];
$kbn = h($_POST["result"]);

$sql_leave = "update rate_match set battle = 2 where id = :id";
$stmt_leave = $db->prepare($sql_leave);
$stmt_leave->bindValue(':id', $now_room,\PDO::PARAM_INT);
try {
    $stmt_leave->execute();
} catch (\PDOException $e) {
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

$sql_check = "select * from rate_match where id = :id";
$check_st = $db->prepare($sql_check);
$check_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
$check_st->execute();
$some_checks=$check_st->fetchAll(PDO::FETCH_ASSOC);
$a_check = [];
foreach($some_checks as $check){
    $a_check = $check;
}

if($_POST["block"] == 1){
    $sql_block = "insert into block_list(user, block_user) values(:user, :block_user)";
    $st_block = $db->prepare($sql_block);
    $st_block->bindValue(':user', $a_check['first_player'], \PDO::PARAM_STR);
    $st_block->bindValue(':block_user', $a_check['second_player'], \PDO::PARAM_STR);
    $st_block->execute();

    $sql_block2 = "insert into block_list(user, block_user) values(:user, :block_user)";
    $st_block2 = $db->prepare($sql_block2);
    $st_block2->bindValue(':user', $a_check['second_player'], \PDO::PARAM_STR);
    $st_block2->bindValue(':block_user', $a_check['first_player'], \PDO::PARAM_STR);
    $st_block2->execute();
}




switch ($kbn) {
    case "勝ち": 
    if($insert_or_entry == 1){//自分がfirst_player
        if($a_check['second_result'] == 0){// 相手がすでに勝敗報告を済ませている
            //レート変動
            $winner = $a_check['first_player'];
            $winner_rating = $a_check['first_rating'];
            $loser = $a_check['second_player'];
            $loser_rating = $a_check['second_rating'];
            //レート計算
            $variation = 16+($loser_rating - $winner_rating)*0.04;
            $variation = floor($variation);//変動値
            $winner_rating = min($winner_rating + $variation, 9999);
            $loser_rating = max($loser_rating - $variation, 1000);

            $winner_sql = "update users set
            rating = :rating,
            rate_win = rate_win + 1
            where twitter_id = :twitter_id";
            $loser_sql = "update users set
            rating = :rating,
            rate_lose = rate_lose + 1            
            where twitter_id = :twitter_id";

            $winner_sql_st = $db->prepare($winner_sql);
            $loser_sql_st = $db->prepare($loser_sql);
            
            $winner_sql_st->bindValue(':rating', $winner_rating,\PDO::PARAM_INT);      
            $winner_sql_st->bindValue(':twitter_id', $winner,\PDO::PARAM_INT);                                                        
            $winner_sql_st->execute();

            $loser_sql_st->bindValue(':rating', $loser_rating,\PDO::PARAM_INT);      
            $loser_sql_st->bindValue(':twitter_id', $loser,\PDO::PARAM_INT);                                                        
            $loser_sql_st->execute();

            $spl_win_fin = "update rate_match set
            first_result = 1,
            battle = 3
            where id = :id";
            $spl_win_st_fin = $db->prepare($spl_win_fin);
            $spl_win_st_fin->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_win_st_fin->execute();

            goRate();

        }elseif($a_check['second_result'] == 1){//相手が誤った勝敗報告をしている
            //問題発生。problemフィールドを用意してもいいかもしれない
            $spl_problem = "update rate_match set
            problem = 1
            where id = :id";
            $spl_problem_st = $db->prepare($spl_problem);
            $spl_problem_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_problem_st->execute();
            goRate();
        }else{//まだ書き込まれていない
            //勝敗書き込み
            
            $spl_win = "update rate_match set
            first_result = 1
            where id = :id";
            $spl_win_st = $db->prepare($spl_win);
            $spl_win_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_win_st->execute();
            goRate();
        }
    }elseif($insert_or_entry == 2){//自分がsecond_player
        if($a_check['first_result'] == 0){
            //レート変動
            $winner = $a_check['second_player'];
            $winner_rating = $a_check['second_rating'];
            $loser = $a_check['first_player'];
            $loser_rating = $a_check['first_rating'];
            //レート計算
            $variation = 16+($loser_rating - $winner_rating)*0.04;
            $variation = floor($variation);//変動値
            $winner_rating = min($winner_rating + $variation, 9999);
            $loser_rating = max($loser_rating - $variation, 1000);

            $winner_sql = "update users set
            rating = :rating,
            rate_win = rate_win + 1
            where twitter_id = :twitter_id";
            $loser_sql = "update users set
            rating = :rating,
            rate_lose = rate_lose + 1            
            where twitter_id = :twitter_id";

            $winner_sql_st = $db->prepare($winner_sql);
            $loser_sql_st = $db->prepare($loser_sql);
            
            $winner_sql_st->bindValue(':rating', $winner_rating,\PDO::PARAM_INT);      
            $winner_sql_st->bindValue(':twitter_id', $winner,\PDO::PARAM_INT);                                                        
            $winner_sql_st->execute();

            $loser_sql_st->bindValue(':rating', $loser_rating,\PDO::PARAM_INT);      
            $loser_sql_st->bindValue(':twitter_id', $loser,\PDO::PARAM_INT);                                                        
            $loser_sql_st->execute();
            
            $spl_win_sec_fin = "update rate_match set
            second_result = 1,
            battle = 3
            where id = :id";
            $spl_win_fin_st = $db->prepare($spl_win_sec_fin);
            $spl_win_fin_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_win_fin_st->execute();

            goRate();
        }elseif($a_check['first_result'] == 1){
            //問題発生。problemフィールドを用意してもいいかもしれない
            $spl_problem = "update rate_match set
            problem = 1
            where id = :id";
            $spl_problem_st = $db->prepare($spl_problem);
            $spl_problem_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_problem_st->execute();
            goRate();
        }else{
            //勝敗書き込み
            //同時に書き込んだ場合レート変動の処理が行われないのではという心配
            $spl_win = "update rate_match set
            second_result = 1
            where id = :id";
            $spl_win_st = $db->prepare($spl_win);
            $spl_win_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_win_st->execute();
            goRate();
        }
    }else{//insertもentryもしていない？　insert_or_entryが1でも2でもない
        goRate();
    }
        break;
    case "負け":
    if($insert_or_entry == 1){//自分がfirst_player
        if($a_check['second_result'] == 1){// 相手がすでに勝敗報告を済ませている
            //レート変動
            $winner = $a_check['second_player'];
            $winner_rating = $a_check['second_rating'];
            $loser = $a_check['first_player'];
            $loser_rating = $a_check['first_rating'];
            //レート計算
            $variation = 16+($loser_rating - $winner_rating)*0.04;
            $variation = floor($variation);//変動値
            $winner_rating = min($winner_rating + $variation, 9999);
            $loser_rating = max($loser_rating - $variation, 1000);

            $winner_sql = "update users set
            rating = :rating,
            rate_win = rate_win + 1
            where twitter_id = :twitter_id";
            $loser_sql = "update users set
            rating = :rating,
            rate_lose = rate_lose + 1            
            where twitter_id = :twitter_id";

            $winner_sql_st = $db->prepare($winner_sql);
            $loser_sql_st = $db->prepare($loser_sql);
            
            $winner_sql_st->bindValue(':rating', $winner_rating,\PDO::PARAM_INT);      
            $winner_sql_st->bindValue(':twitter_id', $winner,\PDO::PARAM_INT);                                                        
            $winner_sql_st->execute();

            $loser_sql_st->bindValue(':rating', $loser_rating,\PDO::PARAM_INT);      
            $loser_sql_st->bindValue(':twitter_id', $loser,\PDO::PARAM_INT);                                                        
            $loser_sql_st->execute();

            $spl_lose_fin = "update rate_match set
            first_result = 0,
            battle = 3
            where id = :id";
            $spl_lose_st_fin = $db->prepare($spl_lose_fin);
            $spl_lose_st_fin->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_lose_st_fin->execute();

            goRate();

        }elseif($a_check['second_result'] == 0){//相手が誤った勝敗報告をしている
            //問題発生。problemフィールドを用意してもいいかもしれない
            $spl_problem = "update rate_match set
            problem = 1
            where id = :id";
            $spl_problem_st = $db->prepare($spl_problem);
            $spl_problem_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_problem_st->execute();
            goRate();
        }else{//まだ書き込まれていない
            //勝敗書き込み
            $spl_lose = "update rate_match set
            first_result = 0
            where id = :id";
            $spl_lose_st = $db->prepare($spl_lose);
            $spl_lose_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_lose_st->execute();
            goRate();
        }
    }elseif($insert_or_entry == 2){//自分がsecond_player
        if($a_check['first_result'] == 1){
            //レート変動
            $winner = $a_check['first_player'];
            $winner_rating = $a_check['first_rating'];
            $loser = $a_check['second_player'];
            $loser_rating = $a_check['second_rating'];
            //レート計算
            $variation = 16+($loser_rating - $winner_rating)*0.04;
            $variation = floor($variation);//変動値
            $winner_rating = min($winner_rating + $variation, 9999);
            $loser_rating = max($loser_rating - $variation, 1000);

            $winner_sql = "update users set
            rating = :rating,
            rate_win = rate_win + 1
            where twitter_id = :twitter_id";
            $loser_sql = "update users set
            rating = :rating,
            rate_lose = rate_lose + 1            
            where twitter_id = :twitter_id";

            $winner_sql_st = $db->prepare($winner_sql);
            $loser_sql_st = $db->prepare($loser_sql);
            
            $winner_sql_st->bindValue(':rating', $winner_rating,\PDO::PARAM_INT);      
            $winner_sql_st->bindValue(':twitter_id', $winner,\PDO::PARAM_INT);                                                        
            $winner_sql_st->execute();

            $loser_sql_st->bindValue(':rating', $loser_rating,\PDO::PARAM_INT);      
            $loser_sql_st->bindValue(':twitter_id', $loser,\PDO::PARAM_INT);                                                        
            $loser_sql_st->execute();

            $spl_lose_fin_sec = "update rate_match set
            second_result = 0,
            battle = 3
            where id = :id";
            $spl_lose_st_fin_sec = $db->prepare($spl_lose_fin_sec);
            $spl_lose_st_fin_sec->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_lose_st_fin_sec->execute();

            goRate();
        }elseif($a_check['first_result'] == 0){
            //問題発生。problemフィールドを用意してもいいかもしれない
             $spl_problem = "update rate_match set
            problem = 1
            where id = :id";
            $spl_problem_st = $db->prepare($spl_problem);
            $spl_problem_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_problem_st->execute();
            goRate();
        }else{
            //勝敗書き込み
            //同時に書き込んだ場合レート変動の処理が行われないのではという心配
            $spl_lose = "update rate_match set
            second_result = 0
            where id = :id";
            $spl_lose_st = $db->prepare($spl_lose);
            $spl_lose_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_lose_st->execute();
            goRate();
        }
    }else{
        //エラ、、insert_or_entryが1でも2でもない
        goRate();
    }
        break;
    case "退出":
        //退出
        if($a_check['second_player'] != 0){
            $spl_problem2 = "update rate_match set
            problem = 2
            where id = :id";
            $spl_problem2_st = $db->prepare($spl_problem2);
            $spl_problem2_st->bindValue(':id', $now_room,\PDO::PARAM_INT);                                
            $spl_problem2_st->execute();
        }
        goRate();
        exit;
    default:
        goRate();
}

   
