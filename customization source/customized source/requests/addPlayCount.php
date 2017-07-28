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


// get Was it played by IP,track_id?
$query = "SELECT * FROM played WHERE track_id= {$track_id} and ip='{$ip_addr}'";
$select_rows = $db->query($query);


if ($select_rows) {

    if ($select_rows->num_rows > 0) {
        echo json_encode($select_rows->num_rows);
    } else {


        //get user id
        $query = "SELECT * FROM tracks WHERE id= {$track_id}";
        $select_rows = $db->query($query);
        while ($row = $select_rows->fetch_assoc()) {
            $uid = $row['uid'];
        }

        $query = "SELECT * from users WHERE idu={$uid}";
        $select_rows = $db->query($query);
        while ($row = $select_rows->fetch_assoc()) {
            $active = $row['active'];
        }
        if ($active == 'active') {
            //insert new played data.
            $query = "INSERT INTO played (track_id, ip, uid) VALUES ({$track_id},'{$ip_addr}',{$uid})";
            $db->query($query);

            //increase user earnings.  unit $1/1000000
            $query = "SELECT * from settings";
            $select_rows = $db->query($query);
            while ($row = $select_rows->fetch_assoc()) {
                $ip_counts = $row['ip_counts'];
                $unit_cost = $row['unit_cost'];
            }
            $unit_cost /= $ip_counts;
            $unit_cost = round($unit_cost * 1000000);
            $query = "UPDATE users SET earnings=earnings+{$unit_cost} WHERE idu={$uid}";

            $db->query($query, false);
            // echo json_encode($query);
        }
    }
}

$db->close();
?>