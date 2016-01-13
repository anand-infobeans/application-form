<?php
//get all templates
global $wpdb;
$sql = 'select * from wp_templates';
$result = $wpdb->get_results($sql);
?>
<h1>Templates</h1>
<form action="admin-post.php" method="post" action="">
<input type="hidden" name="app_id" value="<?php echo $_REQUEST['app_id']; ?>">
<input type="hidden" name="action" value="manage_templates_role">
<table id="example" class="wp-list-table widefat fixed striped pages" cellspacing="0" width="50%">
    <thead>
        <tr>
			<th align="left">Templates</th>
			<?php global $wp_roles;
			$roles = $wp_roles->roles;
			foreach( $roles as $role ) :
			if($role['name']=="Administrator") {$width="12%";}else{$width="";}?> 
            <th align="left" width="<?php echo $width;?>"><input type="checkbox" onclick="selectall('<?php echo str_replace(" ","_",$role['name']); ?>')" id="<?php echo str_replace(" ","_",$role['name']); ?>">&nbsp;<?php echo $role['name']; ?></th>
			<?php endforeach; ?> 
       </tr>
    </thead>
    <tbody>
<?php
$role_array = array('Legal','Technical','Billing');
foreach ($result as $val) {
	$my_array = unserialize(str_replace("`",'"',$val->capabilities));
	if(isset($_REQUEST["app_id"])){
	$sql = 'select * from wp_application_templates where template_id ='.$val->id.' and application_id = '.$_REQUEST["app_id"];
	}else
	{
		$sql = 'select * from wp_application_templates where template_id ='.$val->id;
	}
	$result = $wpdb->get_row($sql);
	if(isset($result->template_id)){if($val->id == $result->template_id) $checked = "checked"; else $checked = "";}
    ?>
		<tr>
			<td><?php echo $val->name; ?></td>
			<?php foreach( $roles as $role ) :
			
			?>
			<td class="<?php echo str_replace(" ","_",$role['name']);?>">
				<input type="checkbox" value="r" <?php if(isset($selected)){echo $selected;} ?> name="<?php echo str_replace(" ","_",$val->name)."-".$role['name'];?>[]" class="<?php echo str_replace(" ","_",$val->name)."-".str_replace(" ","_",$role['name']);?>-r">Read</br>
				<input type="checkbox" value="w" <?php if(isset($selected)){echo $selected;} ?> name="<?php echo str_replace(" ","_",$val->name)."-".$role['name'];?>[]" class="<?php echo str_replace(" ","_",$val->name)."-".str_replace(" ","_",$role['name']);?>-w">Write</br>
				<input type="checkbox" value="e" <?php if(isset($selected)){echo $selected;} ?> name="<?php echo str_replace(" ","_",$val->name)."-".$role['name'];?>[]" class="<?php echo str_replace(" ","_",$val->name)."-".str_replace(" ","_",$role['name']);?>-e">Edit</br>
			</td>
			<?php
			if(!empty($my_array)){
			if(array_key_exists($role['name'],$my_array))
			{ 	
				for($j=0;$j<strlen($my_array[$role['name']]);$j++)
				{?>
				<script>jQuery(".<?php echo str_replace(" ","_",$val->name)."-".str_replace(" ","_",$role['name'])."-".$my_array[$role['name']][$j];?>").prop('checked',true);</script>
			<?php }
			}
			}
			endforeach;?>
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
<script>
//	jQuery(document).ready(function () {
//        jQuery('#example').DataTable();
//    });

	function selectall(classname)
	{ 
		if(jQuery("#"+classname).prop('checked')){
			
			jQuery('.'+classname+' input').prop('checked', true);
		}else
		{
			jQuery('.'+classname+' input').prop('checked', false);
		}
	}
</script>