<?php
/* function that describes the form for classes */
function pnt_meta_classes_callback($post){
wp_nonce_field(basename(__FILE__), 'pnt_classes_nonce'); /* nonce for security*/
$pnt_stored_meta=get_post_meta($post->ID);

?>



<?php
$args=array( 'post_type' => 'club_member' );
// The Query
$the_query = new WP_Query( $args );

// The Loop
if ( $the_query->have_posts() ) {
	
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		
		if (in_array(get_the_title(), $pnt_stored_meta['member_checked'])) {		
			echo '<input name="member_checked[]" type="checkbox"  value='.get_the_title().' checked>' . get_the_title().'<br>';			
		}
		else{
		echo '<input name="member_checked[]" type="checkbox" value='.get_the_title().'>' . get_the_title().'<br>' ;
		}
	}
	
	

	/* Restore original Post Data */
	wp_reset_postdata();
} else {
	// no posts found
}
	
?>



<?php
$content=get_post_meta($post->ID,'principle_duties'.true);
$editor='principle_duties';
$settings=array(
'textarea_rows'=>8,
);
}
function pnt_class_save($post_id){

//checks save status
$is_autosave=wp_is_post_autosave($post_id);
$is_revision=wp_is_post_revision($post_id);
$is_valid_nonce=(isset($_POST['pnt_classes_nonce'])) && wp_verify_nonce($_POST['pnt_classes_nonce'],basename(__FILE__))? 'true':'false';
//exits script depending on save status



if ($is_autosave || $is_revision || !$is_valid_nonce){
/* if post not valid or is autosave or revision dont save the changes */
echo "error";die();
return;
}

if (isset($_POST['member_checked'])){

	delete_post_meta($post_id,'member_checked');	

foreach ($_POST['member_checked'] as $key=>$member_checked){
if (in_array($member_checked, $pnt_stored_meta['member_checked'])) {	
if (isset($member_checked)){
update_post_meta($post_id,'member_checked', sanitize_text_field($member_checked));
}
else {
	
}
}
else{
add_post_meta($post_id,'member_checked', sanitize_text_field($member_checked));
}
}

}
}
/* add action for saving classes posts */
add_action('save_post','pnt_class_save');

/* create classes metabox */
function pnt_add_classes_metabox(){
add_meta_box(
'pnt_meta',
'List of members present on this class',
'pnt_meta_classes_callback', /*a function which represents the form*/
'club_class',
'normal', /*metabox position*/
'low'
);
}
/* add action classes metabox */
add_action('add_meta_boxes','pnt_add_classes_metabox');
?>