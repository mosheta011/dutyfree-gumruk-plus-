<?php
require_once 'wp-load.php';
$user = wp_get_current_user();
if($user->ID == 0) {
    $users = get_users(['role' => 'administrator']);
    if(!empty($users)) {
        $user = $users[0];
    }
}
echo "Admin ID: " . $user->ID;
