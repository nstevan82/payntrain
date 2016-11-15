<?php
/* the form for members */
function pnt_meta_members_callback($post){
wp_nonce_field(basename(__FILE__), 'pnt_members_nonce'); /* nonce for security*/
$pnt_stored_meta=get_post_meta($post->ID);

?>
<table>
<tr><td><label for="parent">Parent name</label></td><td>
<input id="parent" type="text" name="parent" value="<?php if (!empty($pnt_stored_meta['parent'])) echo esc_attr($pnt_stored_meta['parent'][0]);?>"></td></tr>
<tr><td><label for="jmbg">ID number(jmbg)</label></td><td>
<input id="jmbg" type="text" name="jmbg" value="<?php if (!empty($pnt_stored_meta['jmbg'])) echo esc_attr($pnt_stored_meta['jmbg'][0]);?>"></td></tr>
<tr><td><label for="id">ID number (short)</label></td><td>
<input id="id" type="text" name="id" value="<?php if (!empty($pnt_stored_meta['id'])) echo esc_attr($pnt_stored_meta['id'][0]);?>"></td></tr>
<tr><td><label for="birthdate">Birthdate</label></td><td>
<input id="birthdate" type="text" name="birthdate" class='datepicker' value="<?php if (!empty($pnt_stored_meta['birthdate'])) echo esc_attr($pnt_stored_meta['birthdate'][0]);?>"></td></tr>
<tr><td><label for="birthplace">Birthplace</label></td><td>
<input id="birthplace" type="text" name="birthplace" value="<?php if (!empty($pnt_stored_meta['birthplace'])) echo esc_attr($pnt_stored_meta['birthplace'][0]);?>"></td></tr>
<tr><td><label for="phone">Phone</label></td><td>
<input id="phone" type="text" name="phone" value="<?php if (!empty($pnt_stored_meta['phone'])) echo esc_attr($pnt_stored_meta['phone'][0]);?>"></td></tr>
<tr><td><label for="address">Address</label></td><td>
<input id="address" type="text" name="address" value="<?php if (!empty($pnt_stored_meta['address'])) echo esc_attr($pnt_stored_meta['address'][0]);?>"></td></tr>
<tr><td><label for="post">City Post number</label></td><td>
<input id="post" type="text" name="post" value="<?php if (!empty($pnt_stored_meta['post'])) echo esc_attr($pnt_stored_meta['post'][0]);?>"></td></tr>
<tr><td><label for="joined">Date joined</label></td><td>
<input id="joined" type="text" name="joined"  class='datepicker' value="<?php if (!empty($pnt_stored_meta['joined'])) echo esc_attr($pnt_stored_meta['joined'][0]);?>"></td></tr>
<tr><td><label for="email">Email</label></td><td>
<input id="email" type="text" name="email" value="<?php if (!empty($pnt_stored_meta['email'])) echo esc_attr($pnt_stored_meta['email'][0]);?>"></td></tr>
</table>
<?php
$content=get_post_meta($post->ID,'principle_duties'.true);
$editor='principle_duties';
$settings=array(
'textarea_rows'=>8,
);
}
/* saving and updating members data */
function pnt_member_save($post_id){

//checks save status
$is_autosave=wp_is_post_autosave($post_id);
$is_revision=wp_is_post_revision($post_id);
$is_valid_nonce=(isset($_POST['pnt_members_nonce'])) && wp_verify_nonce($_POST['pnt_members_nonce'],basename(__FILE__))? 'true':'false';
//exits script depending on save status



if ($is_autosave || $is_revision || !$is_valid_nonce){
/* if post not valid or is autosave or revision dont save the changes */
return;
}
/* if the page has been submited update the member data */
if (isset($_POST['phone'])){

update_post_meta($post_id,'phone', sanitize_text_field($_POST['phone']));
update_post_meta($post_id,'address', sanitize_text_field($_POST['address']));
update_post_meta($post_id,'joined', sanitize_text_field($_POST['joined']));
update_post_meta($post_id,'email', sanitize_text_field($_POST['email']));
update_post_meta($post_id,'jmbg', sanitize_text_field($_POST['jmbg']));
update_post_meta($post_id,'id', sanitize_text_field($_POST['id']));
update_post_meta($post_id,'post', sanitize_text_field($_POST['post']));
update_post_meta($post_id,'parent', sanitize_text_field($_POST['parent']));
update_post_meta($post_id,'birthdate', sanitize_text_field($_POST['birthdate']));
update_post_meta($post_id,'birthplace', sanitize_text_field($_POST['birthplace']));
}
}
/* add action for saving a member */
add_action('save_post','pnt_member_save');
/* add metabox for members and call pnt_meta_members_callback() which contains the members form code */
function pnt_add_members_metabox(){
add_meta_box(
'pnt_meta',
'MEMBER DATA',
'pnt_meta_members_callback', /*a function which represents the form*/
'club_member',
'normal', /*metabox position*/
'low'
);
}
/* add action for members metabox */
add_action('add_meta_boxes','pnt_add_members_metabox');






?>