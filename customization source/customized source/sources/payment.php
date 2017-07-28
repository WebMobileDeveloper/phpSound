<?php


function PageMain()
{
    global $TMPL, $LNG, $CONF, $db, $loggedIn, $settings;

    if (isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
        $verify = $loggedIn->verify();

        if (empty($verify['username'])) {
            // If fake cookies are set, or they are set wrong, delete everything and redirect to home-page
            $loggedIn->logOut();
            header("Location: " . $CONF['url'] . "/index.php?a=welcome");
        } else {
            // Start displaying the Feed
            $feed = new feed();
            $feed->db = $db;
            $feed->url = $CONF['url'];
            $feed->user = $verify;
            $feed->id = $verify['idu'];
            $feed->username = $verify['username'];
            $feed->time = $settings['time'];


            $TMPL_old = $TMPL;
            $TMPL = array();
            $skin = new skin('payment/rows');


            $TMPL['plays'] = 0;
            $query = "SELECT COUNT(*) as plays FROM played WHERE uid= {$feed->id}";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $TMPL['plays'] = $row['plays'];
            }


            $query = "SELECT *  FROM users WHERE idu= {$feed->id}";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $TMPL['earnings'] = $row['earnings'] / 1000000;
                $TMPL['pending'] = $row['pending'] / 1000000;
                $TMPL['paid'] = $row['paid'] / 1000000;
                $TMPL['available'] = ($row['earnings'] - $row['pending'] - $row['paid']) / 1000000;
            }

            $query = "SELECT * from settings";
            $select_rows = $db->query($query);
            while ($row = $select_rows->fetch_assoc()) {
                $TMPL['minimum'] = $row['minimum_limit'];
                $TMPL['ip_counts'] = $row['ip_counts'];
                $TMPL['unit_cost'] = $row['unit_cost'];
            }


            if ($TMPL['available'] < $TMPL['minimum']) {
                $TMPL['message'] = "<br><br>Claims can only be made when the amount of your earnings exceeds $" . $TMPL['minimum'] . ".";
                $TMPL['show_flag'] = "hidden";
            }


            $query = "SELECT * from users WHERE idu={$feed->id}";
            $select_rows = $db->query($query);
            while ($row = $select_rows->fetch_assoc()) {
                $active = $row['active'];
            }
            if ($active != 'active') {
                $TMPL['message'] = "<br><br>Your account has been blocked.";
                $TMPL['show_flag'] = "hidden";
            }

            $TMPL['claim'] = '';
            $TMPL['uid'] = $feed->id;

            $query = "SELECT * from claim WHERE uid={$TMPL['uid']} ORDER BY DATE DESC ";
            $select_rows = $db->query($query);
            $TMPL['claims'] = "<table style='text-align: center;'><thead><th>No</th><th>Type</th><th>Paypal email</th><th>bankName</th><th>Account Number</th><th>Account Name</th>
                                <th>Additional Info</th><th>Claim Amount</th><th>Real Paid Amount</th><th>status</th><th>Date</th><th>delete</th></thead>";
            $i=0;
            //  billingAmount, , date
            while ($row = $select_rows->fetch_assoc()) {
                $i++;
                $paid=$row['paidAmount']*0.9/1000000;
                $bill=$row['billingAmount']/1000000;
                $id=$row['id'];
                $status=$row['status'];
                $tr = "<tr>
                    <td>{$i}</td>
                    <td>{$row['billingtype']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['bankName']}</td>
                    <td>{$row['bankAccountNumber']}</td>
                    <td>{$row['bankAccountName']}</td>
                    <td>{$row['AdditionalInfo']}</td>
                    <td>{$bill}</td>
                    <td>{$paid}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['date']}</td>
                    <td><button style='width:30px;' rowId='{$id}' status='{$status}' bill='{$bill}' class='delete-button'></button></td>
                    </tr>";
                $TMPL['claims'] .= $tr;
            }
            $TMPL['claims'] .= "</table>";

            //played_id, track_id, ip, uid, date
            $query="SELECT count(played_id) as counts FROM played WHERE DATE(`date`) = CURDATE() and uid={$feed->id}";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $TMPL['earnings_today']=$row['counts']/$TMPL['ip_counts']*$TMPL['unit_cost'];
                $TMPL['plays_today']=$row['counts'];
            }

            $query="SELECT count(played_id) as counts FROM played WHERE date > DATE_SUB(NOW(), INTERVAL 1 WEEK) and uid={$feed->id}";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $TMPL['earnings_week']=$row['counts']/$TMPL['ip_counts']*$TMPL['unit_cost'];
                $TMPL['plays_week']=$row['counts'];
            }

            $query="SELECT count(played_id) as counts FROM played WHERE date > DATE_SUB(NOW(), INTERVAL 1 MONTH) and uid={$feed->id}";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $TMPL['earnings_month']=$row['counts']/$TMPL['ip_counts']*$TMPL['unit_cost'];
                $TMPL['plays_month']=$row['counts'];
            }

            $query="SELECT count(played_id) as counts FROM played WHERE uid={$feed->id}";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $TMPL['earnings_total']=$row['counts']/$TMPL['ip_counts']*$TMPL['unit_cost'];
                $TMPL['plays_total']=$row['counts'];
            }


            $rows = $skin->make();
            $TMPL = $TMPL_old;
            unset($TMPL_old);
            $TMPL['rows'] = $rows;
            //$TMPL['sidebar'] = $sidebar;
        }

    } else {
        // If the session or cookies are not set, redirect to home-page
        header("Location: " . $CONF['url'] . "/index.php?a=welcome");
    }

    if (isset($_GET['logout']) == 1) {
        $loggedIn->logOut();
        header("Location: " . $CONF['url'] . "/index.php?a=welcome");
    }

    $TMPL['url'] = $CONF['url'];
    $TMPL['title'] = $LNG['payment'] . ' - ' . $settings['title'];

    $skin = new skin('payment/content');

    return $skin->make();
}

?>