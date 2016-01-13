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
if(isset($_GET['type'])){
	$company_sql = 'select *,'.$wpdb->prefix .'company.id as company_id from  '.$wpdb->prefix .'company join '.$wpdb->prefix.'company_user_roles on  '.$wpdb->prefix .'company_user_roles.company_id = '.$wpdb->prefix.'company.id WHERE '.$wpdb->prefix .'company.status="'.$_GET['type'].'"';
}else
{
	$company_sql = 'select *,'.$wpdb->prefix .'company.id as company_id from  '.$wpdb->prefix .'company join '.$wpdb->prefix.'company_user_roles on  '.$wpdb->prefix .'company_user_roles.company_id = '.$wpdb->prefix.'company.id WHERE '.$wpdb->prefix .'company.status="new_company"';
}


//$user_sql = 'select *,'.$wpdb->prefix .'company.name as mycompany,'.$wpdb->prefix .'company.id as company_id from  '.$wpdb->prefix .'company left join '.$wpdb->prefix.'users on  '.$wpdb->prefix .'company_user_roles.user_id = '.$wpdb->prefix.'users.ID join '.$wpdb->prefix.'company on  '.$wpdb->prefix .'company_user_roles.company_id = '.$wpdb->prefix.'company.id';

$company_result = $wpdb->get_results($company_sql);
//print_r($company_result);
$country_result = get_countries_list();

$state_sql = 'select * from ' . $wpdb->prefix . 'state';
$state_result = $wpdb->get_results($state_sql);

add_thickbox();?>
<?php if(isset($_GET['updated']))
{
	echo "<div class='updated'><p>Successfully Updated Company</p></div>";
}else if(isset($_GET['deleted']))
{
	echo "<div class='updated'><p>Successfully Deleted Company</p></div>";
}else if(isset($_GET['added']))
{
	echo "<div class='updated'><p>Successfully Added Company</p></div>";
}?>
<div class="container">
	<div class="row" style="padding-top:5px; ">
		<div class="col-lg-12">
			<select class="form-control" onchange="javascript:window.location.href=document.URL+'&type='+this.value" id="select_company">
				<option value="new_company">New Company</option>
				<option value="edited">Edited Comapny</option>
				<option value="approved">Approve Company</option>
			</select>
		</div>
	</div>
	<h1>All Companies<!--<a href="#TB_inline?width=50&height=600&inlineId=my-content-id" onclick="cancel_user()" class="thickbox btn-primary" style="float:right;font-size: 20px;padding:5px;">Add New</a>--></h1>
	<div class="row">
		<div class="col-lg-12">
		<table id="example" class="table table-striped table-hover dt-responsive wp-list-table widefat fixed striped pages" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>
						Company Name
					</th>
					<th>
						Country
					</th>
					<th>
						State
					</th>
					<th>
						City
					</th>
					<th>
						Status
					</th>
					<th>
						Action
					</th>
					<!--<th>
						Edit
					</th>
					<th>
						Delete
					</th>-->
				</tr>
			</thead>
			<tbody>
				<?php foreach($company_result as $val){ 
					//$user_info = get_userdata($val->ID);
					//$user_role = '';
					//for($i=0;$i<count($user_info->roles);$i++){ $user_role .= $user_info->roles[$i]."<br/>";}
					?><tr>
						<td><?php echo $val->name;?></td>
						<td><?php if(isset($val->country)){$country_name = $wpdb->get_var( "SELECT country FROM ".$wpdb->prefix."country where id=".$val->country );
							echo $country_name;}?></td>
						<td><?php
							if(isset($val->state)){
							$state_name = $wpdb->get_var( "SELECT state FROM ".$wpdb->prefix."state where id=".$val->state );
							echo $state_name;}?></td>
						<td><?php echo $val->city;?></td>
						<td><?php if(isset($val->status)){ if(strtolower($val->status) == 'new_company'){ echo "New company"; } else { echo ucfirst($val->status);}}?></td>
						<td>
                                                    <?php 
                                                         if($val->status == 'new_company' || $val->status == 'New_company') { ?>
                                                              <a href='<?php echo get_admin_url()."admin-post.php?action=approve_all_companies&id=".base64_encode($val->company_id) ?>'>Approve</a> 
                                                       <?php   }
                                                       else if($val->status == 'edited') { ?>
                                                            <a href='<?php echo get_admin_url()."admin-post.php?action=approve_all_companies&id=".base64_encode($val->company_id) ?>&is_update=1'>Approve</a> 
                                                     <?php   }
                                                    ?>
                                                       
                                                
                                                </td>
						<!--<td><a href="#TB_inline?width=50&height=600&inlineId=my-content-id" class="thickbox" onclick="edit_company('<?php echo $val->company_id;?>','<?php echo $val->name;?>','<?php echo $val->address;?>','<?php echo $val->country;?>','<?php echo $val->state;?>','<?php echo $val->city;?>','<?php echo $val->zipcode;?>','<?php echo $val->website_url;?>')"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size: 20px;"></span></a>
						</td><td>
						<a onclick="delete_company('<?php echo $val->company_id;?>','<?php echo get_current_user_id();?>')" href="javascript:void(0)"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="font-size: 20px;"></span></a></td>-->
					</tr>
					<?php }?>
			</tbody>
		</table> 
		</div>
		<div class="col-lg-3" id="my-content-id" style="display:none">
			<form style="font-size:120px !important;" action="<?php echo get_admin_url();?>admin-post.php?action=add_company&page=all-companies"  method="post" id="user_form">
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" value="" class="required form-control" id="name">
					<input type="hidden" name="id" value="" class="required form-control" id="id" value="0">
				</div>
				<div class="form-group">
					<label>Address</label>
					<input type="text" name="address" value="" class="required form-control" id="address"></td>
				</div>
				<div class="form-group">
					<label>Country</label>
					<select id="country" name="country" class="js-vld-required accepttext form-control" <!--onchange="change_state(this.value,'state',0)"-->>
						<option value="">Select</option>
						<?php foreach($country_result as $val){?>
						<option value="<?php echo $val->id;?>"><?php echo $val->country;?></option>
						<?php }?>
					</select>
				</div>
				<div class="form-group">
					<label>State</label>
					<select id="state" name="state" class="js-vld-required accepttext form-control">
						<option value="">Select</option>
                        <?php foreach ($state_result as $val) { ?>
                            <option value="<?php echo $val->id; ?>"><?php echo $val->state; ?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>City</label>
					<input type="text" name="city" value="" class="required form-control" id="city">
				</div>
				<div class="form-group">
					<label>zipcode</label>
					<input type="text" name="zipcode" value="" class="required form-control" id="zipcode">
				</div>
				<div class="form-group">
					<label>Website Url</label>
					<input type="text" name="website_url" value="" class="required form-control" id="website_url">
				</div>
					<input type="submit" name="submit" value="Submit" class="btn-primary" onclick="return validate_company_form()">
					<input type="hidden" name="action" value="add_company" />
					<input type="hidden" name="hide" value="" />
			</form>
        </div>
	</div>
</div>

<script>
	function validate_company_form(){ 
	temp=1;
	name = jQuery('#name').val();
	address = jQuery('#address').val();
	country= jQuery('#country').val();
	state= jQuery('#state').val();
	city = jQuery('#city').val();
	zipcode = jQuery('#zipcode').val();
	website_url = jQuery('#website_url').val();
	
	if(name=='')
	{
		jQuery('#name').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#name').css('border','1px solid gray');
	}
	if(address=='')
	{
		jQuery('#address').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#address').css('border','1px solid gray');
	}
	
	if(country=='')
	{
		jQuery('#country').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#country').css('border','1px solid gray');
	}
	
	if(state=='')
	{
		jQuery('#state').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#state').css('border','1px solid gray');
	}
	
	if(city=='')
	{
		jQuery('#city').css('border','1px solid red');
		temp=0;
	}else{
		jQuery('#city').css('border','1px solid gray');
	}
	
	if (temp) {
		return true;    
	}else
	{
		return false;
	}
	
}
function edit_company(id,name,address,country,state,city,zipcode,website_url)
{
	//change_state(country,'state',state);
	jQuery('#name').val((name).trim());
	jQuery('#id').val((id).trim());
	jQuery('#address').val((address).trim());
	jQuery('#country').val((country).trim());
	//jQuery('#state').val((state).trim());
	jQuery('#city').val((city).trim());
	jQuery('#zipcode').val((zipcode).trim());
	jQuery('#website_url').val((website_url).trim());
}
function cancel_user()
{
	jQuery('#name').val('');
	jQuery('input:checkbox').prop('checked', false);
}
function delete_company(company_id)
{
	var r= confirm('Are you sure you want to delete this company?');
	if(r){
		window.location.href = '<?php echo get_admin_url();?>admin-post.php?action=delete_company&page=all-companies&&hash='+company_id;
	}
}
jQuery(document).ready(function() {
    jQuery('#example').DataTable({
	"processing": true});
	<?php if(isset($_GET['type']))
	{?>
	jQuery('#select_company').val('<?php echo $_GET['type'];?>');
	<?php }?>
} );
</script>