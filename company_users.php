<?php
global $wpdb;

$user_id = get_current_user_id();
$user = get_user_by('id', $user_id);
$company_name = $user->company_name;
$sql = 'SELECT * FROM ' . $wpdb->prefix . 'company where name="' . $company_name . '"';
$result = $wpdb->get_results($sql);

foreach ($result as $val) {
	$company_id = $val->id;
}

$user_sql = 'select * from  '.$wpdb->prefix .'company_user_roles join '.$wpdb->prefix.'users on  '.$wpdb->prefix .'company_user_roles.user_id = '.$wpdb->prefix.'users.ID WHERE user_login!="admin"' ;
$user_result = $wpdb->get_results($user_sql);?>

<script type='text/javascript' src='<?php echo plugin_dir_url(__FILE__);?>/js/jquery-1.11.1.min.js?ver=1.0.0'></script>
<link rel='stylesheet' id='bootstrap-css-css'  href='<?php echo plugin_dir_url(__FILE__);?>/css/bootstrap.min.css?ver=4.2.2' type='text/css' media='all' />
<div class="container">
	<h1>My Companies</h1>
	<div class="row">
		<div class="col-lg-8">
		<table class="table">
			<thead>
				<tr>
					<!--<th>
						Name
					</th>-->
					<!--<th>
						Phone
					</th>-->
					<th>
						Email
					</th>
					<th>
						Third Party
					</th>
					<!--<th>
						Title
					</th>-->
					<!--<th>
						Fax
					</th>-->
					<th>
						Address
					</th>
					<th>
						User Role
					</th>
					<th>
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($user_result as $val){
					$user_info = get_userdata($val->ID);
					$user_role = '';
					for($i=0;$i<count($user_info->roles);$i++){ $user_role .= $user_info->roles[$i]."<br/>";}
					?><tr>
						<!--<td><?php echo $val->display_name;?></td>-->
						<!--<td><?php echo $val->phone;?></td>-->
						<td><?php echo $val->user_email;?></td>
						<td><?php echo $val->third_party;?></td>
						<!--<td><?php echo $val->title;?></td>
						<td><?php echo $val->fax;?></td>-->
						<td><?php echo $val->address;?></td>
						<td><?php echo $user_role;?></td>
						<td><a href="javascript:void(0)" onclick="edit_user('<?php echo $val->ID;?>','<?php echo $val->display_name;?>','<?php echo $val->phone;?>','<?php echo $val->user_email;?>','<?php echo $val->third_party;?>','<?php echo $val->title;?>','<?php echo $val->fax;?>','<?php echo $val->address;?>','<?php echo $user_role;?>')">Edit</a>
						<a onclick="delete_user_list('<?php echo $val->ID;?>','<?php echo get_current_user_id();?>')" href="javascript:void(0)">Delete</a></td>
					</tr>
					<?php }?>
			</tbody>
		</table>
		</div>
		<div class="col-lg-4">
		
                    <form action="<?php echo get_admin_url();?>admin-post.php?page=<?php echo $_GET['page'];?>"  method="post" id="user_form">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="" class="required form-control" id="name">
							<input type="hidden" name="id" value="0"  id="id">
                        </div>
                        <div>
                            <label>Phone</label>
                            <input type="text" name="phone" value="" class="required form-control" id="phone"></td>
                        </div>
						<div>
                            <label>Email</label>
                            <input type="text" name="email" value="" class="required form-control" id="email">
                        </div>
						<div>
                            <label>Name of third party inspection Agencyy</label>
                            <input type="text" name="third_party" value="" class="required form-control" id="third_party">
                        </div>
						<div>
                            <label>Title</label>
                            <input type="text" name="title" value="" class="required form-control" id="title">
                        </div>
						<div>
                            <label>Fax</label>
                            <input type="text" name="fax" value="" class="required form-control" id="fax">
                        </div>
						<div>
                            <label>Address</label>
                            <input type="text" name="address" value="" class="required form-control" id="address">
                        </div>
                        <div>
                            <label>User Role</label>
                            <div class="checkbox">
                            <?php
							$userarray = array('legal','technical','billing');
                            foreach (get_editable_roles() as $role_name => $role_info){ if(in_array($role_name,$userarray)){ 
                                echo '<input type="checkbox" class="" name="user_role[]" value="'.$role_name.'" id="'.$role_name.'">'.$role_name."<br/>";
                            }}?>
                            </div>
                        </div>
                        <div>
                            
							<input type="submit" name="submit" value="Submit" class="btn-primary" onclick="return validate_user_form()">
							<input type="button" name="cancel" value="Cancel" class="btn-primary" onclick="cancel_user()" id="cancel" style="display:none">
							
                            <input type="hidden" name="action" value="user-form" />
                            <input type="hidden" name="hide" value="" />
                        </div>
                    </form>
                
		</div>
	</div>
</div>

<script>
	function validate_user_form(){ 
	temp=1;
	name = jQuery('#name').val();
	phone = jQuery('#phone').val();
	email = jQuery('#email').val();
	third_party = jQuery('#third_party').val();
	title = jQuery('#title').val();
	fax = jQuery('#fax').val();
	address = jQuery('#address').val();
	if(name=='')
	{
		jQuery('#name').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#name').css('border','1px solid gray');
	}
	if(phone=='')
	{
		jQuery('#phone').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#phone').css('border','1px solid gray');
	}
	
	if(email=='')
	{
		jQuery('#email').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#email').css('border','1px solid gray');
	}
	
	if(third_party=='')
	{
		jQuery('#third_party').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#third_party').css('border','1px solid gray');
	}
	
	if(title=='')
	{
		jQuery('#title').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#title').css('border','1px solid gray');
	}
	
	if(fax=='')
	{
		jQuery('#fax').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#fax').css('border','1px solid gray');
	}
	
	if(address=='')
	{
		jQuery('#address').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#address').css('border','1px solid gray');
	}
	
	if (temp) {
		return true;    
	}else
	{
		return false;
	}
	
}
function edit_user(id,name,phone,email,third_party,title,fax,address,user_role)
{
	jQuery('#name').val((name).trim());
	jQuery('#id').val((id).trim());
	jQuery('#phone').val((phone).trim());
	jQuery('#email').val((email).trim());
	jQuery('#third_party').val((third_party).trim());
	jQuery('#title').val((title).trim());
	jQuery('#fax').val((fax).trim());
	jQuery('#address').val((address).trim());
	//jQuery('#user_role').val((user_role).trim());
	user_array = user_role.split('<br/>');
	
	for(e=0;e<user_array.length;e++)
	{
		jQuery('#'+user_array[e].trim()).prop('checked', true);
	}
	
	jQuery('#cancel').show();
}
function cancel_user()
{
	jQuery('#name').val('');
	jQuery('#id').val(0);
	jQuery('#phone').val('');
	jQuery('#email').val('');
	jQuery('#third_party').val('');
	jQuery('#title').val('');
	jQuery('#fax').val('');
	jQuery('#address').val('');
	jQuery('#user_role').val('');
	jQuery('#cancel').hide();
	jQuery('input:checkbox').prop('checked', false);
}
function delete_user_list(id,user_id)
{
	var r= confirm('Are you sure you want to delete');
	if(r){
		window.location.href = '".get_admin_url()."admin-post.php?action=delete-application-user&hash='+id;
	}
}
</script>
<?php if($_GET['page']!='company-users'){?>
<div style="margin:30px 0px;"><input type="button" onclick=" top.tb_remove();top.alert_close('technical');top.alert_close('billing');top.alert_close('legal');" value="close">
<?php } ?>