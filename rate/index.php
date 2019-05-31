<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');

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
<title>ポッ拳ロビー：レート</title>
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

<section class="main">
    <div class="container">

    
        <main>
            <h1>レート</h1>
            <h2>アクティブ人数: 
            <?php
            
            $spl = "select * from rate_match where battle = 0";
            $st = $db->query($spl);
            $now=$st->fetchAll(PDO::FETCH_ASSOC);
            $number0 = count($now);
            $spl2 = "select * from rate_match where battle = 1";
            $st2 = $db->query($spl2);
            $now2=$st2->fetchAll(PDO::FETCH_ASSOC);
            $number1 = count($now2) * 2;
            echo h($number0 + $number1);
            ?>
            人</h2>

        <form action="join.php" method="post" class="common_button">
            <input type="submit" value="レートに参加する">
        </form>

        <h1>ランキング</h1>
        <table class="ranking">
            <?php
            $sql_top = "select * from users where rate_win != 0 or rate_lose != 0 order by rating desc limit 0,100";
            $st_top = $db->query($sql_top);
            $now_top=$st_top->fetchAll(PDO::FETCH_ASSOC);
            for($i = 0; $i<count($now_top); $i++){
                echo "<tr>";
                echo "<td>";
                echo h($i+1);
                echo "位";
                echo "</td>";
                echo "<td>";
                echo h($now_top[$i]['user_name']);
                echo "<div class='ranking_character'><img src='../images/",$now_top[$i]['character1'],".png'></div>";
                if($now_top[$i]['character2'] != null){
                    echo "<div class='ranking_character'><img src='../images/",$now_top[$i]['character2'],".png'></div>";
                }
                if($now_top[$i]['character3'] != null){
                    echo "<div class='ranking_character'><img src='../images/",$now_top[$i]['character3'],".png'></div>";
                }
                echo "</td>";
                echo "<td>";
                echo h($now_top[$i]['rating']);
                echo "</td>";
                echo "</tr>";
            }
            ?>
            </table>
        </main>
    </div><!-- /.container -->
</section>

<!-- ========== /main ========== -->

<!-- ========== footer ========== -->
<footer>
		<script>footer('../');</script> 
</footer>
<!-- ========== /footer ========== -->
</body>
</html>