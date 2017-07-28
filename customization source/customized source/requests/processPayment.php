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
$uid = $_POST['uid'];
$type=$_POST['type'];

if($type=='Success') {

    $query = "UPDATE claim SET status='Success',paidAmount={$bill} WHERE id='{$id}'";
    $db->query($query);


    $query = "UPDATE users SET pending=pending-{$bill}, paid=paid+{$bill} WHERE idu={$uid}";
    $db->query($query);
    $db->close();
    echo json_encode("success");
}else{
    $query = "UPDATE claim SET status='Decline' WHERE id='{$id}'";
    $db->query($query);


    $query = "UPDATE users SET pending=pending-{$bill} WHERE idu={$uid}";
    $db->query($query);
    $db->close();
    echo json_encode("success");
}
?>