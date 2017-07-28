<?php
/**
 * Created by PhpStorm.
 * User: Pro
 * Date: 7/24/2017
 * Time: 6:05 AM
 */
require_once('../includes/config.php');

$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);


$earnings = (int)($_POST['earnings']*1000000);
$pending = (int)($_POST['pending']*1000000);
$uid = $_POST['uid'];
$active=$_POST['active'];

    $query = "UPDATE users SET earnings={$earnings}, pending={$pending},active='{$active}' WHERE idu={$uid}";
    $db->query($query);
    $db->close();
    echo json_encode("success");

?>