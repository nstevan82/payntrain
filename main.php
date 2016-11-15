<?php
/**
*Plugin Name: Pay and Train
*Plugin URI: http://www.taraba.in.rs
*Author: Stevan Nikolic
*Author URI: https://www.facebook.com/waka.waki.33
*Description: Data management about a sport club
*Version: 0.0.1
* Licenece: GPLv2
*/

//Exit if accessed directly
if (!defined('ABSPATH')){
exit;
}
/* plugin parts */
require_once(plugin_dir_path(__FILE__).'members.php');
require_once(plugin_dir_path(__FILE__).'payments.php');
require_once(plugin_dir_path(__FILE__).'classes.php');
require_once(plugin_dir_path(__FILE__).'payment_report.php');

/* declare all scripts that will be used in this plugin */
add_action('admin_enqueue_scripts','pnt_admin_enqueue_scripts');

function pnt_admin_enqueue_scripts(){

	global $pagenow,$typenow;	

		wp_enqueue_style('pnt-admin-css',plugins_url('css/style.css',__FILE__));

		wp_enqueue_script('pnt-club-js',plugins_url('js/admin-club.js',__FILE__),array('jquery','jquery-ui-datepicker'),'20150206',true);
		wp_enqueue_script('pnt-custom-quicktags',plugins_url('js/pnt-quicktags.js',__FILE__),array('quicktags'),'20150206',true);

}

/* register post type CLUB_MEMEBER */
function pnt_register_post_types(){
$pnt_member="member";
$labels=array(
'name'=> 'Club '.$pnt_member.'s',
'singular_name'=>$pnt_member,
'add_name'=>'Add club member',
'add_new_item'=>'Add New '.$pnt_member,
'edit'=>'Edit',
'edit_item'=>'Edit '.$pnt_member,
'new_item'=>'New '.$pnt_member,
'view'=>'View '.$pnt_member,
'view_item'=>'View '.$pnt_member,
'search_term'=>'Search '.$pnt_member,
'parent'=>'Parent '.$pnt_member,
'not_found'=>'No'.$pnt_member.'s found',
'not_found_in_trash'=>'No '.$pnt_member.'s in Trash'
);
$args=array(
'labels'=>$labels,
'public'=>true,
'publicly_queryable'=>true,
'exclude_from_search'=>false,
'show_in_nav_menus'=>true,
'show_ui'=>true,
'show_in_menu'=>'my-theme-settings-menu',
'show_in_admin_bar'=>true,
'menu_position'=>10,
'menu_icon'=>'dashicons-businessman',
'can_export'=>true,
'delete_with_user'=>false,
'hierarchical'=>false,
'has_archive'=>true,
'query_var'=>true,
'capability_type'=>'post',
'map_meta_cap'=>true,
'rewrite'=>array(
	'slug'=>'members',
	'with_front'=>true,
	'pages'=>true,
	'feeds'=>true,
),
'supports'=>array('title',
// 'editor', - text-area
//'author', - drop down menu - author
//'custom-fields',
'thumbnail')
);
register_post_type('club_member',$args);





/* register post type CLUB_PAYMENT */

$pnt_payment="payment";
$labels=array(
'name'=> 'Club '.$pnt_payment.'s',
'singular_name'=>$pnt_payment,
'add_name'=>'Add payment',
'add_new_item'=>'Add New '.$pnt_payment,
'edit'=>'Edit',
'edit_item'=>'Edit '.$pnt_payment,
'new_item'=>'New '.$pnt_payment,
'view'=>'View '.$pnt_payment,
'view_item'=>'View '.$pnt_payment,
'search_term'=>'Search '.$pnt_payment,
'parent'=>'Parent '.$pnt_payment,
'not_found'=>'No'.$pnt_payment.'s found',
'not_found_in_trash'=>'No '.$pnt_payment.'s in Trash'
);
$args=array(
'labels'=>$labels,
'public'=>true,
'publicly_queryable'=>true,
'exclude_from_search'=>false,
'show_in_nav_menus'=>true,
'show_ui'=>true,
'show_in_menu'=>'my-theme-settings-menu',
'show_in_admin_bar'=>true,
'menu_position'=>11,
'menu_icon'=>'dashicons-pressthis',
'can_export'=>true,
'delete_with_user'=>false,
'hierarchical'=>false,
'has_archive'=>true,
'query_var'=>true,
'capability_type'=>'post',
'map_meta_cap'=>true,
'rewrite'=>array(
	'slug'=>'payments',
	'with_front'=>true,
	'pages'=>true,
	'feeds'=>true,
),
'supports'=>array('title',
// 'editor', - text-area
//'author', - drop down menu - author
//'custom-fields',
'thumbnail')
);
register_post_type('club_payment',$args);





/* register post type CLUB_CLASS */

$pnt_class="class";
$labels=array(
'name'=> 'Club '.$pnt_class.'es',
'singular_name'=>$pnt_class,
'add_name'=>'Add class',
'add_new_item'=>'Add New '.$pnt_class,
'edit'=>'Edit',
'edit_item'=>'Edit '.$pnt_class,
'new_item'=>'New '.$pnt_class,
'view'=>'View '.$pnt_class,
'view_item'=>'View '.$pnt_class,
'search_term'=>'Search '.$pnt_class,
'parent'=>'Parent '.$pnt_class,
'not_found'=>'No'.$pnt_class.'s found',
'not_found_in_trash'=>'No '.$pnt_class.'s in Trash'
);
$args=array(
'labels'=>$labels,
'public'=>true,
'publicly_queryable'=>true,
'exclude_from_search'=>false,
'show_in_nav_menus'=>true,
'show_ui'=>true,
'show_in_menu'=>'my-theme-settings-menu',
'show_in_admin_bar'=>true,
'menu_position'=>12,
'menu_icon'=>'dashicons-list-view',
'can_export'=>true,
'delete_with_user'=>false,
'hierarchical'=>false,
'has_archive'=>true,
'query_var'=>true,
'capability_type'=>'post',
'map_meta_cap'=>true,
'rewrite'=>array(
	'slug'=>'classes',
	'with_front'=>true,
	'pages'=>true,
	'feeds'=>true,
),
'supports'=>array('title',
'thumbnail')
);
register_post_type('club_class',$args);
}
add_action('init','pnt_register_post_types');


/* Add action for ajax on club report page when dropdown menu is clicked */

add_action( 'wp_ajax_my_actiona', 'my_action_callbacka' );

function my_action_callbacka() {

$posts = get_posts(array(
    'post_type'   => 'club_payment',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'fields' => 'ids',
	   'meta_query' => array(
        array(
            'key' => 'month',
            'value' => $_POST['month'],
            'compare' => 'LIKE'
        )
		),
	  'meta_query' => array(
        array(
            'key' => 'year',
            'value' => $_POST['year'],
            'compare' => 'LIKE'
        )
		),
         array(
			'key' => 'payment_member',
			'value' => $_POST['payment_member'],
			'compare' => 'LIKE'
        )
	)
);
//loop over each post
echo "<table class='report' border=1>";	
echo "<tr><th>MEMBER</th><th>PAYMENT DATE</th><th>AMOUNT</th><th>PAID FOR THE MONTH</th><th>YEAR</th></tr>";
foreach($posts as $p){
    //get the meta you need form each post
    $amount = get_post_meta($p,"amount",true);
	$date = get_post_meta($p,"date",true);
	$month = get_post_meta($p,"month",true);
	$year = get_post_meta($p,"year",true);
	$payment_member = get_post_meta($p,"payment_member",true);
	//<td>".get_the_title().'</td>
	echo '<tr><td>'.$payment_member.'</td><td>'.$date.'</td><td>'.$amount.'</td><td>'.$month.'</td><td>'.$year.'</td></tr>';

    //do whatever you want with it
}
echo "</table>";

	wp_die(); // this is required to terminate immediately and return a proper response
}


// put the parent menu for this module link on admin page
function register_my_theme_settings_menu() {
    add_menu_page(
        "Pay-and-train",
        "Pay-and-train",
        "manage_options",
        "my-theme-settings-menu"
    );
/* add CLUB REPORT link on the dashboard page menu */
	add_submenu_page('my-theme-settings-menu', __('Club report','Pay-and-train'), __('Club report','Pay-and-train'),'manage_options', 'club_report', 'myplguin_admin_page');
}

 
add_action( "admin_menu", "register_my_theme_settings_menu");

function mt_settings_page(){}

















?>