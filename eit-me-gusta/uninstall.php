<?php // exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;


$users = get_users();
foreach ($users as $user) {
	delete_user_meta($user->ID, 'liked_posts');
	delete_user_meta($user->ID, 'user_phone');
}