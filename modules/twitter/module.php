<?php

$Module = array( 'name' => 'Twitter Oauth' );

$ViewList = array();

$ViewList['redirect'] = array(
    'script' => 'redirect.php',
    'default_navigation_part' => 'pvrupdatesocial'
    );

$ViewList['callback'] = array(
    'script' => 'callback.php',
    'default_navigation_part' => 'pvrupdatesocial'
    );

?>