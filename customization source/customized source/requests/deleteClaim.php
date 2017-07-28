<?php
/**
 * Created by PhpStorm.
 * User: Pro
 * Date: 7/24/2017
 * Time: 6:05 AM
 */
require_once('../includes/config.php');


$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);


$id = $_POST['rowId'];
$bill = $_POST['bill'];
$uid=$_POST['uid'];



$query = "DELETE FROM claim WHERE id='{$id}'";
$db->query($query);


$query = "UPDATE users SET pending=pending-{$bill} WHERE idu={$uid}";
$db->query($query);
$db->close();
echo json_encode("success");

?>