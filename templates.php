<?php
//get all templates
global $wpdb;
$sql = 'select * from wp_templates';
$result = $wpdb->get_results($sql);
?>
<h1>Templates</h1>
<form action="admin-post.php" method="post">
<input type="hidden" name="app_id" value="<?php echo $_REQUEST['app_id']; ?>">
<input type="hidden" name="action" value="add_application_template">
<table id="example" class="wp-list-table widefat fixed striped pages" cellspacing="0" width="50%">
    <thead>
        <tr>
			<th align="left" width="5%">&nbsp;</th>
            <th align="left">Templates</th>
            <th align="left">Order</th>
			<th align="left">Tab</th>
       </tr>
    </thead>
    <tbody>
<?php 
foreach ($result as $val) {
	$sql = 'select * from wp_application_templates where template_id ='.$val->id.' and program_id = '.$_REQUEST["app_id"].' ORDER BY template_render_order ASC';
	$result = $wpdb->get_row($sql);
	if(isset($result->template_id) && $val->id == $result->template_id) $checked = "checked"; else $checked = "";
    ?>
		<tr>
			<td><input type="checkbox" name="temp_id[] ?>" value="<?php echo $val->id; ?>" class="js-switch" <?php echo $checked; ?>></td>
			<td><?php echo $val->name; ?></td>
			<td><input type="text" name="template_render_order_<?php echo $val->id; ?>" value="<?php if(isset($result->template_render_order)){echo $result->template_render_order;}?>" size="2"></td>
			<td>
			<select name="tab_slug_<?php echo $val->id; ?>"> 
				<?php global $post; $args = array( 'numberposts' => -1); 
				$tabs = get_tab_slugs(); 
				foreach( $tabs as $tab ) : if($result->tab_slug == $tab->tab_slug) $selected = "selected"; else $selected = "";?>
				<option value="<?php echo $tab->tab_slug; ?>" <?php echo $selected; ?>><?php echo $tab->tab_name; ?></option> 
				<?php endforeach; ?> 
			</select>
			</td>
		</tr>
<?php } ?>
    <tbody>
</table>

<div class="tablenav bottom">
<?php submit_button( "Save Changes", 'button button-primary', "save_template", '') ?>
		
	</div>
</form>

<?php
function get_tab_slugs() {
global $wpdb;
$sql = 'select * from wp_tab_slugs order by id asc';
$tabs = $wpdb->get_results($sql);
return $tabs;
}
?>
