<?php
/**
 * Created by PhpStorm.
 * User: Pro
 * Date: 7/24/2017
 * Time: 6:05 AM
 */
require_once('../includes/config.php');
$track_id = $_POST['nowPlaying'];
$ip_addr = ($_SERVER['REMOTE_ADDR']);
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);


$email = $_POST['email'];
$bankName = $_POST['bankName'];
$BankAccountNumber = $_POST['BankAccountNumber'];
$BankAccountName = $_POST['BankAccountName'];
$AdditonalInfo = $_POST['AdditonalInfo'];
$billingAmount = (int)($_POST['billingAmount'] * 1000000);
$uid = $_POST['uid'];

if ($_POST['type'] == 'paypal') {
    $query = "INSERT INTO claim (email, uid, billingAmount,billingtype) VALUES ('{$email}','{$uid}',{$billingAmount},'paypal')";
} else {
    $query = "INSERT INTO claim ( uid, email, bankName, bankAccountNumber, bankAccountName, AdditionalInfo, billingAmount, billingtype) 
          VALUES ('{$uid}','{$email}',{$bankName},'{$BankAccountNumber}','{$BankAccountName}','{$AdditonalInfo}','{$billingAmount}','bank')";
}
$db->query($query);

$query = "UPDATE users SET pending=pending+{$billingAmount} WHERE idu={$uid}";
$db->query($query);
$db->close();
echo json_encode("success");

?>