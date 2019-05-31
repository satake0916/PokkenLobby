<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/functions.php');

$twitterLogin = new MyApp\TwitterLogin();

if ($twitterLogin->isLoggedIn()) {
  $me = $_SESSION['me'];

  MyApp\Token::create();
}

try {
    $db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
} catch (\Exception $e) {
    //header('Content-Type: text/plain; charset=UTF-8', true, 500);
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
<title>ポッ拳ロビー:ログイン</title>
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
	<script>nav('../');</script> 
</nav>
<!-- ========== /nav ========== -->

<!-- ========== main ========== -->

<section class="login_main">
	<div class="container">
		<main>
            <?php if ($twitterLogin->isLoggedIn()) : ?>

            <form action="logout.php" method="post" class="logout_button">
                <input type="submit" value="Log Out">
                <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
            </form>

            <form action="update_prf.php" method="post" name="form1" onSubmit="return check()">

                <h1>ユーザー名</h1>
                    <input type="text" name="user_name" value="<?= h($me->user_name); ?>">
                <h1>TwitterID @<?= h($me->twitter_id); ?></h1>

                <h1>レーティング <?php
                $sql_top = "select * from users where twitter_id = :user";
                $st_top = $db->prepare($sql_top);
                $st_top->bindValue(':user', $me->twitter_id ,\PDO::PARAM_STR);
                $st_top->execute();
                $now_top=$st_top->fetchAll(PDO::FETCH_ASSOC); 
                echo h($now_top[0]['rating']);
                $sql_number = "SELECT *, (SELECT Count(*)+1
                FROM users as q1
                WHERE q2.rating < q1.rating and (q1.rate_win != 0 or q1.rate_lose != 0) ) AS number
                FROM users AS q2
                where q2.rating = :rating";
                $st_sql_number = $db->prepare($sql_number);
                $st_sql_number->bindValue(':rating', $now_top[0]['rating'] ,\PDO::PARAM_INT);
                $st_sql_number->execute();
                $now_number = $st_sql_number->fetchAll(PDO::FETCH_ASSOC);
                ?></h1>
                <h3><?= h($now_top[0]['rate_win'])?>勝<?= h($now_top[0]['rate_lose'])?>敗 <?php if($now_top[0]['rate_win'] != 0 || $now_top[0]['rate_lose'] != 0 ){echo h($now_number[0]['number']); echo "位";} ?> </h3>

                <h1>使用キャラ</h1>
                
                <div class="play_character">
                    <input id="lucario" type="checkbox" value="lucario" name="play_character">
                    <label class="checkbox_label" for="lucario">lucario</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="pikachu" type="checkbox" value="pikachu" name="play_character">
                    <label class="checkbox_label" for="pikachu">pikachu</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="machamp" type="checkbox" value="machamp" name="play_character">
                    <label class="checkbox_label" for="machamp">machamp</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="gardevoir" type="checkbox" value="gardevoir" name="play_character">
                    <label class="checkbox_label" for="gardevoir">gardevoir</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character"> 
                    <input id="weavile" type="checkbox" value="weavile" name="play_character">
                    <label class="checkbox_label" for="weavile">weavile</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="suicune" type="checkbox" value="suicune" name="play_character">
                    <label class="checkbox_label" for="suicune">suicune</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="charizard" type="checkbox" value="charizard" name="play_character">
                    <label class="checkbox_label" for="charizard">charizard</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="gengar" type="checkbox" value="gengar" name="play_character">
                    <label class="checkbox_label" for="gengar">gengar</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="blaziken" type="checkbox" value="blaziken" name="play_character">
                    <label class="checkbox_label" for="blaziken">blaziken</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="libre" type="checkbox" value="libre" name="play_character">
                    <label class="checkbox_label" for="libre">libre</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="sceptile" type="checkbox" value="sceptile" name="play_character">
                    <label class="checkbox_label" for="sceptile">sceptile</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="chandelure" type="checkbox" value="chandelure" name="play_character">
                    <label class="checkbox_label" for="chandelure">chandelure</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="mewtwo" type="checkbox" value="mewtwo" name="play_character">
                    <label class="checkbox_label" for="mewtwo">mewtwo</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="shadowmewtwo" type="checkbox" value="shadowmewtwo"  name="play_character">
                    <label class="checkbox_label" for="shadowmewtwo">shadowmewtwo</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="garchomp" type="checkbox" value="garchomp" name="play_character">
                    <label class="checkbox_label" for="garchomp">garchomp</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="braixen" type="checkbox" value="braixen" name="play_character">
                    <label class="checkbox_label" for="braixen">braixen</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="darkrai" type="checkbox" value="darkrai" name="play_character">
                    <label class="checkbox_label" for="darkrai">darkrai</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="scizor" type="checkbox" value="scizor" name="play_character">
                    <label class="checkbox_label" for="scizor">scizor</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="croagunk" type="checkbox" value="croagunk" name="play_character">
                    <label class="checkbox_label" for="croagunk">croagunk</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="empeleon" type="checkbox" value="empeleon" name="play_character">
                    <label class="checkbox_label" for="empeleon">empeleon</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="decidueye" type="checkbox" value="decidueye" name="play_character">
                    <label class="checkbox_label" for="decidueye">decidueye</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="aegislash" type="checkbox" value="aegislash" name="play_character">
                    <label class="checkbox_label" for="aegislash">aegislash</label>
                    <div class="character_error"></div>
                </div>
                <div class="play_character">
                    <input id="blastoise" type="checkbox" value="blastoise" name="play_character">
                    <label class="checkbox_label" for="blastoise">blastoise</label>
                    <div class="character_error"></div>
                </div>

                <h1>接続環境設定</h1>
                <label class="check"><input type="radio" name="connection" value=0 <?php if($me->connection == 0) echo "checked" ?>>有線接続　無線接続のプレイヤーとの対戦を許可しない</label>
                <label class="check"><input type="radio" name="connection" value=1 <?php if($me->connection == 1) echo "checked" ?>>有線接続　無線接続のプレイヤーとの対戦を許可する</label>
                <label class="check"><input type="radio" name="connection" value=2 <?php if($me->connection == 2) echo "checked" ?>>無線接続</label>

                <h1>レートマッチング範囲</h1>
                <label class="check"><input type="radio" name="rate_range" value=0 <?php if($me->rate_range == 0) echo "checked" ?>>制限なし</label>
                <label class="check"><input type="radio" name="rate_range" value=1 <?php if($me->rate_range == 1) echo "checked" ?>>レート差200以内</label>
                <label class="check"><input type="radio" name="rate_range" value=2 <?php if($me->rate_range == 2) echo "checked" ?>>レート差100以内</label>

                <div id="alert"></div>
                <input type="hidden" name="hidden_id" value="<?= h($me->tw_user_id); ?>">
                <p><input type="submit" name="submit" value="更新" class="common_button"></p>
        
            </form>

            
            
            <?php
            
            echo "<h1>対戦ログ</h1>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr align='center'>";
                echo "<td>";
                echo "自分のレート";
                echo "</td>";
                echo "<td>";
                echo "対戦相手";
                echo "</td>";
                echo "<td>";
                echo "対戦相手のレート";
                echo "</td>";
                echo "<td>";
                echo "結果";
                echo "</td>";
                echo "<td>";
                echo "レート変動";
                echo "</td>";
            echo "</tr>";
            $sql_log = "select * from rate_match where (first_player = :user or second_player = :user) and battle = 3";
            $st_log = $db->prepare($sql_log);
            $st_log->bindValue(':user', $me->twitter_id ,\PDO::PARAM_STR);
            $st_log->execute();
            $now_log=$st_log->fetchAll(PDO::FETCH_ASSOC);
            if(count($now_log) > 10){
                $i=count($now_log) - 10;
            }else{
                $i=0;
            }
            for($i; $i<count($now_log); $i++){
                if($now_log[$i]['first_player'] == $me->twitter_id){
                    echo "<tr align='center'>";
                    echo "<td>";
                    echo h($now_log[$i]['first_rating']);
                    echo "</td>";
                    echo "<td>";
                    $sql_log_win = "select * from users where twitter_id = :id";
                    $sql_log_win_st = $db->prepare($sql_log_win);
                    $sql_log_win_st->bindValue(':id', $now_log[$i]['second_player'] ,\PDO::PARAM_STR);
                    $sql_log_win_st->execute();
                    $win_log=$sql_log_win_st->fetchAll(PDO::FETCH_ASSOC);
                    echo h($win_log[0]['user_name']);                    
                    echo "</td>";
                    echo "<td>";
                    echo h($now_log[$i]['second_rating']);                                        
                    echo "</td>";
                    echo "<td>";
                    if($now_log[$i]['first_result'] == 0){
                        echo "×";
                        echo "</td>";
                        echo "<td>";
                        $variation = 16+($now_log[$i]['first_rating'] - $now_log[$i]['second_rating'])*0.04;
                        $variation = floor($variation);
                        echo h($variation);
                        echo "↓";
                    }elseif($now_log[$i]['first_result'] == 1){
                        echo "〇";
                        echo "</td>";
                        echo "<td>";
                        $variation = 16+($now_log[$i]['second_rating'] - $now_log[$i]['first_rating'])*0.04;
                        $variation = floor($variation);
                        echo h($variation);
                        echo "↑";
                    }else{
                        echo "不明";
                        echo "</td>";
                        echo "<td>";
                        echo "不明";                        
                    }
                    echo "</td>";
                    echo "</tr>";
                }elseif($now_log[$i]['second_player'] == $me->twitter_id){
                    echo "<tr align='center'>";
                    echo "<td>";
                    echo h($now_log[$i]['second_rating']);
                    echo "</td>";
                    echo "<td>";
                    $sql_log_lose = "select * from users where twitter_id = :id";
                    $sql_log_lose_st = $db->prepare($sql_log_lose);
                    $sql_log_lose_st->bindValue(':id', $now_log[$i]['first_player'] ,\PDO::PARAM_STR);
                    $sql_log_lose_st->execute();
                    $lose_log=$sql_log_lose_st->fetchAll(PDO::FETCH_ASSOC);
                    echo h($lose_log[0]['user_name']);                                        
                    echo "</td>";
                    echo "<td>";
                    echo h($now_log[$i]['first_rating']);                                        
                    echo "</td>";
                    echo "<td>";
                    if($now_log[$i]['second_result'] == 0){
                        echo "×";
                        echo "</td>";
                        echo "<td>";
                        $variation = 16+($now_log[$i]['second_rating'] - $now_log[$i]['first_rating'])*0.04;
                        $variation = floor($variation);
                        echo h($variation);
                        echo "↓";
                    }elseif($now_log[$i]['second_result'] == 1){
                        echo "〇";
                        echo "</td>";
                        echo "<td>";
                        $variation = 16+($now_log[$i]['first_rating'] - $now_log[$i]['second_rating'])*0.04;
                        $variation = floor($variation);
                        echo h($variation);
                        echo "↑";
                    }else{
                        echo "不明";
                        echo "</td>";
                        echo "<td>";
                        echo "不明";  
                    }
                    echo "</td>";
                    echo "</tr>";
                }else{
                    continue;
                }
            }
            echo "</table>"
            
            ?>
            


            <?php else : ?>
                <p>ログインにはTwitterアカウントが必要です。</p>
                <div class="login_button">
                    <a href="login.php"><img src="signin_button.png"></a>
                </div>
            <?php endif; ?>
        </main>
	</div><!-- /.container -->
</section>

<!-- ========== /main ========== -->

<!-- ========== footer ========== -->
<footer>
	<script>footer('../');</script> 
</footer>
<!-- ========== /footer ========== -->
<script type="text/javascript"> 
function check(){
	var flag = 0;
	// 設定開始（必須にする項目を設定してください）
	if(document.form1.user_name.value == ""){ // 「お名前」の入力をチェッ
		flag = 1;
	}
    flag1 = 1;
    for(var k = 0; k < document.form1.play_character.length; k ++){
        if(document.form1.play_character[k].checked){
            flag1 = 0; break;
        }
    }
	flag2 = 1;
    for(var i = 0; i < document.form1.connection.length; i ++){
        if(document.form1.connrction[i].checked){
            flag2 = 0; break;
        }
    }
    flag3 = 1;
    for(var j = 0; j < document.form1.rate_range.length; j ++){
        if(document.form1.rate_range[j].checked){
            flag3 = 0; break;
        }
    }
	// 設定終了

	if(flag || flag1 ||flag2 || flag3){
        $('#alert').html(<p style="color:red;">選択されていない項目があります</p>);
		return false; // 送信を中止
	}
	else{
		return true; // 送信を実行
	}

}

///////////////////////////

$("input[type=checkbox]").click(function(){
    var count = $("input[type=checkbox]:checked").length;
    var not = $('input[type=checkbox]').not(':checked')
 
        //チェックが3つ付いたら、チェックされてないチェックボックスにdisabledを加える
    if(count >= 3) {
        not.attr("disabled",true);
    }else{
        //3つ以下ならisabledを外す
        not.attr("disabled",false);
    }
});
</script>
</body>
</html>