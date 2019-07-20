<?php
require("../const.php");

class DBConnect
{
    function getDb(){
        // データベースへの接続を確認
        $pdo = new PDO ('mysql:dbname=cmk2; host=127.0.0.1;port=3306; charset=utf8', USER, PASSWD);
        return $pdo;
    }

    function selectTeams($pdo) {
        $results = $pdo->query ( 'select * from teams' );
        return $results;
    }

    function indexTeams($pdo) {
        $results = $pdo->query ( 'select * from teams where id != 0 and delete_flg = 0' );
        return $results;
    }

    function selectMember2($pdo,$team_id) {

        $query = 'select * from members where team_id = '.$team_id;

        $results = $pdo->query ( $query);

        return $results;
    }


    function selectMember($pdo,$district_id, $area_id, $team_id) {
        $query = "";

        if ($team_id != 1) {
            $query = 'select * from members where district_id = '.$district_id .' and area_id = '.$area_id .' and team_id in (1,'.$team_id .')';
        } else {
            $query = 'select * from members where district_id = '.$district_id .' and area_id = '.$area_id .' and team_id = '.$team_id;
        }

        $results = $pdo->query ( $query);

        return $results;
    }

    function selectMailAddress($pdo) {
        $query = "select * from mails";
        $results = $pdo->query ( $query);
        return $results;
    }

    function insertData($pdo, $staff_id, $name, $address, $team, $member, $value, $created_at) {
        $query = 'INSERT INTO strong_points (staff_id,name,address,team,member,value,created_at)VALUES(\'' .$staff_id .'\',\'' .$name .'\',\'' .$address .'\',\'' .$team .'\',\'' .$member .'\',\'' .$value .'\',\'' .$created_at .'\');';
        $results = $pdo->query ( $query);
        return 0;
    }
}
?>