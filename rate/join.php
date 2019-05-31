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

if($me->connection == null || $me->rate_range == null || $me->character1 == null){
    header("Location: ../login/index.php");
    exit;
}

  

try {
    $db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $rate_rows = $db->query('SELECT * FROM rate_match')->fetchAll(PDO::FETCH_ASSOC);
    $user_rows = $db->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);    
}catch(\PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="msapplication-TileColor" content="#2d88ef">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<link rel="icon" type="image/png" sizes="36x36" href="/android-chrome-36x36.png">
<link rel="icon" type="image/png" sizes="48x48" href="/android-chrome-48x48.png">
<link rel="icon" type="image/png" sizes="72x72" href="/android-chrome-72x72.png">
<link rel="icon" type="image/png" sizes="96x96" href="/android-chrome-96x96.png">
<link rel="icon" type="image/png" sizes="128x128" href="/android-chrome-128x128.png">
<link rel="icon" type="image/png" sizes="144x144" href="/android-chrome-144x144.png">
<link rel="icon" type="image/png" sizes="152x152" href="/android-chrome-152x152.png">
<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="256x256" href="/android-chrome-256x256.png">
<link rel="icon" type="image/png" sizes="384x384" href="/android-chrome-384x384.png">
<link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">
<link rel="icon" type="image/png" sizes="36x36" href="/icon-36x36.png">
<link rel="icon" type="image/png" sizes="48x48" href="/icon-48x48.png">
<link rel="icon" type="image/png" sizes="72x72" href="/icon-72x72.png">
<link rel="icon" type="image/png" sizes="96x96" href="/icon-96x96.png">
<link rel="icon" type="image/png" sizes="128x128" href="/icon-128x128.png">
<link rel="icon" type="image/png" sizes="144x144" href="/icon-144x144.png">
<link rel="icon" type="image/png" sizes="152x152" href="/icon-152x152.png">
<link rel="icon" type="image/png" sizes="160x160" href="/icon-160x160.png">
<link rel="icon" type="image/png" sizes="192x192" href="/icon-192x192.png">
<link rel="icon" type="image/png" sizes="196x196" href="/icon-196x196.png">
<link rel="icon" type="image/png" sizes="256x256" href="/icon-256x256.png">
<link rel="icon" type="image/png" sizes="384x384" href="/icon-384x384.png">
<link rel="icon" type="image/png" sizes="512x512" href="/icon-512x512.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icon-16x16.png">
<link rel="icon" type="image/png" sizes="24x24" href="/icon-24x24.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icon-32x32.png">
<link rel="manifest" href="/manifest.json">
<meta name="description" content="ポッ拳対戦・交流ウェブサービス「ポッ拳ロビー」">
<title>ポッ拳ロビー：対戦部屋</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
<script src="../main.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
<!-- ========== header ========== -->
<header>
    <script>header('../');</script> 
</header>
<!-- ========== /header ========== -->
<!-- ========== nav ========== -->
<nav>
<div class="container nav-container">
<ul class="navbar">
<li> sample</li>
     </ul>
 </div><!-- /nav-container -->
</nav>

<!-- ========== /nav ========== -->

<!-- ========== main ========== -->

<section class="join_main">
    <div class="container">
        <?php
        $player = $me->twitter_id;
        $user = $me->user_name;
        $spl = "select * from rate_match where battle = 0";
        $st = $db->query($spl);
        $now=$st->fetchAll(PDO::FETCH_ASSOC);
       
        $sql_me = "select * from users where twitter_id = :id";
        $sql_me_st = $db->prepare($sql_me);
        $sql_me_st->bindValue(':id', $player ,\PDO::PARAM_STR);
        try {
            $sql_me_st->execute();
            } catch (\PDOException $e) {
            //header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
        
        $meme = $sql_me_st->fetchAll(PDO::FETCH_ASSOC);
        $a_me = [];
        foreach($meme as $code){
            $a_me = $code;
        }
        $rating = $a_me['rating'];
        $character = array($me->character1,$me->character2,$me->character3);
        $rate_range = $me->rate_range;
        $connection = $me->connection;
        $now_room = 0;
        $insert_or_entry = 0;
        $number = count($now);

        
        $sql_init = "select * from rate_match where (first_player = :user or second_player = :user) and (battle=0 or battle = 1)";
        $sql_init_st = $db->prepare($sql_init);
        $sql_init_st->bindValue(':user', $player ,\PDO::PARAM_STR);
        try {
            $sql_init_st->execute();
            } catch (\PDOException $e) {
            //header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
        $stay_room=$sql_init_st->fetchAll(PDO::FETCH_ASSOC);
        if(count($stay_room) !== 0){
            $keynumber = $stay_room[0]['code'];
            $now_room = $stay_room[0]['id'];
            echo "<table>","<tr>","<td>","ルームナンバー","</td>","<td>",h($now_room),"</td>","</tr>","<tr>","<td>","対戦コード","</td>","<td>",h($keynumber),"</td>","</tr>";
            if($stay_room[0]['first_player'] == $player){
                $insert_or_entry = 1;
                if($stay_room[0]['second_player'] == 0){
                    echo "<div class='loading'><img src='../images/loading.gif'></div>";
                    echo "<p id='view'>対戦相手を待っています…</p>";
                }else{
                    echo "<table>
                    <tr>
                        <td>プレイヤー1</td>
                        <td><?= h(",$stay_room[0]['first_user_name'],") ?>
                            <div class='rate_character'>img src='../images/<?=",$stay_room[0]['first_character1'],"?>.png'></div>
                            <div class='rate_character'>img src='../images/<?=",$stay_room[0]['first_character2'],"?>.png'></div>
                            <div class='rate_character'>img src='../images/<?=",$stay_room[0]['first_character3'],"?>.png'></div>
                        </td>
                        <td><?= h(",$stay_room[0]['first_rating'],") ?></td>
                    </tr>
                    <tr>
                        <td>プレイヤー2</td>
                        <td><?= h(",$stay_room[0]['second_user_name'],") ?>
                            <div class='rate_character'>img src='../images/<?=",$stay_room[0]['second_character1'],"?>.png'></div>
                            <div class='rate_character'>img src='../images/<?=",$stay_room[0]['second_character2'],"?>.png'></div>
                            <div class='rate_character'>img src='../images/<?=",$stay_room[0]['second_character3'],"?>.png'></div>    
                        </td>
                        <td><?= h(",$stay_room[0]['second_rating'],") ?></td>
                    </tr>
                </table>
                        
                    <p>対戦を始めてください！</p>";                    
                }
            }else{
                $insert_or_entry = 2;
                echo "<table>
                <tr>
                <td>プレイヤー1</td>
                <td><?= h(",$stay_room[0]['first_user_name'],") ?>
                    <div class='rate_character'>img src='../images/<?=",$stay_room[0]['first_character1'],"?>.png'></div>
                    <div class='rate_character'>img src='../images/<?=",$stay_room[0]['first_character2'],"?>.png'></div>
                    <div class='rate_character'>img src='../images/<?=",$stay_room[0]['first_character3'],"?>.png'></div>
                </td>
                <td><?= h(",$stay_room[0]['first_rating'],") ?></td>
            </tr>
            <tr>
                <td>プレイヤー2</td>
                <td><?= h(",$stay_room[0]['second_user_name'],") ?>
                    <div class='rate_character'>img src='../images/<?=",$stay_room[0]['second_character1'],"?>.png'></div>
                    <div class='rate_character'>img src='../images/<?=",$stay_room[0]['second_character2'],"?>.png'></div>
                    <div class='rate_character'>img src='../images/<?=",$stay_room[0]['second_character3'],"?>.png'></div>    
                </td>
                <td><?= h(",$stay_room[0]['second_rating'],") ?></td>
            </tr>
            </table>
                    
                <p>対戦を始めてください！</p>";     
            }
            ?>
            <h2>対戦終了後は勝敗に関わらず、必ず結果報告をしてください。結果が反映されません。</h2>
            <?php
        }else{

        

        
        if($number == 0){
            insert($player,$user_name, $rating,$rate_range,$connection,$character);
        }elseif($number <3){
            $rand = rand(0,2);
            if($rand == 0){
                insert($player,$user_name, $rating,$rate_range,$connection,$character);                
            }else{
                entry($player,$user_name, $rating,$rate_range,$connection,$character);                                            
            }
        }else{
            //10割敵探し、ただしやれる敵がいなければ挿入
            entry($player,$user_name, $rating,$rate_range,$connection,$character);                            
        }
    }
                       
        
        function insert($first_player, $user_name ,$rating, $range, $connection,$character){
            global $db;
            global $now_room;
            global $insert_or_entry;            
            $sql_insert ="insert into rate_match(
                first_player,
                first_user_name,
                first_rating,
                first_character1,
                first_character2,
                first_character3,
                first_min_rate,
                first_max_rate,                
                connection,
                battle,
                code
                ) values(
                :first_player,
                :first_user_name,
                :first_rating,
                :first_character1,
                :first_character2,
                :first_character3,
                :first_min_rate,
                :first_max_rate,
                :connection,
                0,
                :code
                )";
            $keynumber = makeRandStr(8);
            if($range == 0){
                $min_rating = 0;
                $max_rating = 9999;
            }elseif($range == 1){
                $int_range = 200;
                $min_rating = $rating - $int_range;
                $max_rating = $rating + $int_range;
            }else{
                $int_range = 100;
                $min_rating = $rating - $int_range;
                $max_rating = $rating + $int_range;
            }
            $st_insert = $db->prepare($sql_insert);
            $st_insert->bindValue(':first_player', $first_player,\PDO::PARAM_STR);
            $st_insert->bindValue(':first_user_name', $user_name,\PDO::PARAM_STR);            
            $st_insert->bindValue(':first_rating',$rating,\PDO::PARAM_INT);
            $st_insert->bindValue(':first_character1', $character[0],\PDO::PARAM_STR);
            $st_insert->bindValue(':first_character2', $character[1],\PDO::PARAM_STR); 
            $st_insert->bindValue(':first_character3', $character[2],\PDO::PARAM_STR);             
            $st_insert->bindValue(':first_min_rate',$min_rating,\PDO::PARAM_INT);
            $st_insert->bindValue(':first_max_rate',$max_rating,\PDO::PARAM_INT);            
            $st_insert->bindValue(':connection',$connection,\PDO::PARAM_INT);                
            $st_insert->bindValue(':code',$keynumber ,\PDO::PARAM_STR);
            try {
                $st_insert->execute();
                $now_room = intval($db->lastInsertId());
                $insert_or_entry = 1;
            } catch (\PDOException $e) {
                //header('Content-Type: text/plain; charset=UTF-8', true, 500);
                exit($e->getMessage());
            }
            ?>
            <table>
                <tr>
                    <td>ルームナンバー</td>
                    <td><?= h($now_room) ?></td>
                </tr>
                <tr>
                    <td>対戦コード</td>
                    <td><?= h($keynumber) ?></td>
                </tr>
            </table>

            
            
            <div id="loading"><img src="../images/loading.gif"></div>
            <p id="view">対戦相手を待っています…</p>
            <h2>対戦終了後は勝敗に関わらず、必ず結果報告をしてください。結果が反映されません。</h2>
            <?php
        }

        function entry($second_player,$user_name, $rating, $rate_range, $connection, $character){
            global $db;
            global $now_room;
            global $insert_or_entry;
            if($rate_range == 0){
                $min_rating = 0;
                $max_rating = 9999;
            }elseif($rate_range == 1){
                $int_range = 200;
                $min_rating = $rating - $int_range;
                $max_rating = $rating + $int_range;
            }else{
                $int_range = 100;
                $min_rating = $rating - $int_range;
                $max_rating = $rating + $int_range;
            }

            if($connection == 0){
                $st_entry=$db->prepare("select * from rate_match where first_rating >= :min and first_rating <= :max and first_min_rate <= :mine and first_max_rate >= :mine and (connection = :con1 or connection = :con2) and battle = 0");
                $st_entry->bindValue(':min', $min_rating,\PDO::PARAM_INT);
                $st_entry->bindValue(':max', $max_rating,\PDO::PARAM_INT);
                $st_entry->bindValue(':mine', $rating,\PDO::PARAM_INT);                
                $st_entry->bindValue(':con1', 0,\PDO::PARAM_INT);
                $st_entry->bindValue(':con2', 1,\PDO::PARAM_INT);                
                $st_entry->execute();
                $some_entries=$st_entry->fetchAll(PDO::FETCH_ASSOC);//対戦待ちの部屋たち
            }elseif($connection == 1){
                $st_entry=$db->prepare("select * from rate_match where first_rating >= :min and first_rating <= :max and first_min_rate <= :mine and first_max_rate >= :mine and battle = 0");
                $st_entry->bindValue(':min', $min_rating,\PDO::PARAM_INT);
                $st_entry->bindValue(':max', $max_rating,\PDO::PARAM_INT);         
                $st_entry->bindValue(':mine', $rating,\PDO::PARAM_INT);                                
                $st_entry->execute();
                $some_entries=$st_entry->fetchAll(PDO::FETCH_ASSOC);//対戦待ちの部屋たち
            }else{
                $st_entry=$db->prepare("select * from rate_match where first_rating >= :min and first_rating <= :max and first_min_rate <= :mine and first_max_rate >= :mine and (connection = :con1 or connection = :con2) and battle = 0");
                $st_entry->bindValue(':min', $min_rating,\PDO::PARAM_INT);
                $st_entry->bindValue(':max', $max_rating,\PDO::PARAM_INT);
                $st_entry->bindValue(':mine', $rating,\PDO::PARAM_INT);                                
                $st_entry->bindValue(':con1', 1,\PDO::PARAM_INT);
                $st_entry->bindValue(':con2', 2,\PDO::PARAM_INT);                
                $st_entry->execute();
                $some_entries=$st_entry->fetchAll(PDO::FETCH_ASSOC);//対戦待ちの部屋たち
            }

            //some_entriesが0ならinsert
            if(count($some_entries) == 0){
                insert($second_player, $rating, $rate_range, $connection, $character);
            }else{

                $an_entry = [];//入る部屋.もっともidが若い部屋に入る
                foreach($some_entries as $code){
                    $an_entry = $code;
                    break;
                }

                $sql_block = "select * from block_list where user = :user";
                $st_sql_block = $db->prepare($sql_block);
                $st_sql_block->bindValue(':user', $second_player,\PDO::PARAM_STR);
                $st_sql_block->execute();
                $block_users = $st_sql_block->fetchAll(PDO::FETCH_ASSOC);
                $nonblock = true;
                foreach($block_users as $block_user){
                    if($block_user['block_user'] == $an_entry['first_player']){
                        $nonblock = false;
                        break;
                    }
                }

                //部屋が決まった後
                if( $an_entry['battle'] == 0 && $an_entry['first_player'] != $second_player && $nonblock){

                    $sql_entry = "update rate_match set
                    second_player = :second_player,
                    second_user_name = :user_name,
                    second_rating = :second_rating,
                    second_character1 = :second_character1,
                    second_character2 = :second_character2,
                    second_character3 = :second_character3,                    
                    battle = 1
                    where id = :id";
                    $st_entry_sql = $db->prepare($sql_entry);
                    $st_entry_sql->bindValue(':second_player', $second_player,\PDO::PARAM_STR);
                    $st_entry_sql->bindValue(':user_name', $user_name,\PDO::PARAM_STR);                    
                    $st_entry_sql->bindValue(':second_rating', $rating,\PDO::PARAM_INT);      
                    $st_entry_sql->bindValue(':second_character1', $character[0],\PDO::PARAM_STR);
                    $st_entry_sql->bindValue(':second_character2', $character[1],\PDO::PARAM_STR);
                    $st_entry_sql->bindValue(':second_character3', $character[2],\PDO::PARAM_STR);                    
                    $st_entry_sql->bindValue('id',$an_entry['id']);
                    try {
                        $st_entry_sql->execute();
                        $now_room = $an_entry['id'];
                        $insert_or_entry = 2;                        
                    } catch (\PDOException $e) {
                        //header('Content-Type: text/plain; charset=UTF-8', true, 500);
                        exit($e->getMessage());
                    }
                    ?>
                    <table>
                <tr>
                    <td>ルームナンバー</td>
                    <td><?= h($an_entry['id']) ?></td>
                </tr>
                <tr>
                    <td>対戦コード</td>
                    <td><?= h($an_entry['code']) ?></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>プレイヤー1</td>
                    <td><?= h($an_entry['first_user_name']) ?>
                    <div class='rate_character'><img src='../images/<?=$an_entry['first_character1']?>.png'></div>
                    <div class='rate_character'><img src='../images/<?=$an_entry['first_character2']?>.png'></div>
                    <div class='rate_character'><img src='../images/<?=$an_entry['first_character3']?>.png'></div>
                </td>
                    <td><?= h($an_entry['first_rating']) ?></td>
                </tr>
                <tr>
                    <td>プレイヤー2</td>
                    <td><?= h($user_name) ?>
                    <div class='rate_character'><img src='../images/<?=$an_entry['second_character1']?>.png'></div>
                    <div class='rate_character'><img src='../images/<?=$an_entry['second_character2']?>.png'></div>
                    <div class='rate_character'><img src='../images/<?=$an_entry['second_character3']?>.png'></div>
                </td>
                    <td><?= h($rating) ?></td>
                </tr>
            </table>
                    
                <p>対戦を始めてください！</p>
                    <h2>対戦終了後は勝敗に関わらず、必ず結果報告をしてください。結果が反映されません。</h2>
                    <?php
                }else{
                    insert($second_player, $rating, $rate_range, $connection);                    
                }
                
            }
        }
        ?>     

        <form action="leave.php" method="post" class="leave_button">
            
            <ul class="radio">
                <li>
            <label>
            <input type="radio" value=1 name="block">対戦相手をブロックする
            </label>
    </li>
    <li>
            <label>
            <input type="radio" value=0 name="block" checked="checked">対戦相手をブロックしない
            </label>
    </li>
    </ul>

            <input type="submit" value="勝ち" name="result">
            <input type="submit" value="負け" name="result">
            <input type="submit" value="退出" name="result">
            <input type="hidden" value=<?= $now_room ?> name="room">
            <input type="hidden" value=<?= $insert_or_entry ?> name="insert_or_entry">
        </form>
            
        </main>
    </div><!-- /.container -->
    
    
    
</section>


<!-- ========== /main ========== -->


<!-- ========== footer ========== -->
<footer>
		<script>footer('../');</script>  
</footer>
<!-- ========== /footer ========== -->
<!-- ========== script ========== -->
<script>

    $(window).load(function () {
        function checkUpdate() {
            $.post('comet.php?id=<?= $now_room ?> ' , {}, function(data) {
                document.getElementById("loading").remove();
                $('#view').html(data);
            });
        }

        checkUpdate();
    });
    
</script>
<!-- ========== /script ========== -->
</body>
</html>