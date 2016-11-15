<?php
/* form on the payments page */
function pnt_meta_payments_callback($post){
wp_nonce_field(basename(__FILE__), 'pnt_payments_nonce'); /* nonce for security*/
$pnt_stored_meta=get_post_meta($post->ID);

?>

<table>
<tr><td><label for="amount">Member</label></td><td>
<?php
$args=array( 'post_type' => 'club_member' );
// The Query
$the_query = new WP_Query( $args );
echo '<select name="payment_member">';
// The Loop
if ( $the_query->have_posts() ) {
	
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		if (get_the_title()==$pnt_stored_meta['payment_member'][0]){		
			echo '<option  value='.get_the_title().' selected>' . get_the_title() . '</option>';			
		}
		else{
		echo '<option value='.get_the_title().'>' . get_the_title() . '</option>';
		}
	}
	
	echo '</select><br>';

	/* Restore original Post Data */
	wp_reset_postdata();
} else {
	// no posts found
}
	
?>
</td></tr>


<tr><td><label for="amount">Amount</label></td><td>
<input id="amount" type="text" name="amount" value="<?php if (!empty($pnt_stored_meta['amount'])) echo esc_attr($pnt_stored_meta['amount'][0]);?>"></td></tr>
<tr><td><label for="date">date</label></td><td>
<input id="date" type="text" name="date"  class='datepicker' value="<?php if (!empty($pnt_stored_meta['date'])) echo esc_attr($pnt_stored_meta['date'][0]);?>"></td></tr>
<tr><td colspan=2>Membership paid for the month:<br>
<select name="month">
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="1") echo "selected";?> value="1">January</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="2") echo "selected";?> value="2">February</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="3") echo "selected";?> value="3">March</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="4") echo "selected";?> value="4">April</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="5") echo "selected";?> value="5">May</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="6") echo "selected";?> value="6">Jun</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="7") echo "selected";?> value="7">July</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="8") echo "selected";?> value="8">August</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="9") echo "selected";?> value="9">September</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="10") echo "selected";?> value="10">October</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="11") echo "selected";?> value="11">November</option>
<option <?php if (!empty($pnt_stored_meta['month']) && $pnt_stored_meta['month'][0]=="12") echo "selected";?> value="12">December</option>
</select>

And year:
<input id="date" type="text" name="year" value="<?php if (!empty($pnt_stored_meta['year'])) echo esc_attr($pnt_stored_meta['year'][0]);?>"></td></tr>
</table>
<?php
$content=get_post_meta($post->ID,'principle_duties'.true);
$editor='principle_duties';
$settings=array(
'textarea_rows'=>8,
);
}
/* update the data for payments */
function pnt_payment_save($post_id){

//checks save status
$is_autosave=wp_is_post_autosave($post_id);
$is_revision=wp_is_post_revision($post_id);
$is_valid_nonce=(isset($_POST['pnt_payments_nonce'])) && wp_verify_nonce($_POST['pnt_payments_nonce'],basename(__FILE__))? 'true':'false';
//exits script depending on save status



if ($is_autosave || $is_revision || !$is_valid_nonce){
/* if post not valid or is autosave or revision dont save the changes */
echo "error: ";die();
return;
}
/* if the page is submited do the update */
if (isset($_POST['amount'])){

update_post_meta($post_id,'payment_member', sanitize_text_field($_POST['payment_member']));
update_post_meta($post_id,'amount', sanitize_text_field($_POST['amount']));
update_post_meta($post_id,'date', sanitize_text_field($_POST['date']));
update_post_meta($post_id,'month', sanitize_text_field($_POST['month']));
update_post_meta($post_id,'year', sanitize_text_field($_POST['year']));
}
}
/* add the action for saving the post and call pnt_payment_save() */
add_action('save_post','pnt_payment_save');

/* add payments metabox */
function pnt_add_payments_metabox(){
add_meta_box(
'pnt_meta',
'List of payments',
'pnt_meta_payments_callback', /*a function which represents the form*/
'club_payment',
'normal', /*metabox position*/
'low'
);
}
/* add action for adding payments metabox */
add_action('add_meta_boxes','pnt_add_payments_metabox');
?>