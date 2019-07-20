<?php
include("class/template.class.php");
require_once 'class/dbConnect.class.php';

$tpl = new MyTemplate();

$db = new DBConnect();
$pdo = $db->getDb();
$results = $db->selectTeams($pdo);

foreach ($results as $result) {
    $tpl->t_name  = $result['name'];
    $tpl->team_id = $result['id'];
    $tpl->output('template/tchk.tpl.html',$result['id']);
}
?>