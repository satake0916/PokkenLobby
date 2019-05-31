<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');

try {
    $db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $rate_rows = $db->query('SELECT * FROM rate_match')->fetchAll(PDO::FETCH_ASSOC);
    $user_rows = $db->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);    
}catch(\PDOException $e){
    //header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}



function getData($id) {
    global $db;
    $sql_entry = "select * from rate_match where id = :id";
    $st_entry_sql = $db->prepare($sql_entry);      
    $st_entry_sql->bindValue('id', $id, \PDO::PARAM_INT);
    $st_entry_sql->execute();
    $room = $st_entry_sql->fetchAll(PDO::FETCH_ASSOC);
    return $room[0];
}

function getUpdatedData($id) {
    $temp = getData($id);
    for($i; $i<60; $i++){
        if($temp['second_player']==0 && $temp['battle']==1){
            $temp = getData($id);
            sleep(10);
        }else{
            break;
        }
    }
	return $temp;
}

if (isset($_GET['id'])) {
    $data = getUpdatedData($_GET['id']);
    if($data['second_player'] == 0){
        echo "対戦相手が見つかりませんでした。";
    }else{
        echo "
        <table>
        <tr>
            <td>プレイヤー1</td>
            <td><?= h(",$data['first_user_name'],") ?>
                <div class='rate_character'>img src='../images/<?=",$data['first_character1'],"?>.png'></div>
                <div class='rate_character'>img src='../images/<?=",$data['first_character2'],"?>.png'></div>
                <div class='rate_character'>img src='../images/<?=",$data['first_character3'],"?>.png'></div>
            </td>
            <td><?= h(",$data['first_rating'],") ?></td>
        </tr>
        <tr>
            <td>プレイヤー2</td>
            <td><?= h(",$data['second_user_name'],") ?>
                <div class='rate_character'>img src='../images/<?=",$data['second_character1'],"?>.png'></div>
                <div class='rate_character'>img src='../images/<?=",$data['second_character2'],"?>.png'></div>
                <div class='rate_character'>img src='../images/<?=",$data['second_character3'],"?>.png'></div>
            </td>
            <td><?= h(",$data['second_rating'],") ?></td>
        </tr>
        </table>
        "
    }
    }
    

