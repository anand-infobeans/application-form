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
if (isset($_GET['type'])) {
    $company_sql = 'select * from  ' . $wpdb->prefix . 'company_user_roles join ' . $wpdb->prefix . 'company on  ' . $wpdb->prefix . 'company_user_roles.company_id = ' . $wpdb->prefix . 'company.id WHERE ' . $wpdb->prefix . 'company_user_roles.user_id=' . get_current_user_id() . ' AND ' . $wpdb->prefix . 'company.status="' . $_GET['type'] . '"';
} else {
    $company_sql = 'select * from  ' . $wpdb->prefix . 'company_user_roles join ' . $wpdb->prefix . 'company on  ' . $wpdb->prefix . 'company_user_roles.company_id = ' . $wpdb->prefix . 'company.id WHERE ' . $wpdb->prefix . 'company_user_roles.user_id=' . get_current_user_id() . ' AND ' . $wpdb->prefix . 'company.status="new_company"';
}
$company_result = $wpdb->get_results($company_sql);

$country_result = get_countries_list();

$state_sql = 'select * from ' . $wpdb->prefix . 'state';
$state_result = $wpdb->get_results($state_sql);
        
add_thickbox();


$page_url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
$delete_url = strtok($_SERVER["REQUEST_URI"],'?');
if(isset($_GET['type'])){
$delete_url .= '?page='.$_GET['page'].'&type='.$_GET['type'];
}
$page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

?>

<div class="container inner-main-container">


    <div class="col-md-12 padding-left-0 padding-right-0 inner-main-heading">
    <div class="col-md-8 padding-left-0 padding-right-0">
      <h1 class="entry-title post-title">My Companies</h1>
     </div>
     
     <div class="col-md-4 padding-left-0 padding-right-0"><a href="#my_content_id" onclick="cancel_user()" class="colorbox-inline btn btn-primary pull-right">Add New Company</a></div>
    </div><!--col-md-12-->
    
    <div class="floating-line"></div>
  
  
  <?php
if (isset($_GET['updated'])) {
    echo "<div class='updated'><p>Successfully Updated Company</p></div>";
} else if (isset($_GET['deleted'])) {
    echo "<div class='updated'><p>Successfully Deleted Company</p></div>";
} else if (isset($_GET['added'])) {
    echo "<div class='updated'><p>Successfully Added Company</p></div>";
}
?>

  

    <!--div class="" style="padding-top:5px; ">
        <div class="col-lg-12 padding-left-0 padding-right-0">
        <div class="select-box-wp select-theme-2">
        <span class="select-value">
        <?php /*if(isset($_GET['type']) && $_GET['type'] == 'new_company'){ 
			echo "New Company";
		}else if(isset($_GET['type']) && $_GET['type'] == 'edited'){ 
		echo "Edited Company";
		}else if(isset($_GET['type']) && $_GET['type'] == 'approved'){ 
			echo "Approve Company";
		} */ ?>
        </span>
            <select class="form-control select-box"  onchange="changedCompanyType('type', this.value);" id="select_company">
                <option  value="new_company">New Company</option>
                <option value="edited">Edited Company</option>
                <option  value="approved">Approve Company</option>
            </select>
            </div>
        </div>
    </div-->
    <div class="divider-20"></div>
    


    <div class="">
        <div class="col-lg-12 padding-left-0 padding-right-0">
            <table id="example" class="table  table-hover dt-responsive wp-list-table widefat fixed striped pages" cellspacing="0" width="100%">
                <thead>
                    <tr><th>Company Name</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Contact Person</th>
                        <th>Status</th>
                        <th class="text-align-center padding-right-0">Edit</th>
                        <th class="text-align-center padding-right-0">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($company_result as $val) {
                        $val->website_url;
                        //$user_info = get_userdata($val->ID);
                        $user_role = '';
                        //for($i=0;$i<count($user_info->roles);$i++){ $user_role .= $user_info->roles[$i]."<br/>";}
                        ?><tr class="<?php echo $val->status; ?>">
                            <td><?php
                                if (isset($val->name)) {
                                    echo $val->name;
                                }
                                ?></td>
                            <td><?php
                                if (isset($val->country)) {
                                    $country_name = $wpdb->get_var("SELECT country FROM " . $wpdb->prefix . "country where id=" . $val->country);
                                    echo $country_name;
                                }
                                ?></td>
                            <td><?php
                                if (isset($val->state)) {
                                    $state_name = $wpdb->get_var("SELECT state FROM " . $wpdb->prefix . "state where id=" . $val->state);
                                    echo $state_name;
                                }
                                ?></td>
                            <td><?php
                                if (isset($val->city)) {
                                    echo $val->city;
                                }
                                ?></td>
                                <td><?php
                                if (isset($val->address)) {
                                    echo $val->address;
                                }
                                ?></td>
                                 <td><?php
                                if (isset($val->status)) {
                                    echo $val->status;
                                }
                                ?></td>
                                <td><?php
                                if (isset($val->status)) {
                                    if(strtolower($val->status) == 'new_company'){ echo "New company"; } else { echo ucfirst($val->status);}
                                }
                                ?></td>
                            <td class="text-align-center "><a href="#TB_inline?width=50&height=600&inlineId=my-content-id" class="thickbox" onclick="edit_company('<?php echo $val->id; ?>', '<?php echo $val->name; ?>', '<?php echo $val->address; ?>', '<?php echo $val->country; ?>', '<?php echo $val->state; ?>', '<?php echo $val->city; ?>', '<?php echo $val->zipcode; ?>', '<?php echo $val->website_url; ?>')"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size: 20px;"></span></a>
                            </td><td class="text-align-center">
                                <a onclick="delete_company('<?php echo $val->id; ?>', '<?php echo get_current_user_id(); ?>')" href="javascript:void(0)"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="font-size: 20px;"></span></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
     
         <div style='display:none'>
			<div id='my_content_id' style='padding:10px; background:#fff;'>
            
            <div class="pop-heading-wp">Add Company</div><!--pop-heading-wp-->
                
            <form action="<?php echo get_admin_url(); ?>admin-post.php?action=add_company&page=my-companies"  method="post" id="user_form">
            
                    <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label >Company Name <span class="color-red">*</span></label></div>
                    <div class="col-md-8 padding-right-0"><input type="text" name="name" value="" class="required form-control pop-up-innput" id="name">
                    <input type="hidden" name="id" value="" class="required form-control" id="id" value="0"></div>
                    
            </div><!--col-md-12-->
            
            <div class="divider-15"></div>
            
            <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label>Address <span class="color-red">*</span></label></div>
                    <div class="col-md-8 padding-right-0"> <input type="text" name="address" value="" class="required form-control pop-up-innput" id="address"></div>
                    
            </div><!--col-md-12-->
            
            
             <div class="divider-15"></div>
            
            <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label >Country <span class="color-red">*</span></label></div>
                    <div class="col-md-8 padding-right-0">
                      <div class="select-box-wp bg-gray">
                    <span class="select-value">Select</span>
                    <select id="country" name="country" class="select-box js-vld-required accepttext form-control pop-up-innput">
                        <option value="">Select</option>
                        <?php foreach ($country_result as $val) { ?>
                            <option value="<?php echo $val->id; ?>"><?php echo $val->country; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    </div>
                    
            </div><!--col-md-12-->
            
            
            <div class="divider-15"></div>
            <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label >State <span class="color-red">*</span></label></div>
                    <div class="col-md-8 padding-right-0">
                    <div class="select-box-wp bg-gray">
                    <span class="select-value">Select</span>
                    <select id="state" name="state" class="select-box js-vld-required accepttext form-control pop-up-innput">
                        <option value="">Select</option>
                        <?php foreach ($state_result as $val) { ?>
                            <option value="<?php echo $val->id; ?>"><?php echo $val->state; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    </div>
                    
            </div><!--col-md-12-->
            
            
             <div class="divider-15"></div>
            
            <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label >City <span class="color-red">*</span></label></div>
                    <div class="col-md-8 padding-right-0">  <input type="text" name="city" value="" class="required form-control pop-up-innput" id="city"> </div>
                    
            </div><!--col-md-12-->
            
            
             <div class="divider-15"></div>
             
             
              <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label >Zipcode <span class="color-red">*</span></label></div>
                    <div class="col-md-8 padding-right-0"> <input type="text" name="zipcode" value="" class="required form-control pop-up-innput" id="zipcode"></div>
                    
            </div><!--col-md-12-->
            
             <div class="divider-15"></div>
             
             
              <div class="col-md-12 padding-left-0 padding-right-0">
            		<div class="col-md-4 padding-left-0"> <label>Website URL </label></div>
                    <div class="col-md-8 padding-right-0"> <input type="text" name="website_url" value="" class="required form-control pop-up-innput" id="website_url"></div>
                    
            </div><!--col-md-12-->
            
             <div class="divider-15"></div>
            
              <div class="col-md-12 padding-left-0 padding-right-0">
              <div class="col-md-4 padding-left-0"></div>
               <div class="col-md-8 padding-right-0"> 
               <input type="submit" name="submit" value="Submit" class="btn-primary btn btn-large-custom width-100" onclick="return validate_company_form()" style="min-width:100%;">
                    <input type="hidden" name="action" value="add_company" />
                    <input type="hidden" name="hide" value="" />

                    <input type="hidden" name="redirect_url" value="<?php echo $page_url; ?>" /></div>
              
                </div>    
                   
                  <div class="divider-5"></div>
                   
                  
            
              
            </form>
			</div>
        </div>
    </div>
</div>

<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    function validate_company_form() {
        temp = 1;
        name = jQuery('#name').val();
        address = jQuery('#address').val();
        country = jQuery('#country').val();
        state = jQuery('#state').val();
        city = jQuery('#city').val();
        zipcode = jQuery('#zipcode').val();
        website_url = jQuery('#website_url').val();
        //address = jQuery('#address').val();
        if (name == '')
        {
            jQuery('#name').css('border', '1px solid red');
            temp = 0;
        } else {
            jQuery('#name').css('border', '1px solid gray');
        }
        if (address == '')
        {
            jQuery('#address').css('border', '1px solid red');
            temp = 0;
        } else {
            jQuery('#address').css('border', '1px solid gray');
        }

        if (country == '')
        {
            jQuery('#country').css('border', '1px solid red');
            temp = 0;
        } else {
            jQuery('#country').css('border', '1px solid gray');
        }

        if (state == '')
        {
            jQuery('#state').css('border', '1px solid red');
            temp = 0;
        } else {
            jQuery('#state').css('border', '1px solid gray');
        }

        if (city == '')
        {
            jQuery('#city').css('border', '1px solid red');
            temp = 0;
        } else {
            jQuery('#city').css('border', '1px solid gray');
        }

        //if(fax=='')
        //{
        //	jQuery('#fax').css('border','1px solid red');
        //	temp=0;
        //}else{
        //	jQuery('#fax').css('border','1px solid gray');
        //}
        //
        //if(address=='')
        //{
        //	jQuery('#address').css('border','1px solid red');
        //	temp=0;
        //}else{
        //	jQuery('#address').css('border','1px solid gray');
        //}

        if (temp) {
            return true;
        } else
        {
            return false;
        }

    }
    function edit_company(id, name, address, country, state, city, zipcode, website_url)
    {
        //change_state(country, 'state', state);
        jQuery('#name').val((name).trim());
        jQuery('#id').val((id).trim());
        jQuery('#address').val((address).trim());
        jQuery('#country').val((country).trim());
        //jQuery('#state').val((state).trim());
        jQuery('#city').val((city).trim());
        jQuery('#zipcode').val((zipcode).trim());
        jQuery('#website_url').val((website_url).trim());
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
    function delete_company(id, user_id)
    {
        var r = confirm('Are you sure you want to delete');
        if (r) {
            window.location.href = '<?php echo get_admin_url(); ?>admin-post.php?action=delete_company&page=my-companies&hash=' + id + '&redirect_url=<?php echo base64_encode($delete_url) ?>';
        }
    }
    jQuery(document).ready(function () {
        jQuery('#example').DataTable({
			"processing": true,
			"sDom": '<"head-controls"l<"app-filter">f>t<"foot-controls"ip>'
		});
		jQuery('.app-filter').html('<div class="select-box-wp-2"> <span class="select-value">Change Type </span> <select class="form-control input-xm" onchange="changedCompanyType(\'type\', this.value);" id="select_company"><option  value="new_company">New Company</option><option value="edited">Edited Company</option><option  value="approved">Approve Company</option></select></div>');
<?php if (isset($_GET['type'])) {
    ?>
            jQuery('#select_company').val('<?php echo $_GET['type']; ?>');
<?php } ?>
    });
//Function is used to changed company type 
    function changedCompanyType(key, value) {
        var uri = document.URL;

        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            uri = uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            uri = uri + separator + key + "=" + value;
        }
        window.location.href = '' + uri + '';
    }
</script>
<?php if ($_GET['page'] != 'my-companies') { ?>
    <div style="margin:30px 0px;"><input type="button" onclick=" top.tb_remove();
                top.alert_close('technical');
                top.alert_close('billing');
                top.alert_close('legal');" value="close">
    <?php } ?>
<script type="text/javascript">
      jQuery('select').first().focus();
</script>