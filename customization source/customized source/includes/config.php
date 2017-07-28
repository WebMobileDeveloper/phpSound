<?php
error_reporting(0);

$CONF = $TMPL = array();

// The MySQL credentials
$CONF['host'] = '104.168.188.87';
$CONF['user'] = 'okkbitxn_php';
$CONF['pass'] = 'Norway456';
$CONF['name'] = 'okkbitxn_php';
$CONF['port'] = '3306';

// The Installation URL
//$CONF['url'] = 'http://localhost.com';
$CONF['url'] = 'http://mytune3ree.com';

// The Notifications e-mail
$CONF['email'] = 'notifications@yourdomain.com';

// The themes directory
$CONF['theme_path'] = 'themes';

$action = array('admin' => 'admin',
    'explore' => 'explore',
    'stream' => 'stream',
    'settings' => 'settings',
    'messages' => 'messages',
    'track' => 'track',
    'playlist' => 'playlist',
    'upload' => 'upload',
    'recover' => 'recover',
    'profile' => 'profile',
    'stats' => 'stats',
    'payment' => 'payment',
    'pro' => 'pro',
    'notifications' => 'notifications',
    'search' => 'search',
    'page' => 'page',
    'welcome' => 'welcome'
);
?>