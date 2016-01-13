<?php
global $wpdb, $per_page, $resulttotal,$current_user;
$role = get_current_user_role();
$status_opt =   '<option value="all">All Application</option>';
if ( user_can($current_user, "customer")){
  $status_opt .='<option value="Draft" title="Draft">Draft</option>';
}
                $status_opt .='<option value="New" title="New">New</option>
                <option value="In Review" title="In Review">In Review</option>
                <option value="Modified" title="Modified">Modified</option>'.
                /*<option value="send_to_customer" title="Send To Customer">Send To Customer</option>*/
                '<option value="missing_items" title="Missing Items">Missing Items</option>
                <option value="completed" title="Completed">Completed</option>
                <option value="cancelled" title="Cancelled">Cancelled</option>';
if(isset($_GET['view'])){
    if($_GET['view']=='pending')
        $type = 'New';
    elseif($_GET['view']=='published')
        $type = 'completed';
    elseif($_GET['view']=='approved')
        $type = '';
	else
        $type = '';
} else {
   $type = '';
}
wp_enqueue_script('application_tb_script', plugin_dir_url(__FILE__) . 'js/applicationTables.js', array('datatable-bootstrap'));

wp_localize_script('application_tb_script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'uesr_role' => $role,'opt'=> $status_opt,'type'=>$type));
wp_localize_script('application_tb_script', 'loaderurl', get_bloginfo('template_directory')."/core/images/ajax-loading.gif");
wp_enqueue_script('crm_keys_script', plugin_dir_url(__FILE__) . 'js/crm_keys.js',array('jquery'));
require_once(ABSPATH . 'wp-content/plugins/ib-quotation/ib-quotation.php' );

?>

<?php
//show error/notices
$pageCode=$_GET['page'];
site_messages($pageCode);
//show error/notices

    //if (isset($_SESSION['approve_error_message']) && !empty($_SESSION['approve_error_message'])) {
    //    echo "<div class='error'><p>" . $_SESSION['approve_error_message'] . "</p></div>";
    //    unset($_SESSION['approve_error_message']);
    //} else if (isset($_SESSION['approve_success_message']) && !empty($_SESSION['approve_success_message'])) {
    //    echo "<div class='success'><p>" . $_SESSION['approve_success_message'] . "</p></div>";
    //    unset($_SESSION['approve_success_message']);
    //}
?>
        
            
            
    <div class="col-md-12 padding-left-0 padding-right-0 inner-main-heading">
        <div class="col-md-8 padding-left-0 padding-right-0">
            <h1 class="entry-title post-title"><span class="heading-line-height">Applications</span><div class="clearfix"></div></h1>
             
        </div>
        <?php
		
		// Check if login user is company admin or not
$is_user_company_admin = false;
if (function_exists('is_user_company_admin')) {
    $is_user_company_admin = is_user_company_admin();
}
		$get_current_user_role_new = get_current_user_role();
		
			  if ($is_user_company_admin && strtolower($get_current_user_role_new) != 'staff'){
				  ?>
        <a  class="colorbox-inline-70 btn btn-primary color-blue pull-right" href="#apply_for_new_accreditation"> Apply for New Accreditation</a>
			  <?php }/*else if ( is_user_company_legal()){ ?>
			  <a  class="colorbox-inline-70 btn btn-primary color-blue pull-right" href="#apply_for_new_accreditation"> Apply for New Accreditation</a>
			  <?php }*/?>
        
    <?php
		$sql = "select `id`, `name`, `doc_id` from " . $wpdb->prefix . "programs order by name asc";
        $all_programs = $wpdb->get_results($sql);
    	if(strtolower($role) == 'staff') {
    		$dboperations = Dboperations::getInstance();
    		$all_programs = $dboperations->get_programs_by_staff_user_id(get_current_user_id());
    	}
		echo "<script>var filter='';";
		foreach($all_programs as $all_program){
		  echo "filter +='<option name=".$all_program->name.">".$all_program->name."</option>';";
		}
		echo "</script>";
        ?>
        <div class="floating-line"></div>
    <div class="clearfix"></div>
        <div class="divider-15"></div>
    </div>
<div style='display:none'>
    <div id='apply_for_renew' style='padding:10px; background:#fff; width:100%; height: 500px; overflow-y:auto; overflow-x: hidden'>
        <h4 style="font-weight:bold; color: rgb(44,105,100);"> Coming soon </h4>
    </div>  
</div>
<table  class="wp-list-table  table table-striped table-hover dataTable no-footer" cellspacing="0" width="100%" id = "applicationTable">
	<tfoot style="display: none;">
		  <tr>
			  <th style="display: none;"></th>
			  <th style="display: none;"></th>
			  <th style="display: none;"></th>
			  <!--<th>Certificate URL</th>-->
			  <th style="display: none;"></th>
			  <th></th>
			  <th style="display: none;"></th>
			  <th style="display: none;"></th>
			  <!--<th style="display: none;"></th>
			  <th style="display: none;"></th>
			  <th style="display: none;"></th>-->
			  <th style="display: none;"></th>
		  </tr>
	</tfoot>
    <thead>
        <tr>
            <th>Application ID</th>
			<th>Application Name</th>
            <th>Certificate Name</th>
            <!--<th>Certificate URL</th>-->
			<th>Program Type</th>
            <th>Company</th>
            <th>State / Country</th>
            <th>Status</th>
            <!--<th>Due Date</th>
            <th class="text-center">Renew Detail</th>
            <th class="text-center">Scope Extension</th>-->
            <th class="percent-10" id="button-count">Actions</th>
        </tr>
    </thead>
	
</table>
<style>
tfoot {
    display: table-header-group;
}
</style>
<?php require_once 'app-diff.php'; ?>
<?php require_once 'view-application-data.php'; ?>