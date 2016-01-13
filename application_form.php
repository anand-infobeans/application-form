<?php
/* Plugin Name: Application Form
  Plugin URI: http://applicationform.com/
  Description: custom application form for IAS
  Depends: Ib Chase Payment
  Version: 4.0
  Author: Anand Pandey
  Author URI: http://anand.pandey@infobeans.com/
  License: GPLv2 or later
 */
/* 0846 - 08/06/15 - activation hook after activate create a table named application_form */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
register_activation_hook(__FILE__, 'application_form_install');
global $application_db_version;
$application_db_version = '1.0';
function application_form_install() {
    global $wpdb;
    global $application_db_version;
    /* create new tables */
    $programs = $wpdb->prefix . 'programs';
    $application_data = $wpdb->prefix . 'application_data';
    $templates = $wpdb->prefix . 'templates';
    $application_templates = $wpdb->prefix . 'application_templates';
    $company = $wpdb->prefix . 'company';
    $tab_slug = $wpdb->prefix . 'tab_slugs';
    /* update users table */
    $users = $wpdb->prefix . 'users';
    $charset_collate = $wpdb->get_charset_collate();
    $sql_users = "ALTER TABLE $users
    ADD my_column varchar(255)";
    $sql_app = "CREATE TABLE IF NOT EXISTS $programs (
        id int(10) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        created_by int(10) NOT NULL,
        created_on datetime,
        modified_by int(10) NOT NULL,
        modified_on datetime NOT NULL,
        deleted_by int(10) NOT NULL,
        deleted_on datetime NOT NULL,
		doc_id int(10) NOT NULL,
		abbreviation varchar(10) NULL,
        PRIMARY KEY id (id)
    ) $charset_collate";
    $sql_app_data = "CREATE TABLE IF NOT EXISTS $application_data  (
		id int(10) NOT NULL AUTO_INCREMENT,
		program_id int(10) NOT NULL,
		user_id int(10) NOT NULL,
		company_id int(10) NOT NULL,
		application_data longtext NOT NULL,
		created_by int(10) NOT NULL,
		created_on datetime,
		modified_by int(10) NOT NULL,
		modified_on datetime,
		deleted_by int(10) NOT NULL,
		deleted_on datetime,
		PRIMARY KEY id (id),
		FOREIGN KEY user_id (user_id) REFERENCES $users(ID),
		FOREIGN KEY program_id (program_id) REFERENCES $applications(id),
		FOREIGN KEY company_id (company_id) REFERENCES $company(id)
	) ENGINE=MyIsam $charset_collate";
    $sql_temp = "CREATE TABLE IF NOT EXISTS $templates (
        id int(10) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description varchar(255) NOT NULL,
        slug varchar(255) NOT NULL,
        created_by int(10) NOT NULL,
        created_on datetime,
        modified_by int(10) NOT NULL,
        modified_on datetime,
        deleted_by int(10) NOT NULL,
        deleted_on datetime,
        PRIMARY KEY id (id)
    ) $charset_collate";
    $sql_app_temp = "CREATE TABLE IF NOT EXISTS $application_templates (
		id int(10) NOT NULL AUTO_INCREMENT,
		program_id int(10) NOT NULL,
		template_id int(10) NOT NULL,
		template_render_order int(10) NOT NULL,
		tab_slug varchar(255) NOT NULL,
		created_by int(10) NOT NULL,
		created_on datetime,
		modified_by int(10) NOT NULL,
		modified_on datetime,
		deleted_by int(10) NOT NULL,
		deleted_on datetime,
		PRIMARY KEY id (id),
		FOREIGN KEY template_id (template_id) REFERENCES $templates (id),
		FOREIGN KEY program_id (program_id) REFERENCES $applications(id)
	) $charset_collate";
    $sql_company = "CREATE TABLE IF NOT EXISTS $company (
        id int(10) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        created_by int(10) NOT NULL,
        created_on datetime,
        modified_by int(10) NOT NULL,
        modified_on datetime,
        deleted_by int(10) NOT NULL,
        deleted_on datetime,
        PRIMARY KEY id (id)
    ) $charset_collate";
    $sql_tab = "CREATE TABLE IF NOT EXISTS $tab_slug (
    id int(11) NOT NULL AUTO_INCREMENT,
    tab_slug varchar(255) NOT NULL,
    tab_name varchar(255) NOT NULL,
    tab_order int(11) NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql_app);
    dbDelta($sql_app_data);
    dbDelta($sql_temp);
    dbDelta($sql_app_temp);
    dbDelta($sql_company);
    dbDelta($sql_users);
    dbDelta($sql_tab);
    add_option('application_db_version', $application_db_version);
    $wpdb->query("ALTER TABLE $users ADD COLUMN first_name varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN middle_name varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN last_name varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN phone varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN fax varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN address varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN third_party varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN title varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN company_name varchar(255) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN created_by int(10) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN created_on DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
    $wpdb->query("ALTER TABLE $users ADD COLUMN modified_by int(10) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN modified_on DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
    $wpdb->query("ALTER TABLE $users ADD COLUMN deleted_by int(10) AFTER display_name");
    $wpdb->query("ALTER TABLE $users ADD COLUMN deleted_on DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
    $wpdb->query("ALTER TABLE $users ADD COLUMN crm_id int(10) NOT NULL");
    $wpdb->query("ALTER TABLE $users ADD COLUMN crm_lead_id int(10) NOT NULL");
}
/* 0846 - 08/06/15 - function to deactivate and uninstall the table from database */
register_deactivation_hook(__FILE__, 'application_form_uninstall');
function application_form_uninstall() {
    global $wpdb;
    $applications = $wpdb->prefix . 'applications';
    $application_data = $wpdb->prefix . 'application_data';
    $templates = $wpdb->prefix . 'templates';
    $application_templates = $wpdb->prefix . 'application_templates';
    $company = $wpdb->prefix . 'company';
    $users = $wpdb->prefix . 'users';
    $tab_slugs = $wpdb->prefix . 'tab_slugs';
    //$wpdb->query("DROP TABLE IF EXISTS  $application_data, $application_templates, $company");
    //$wpdb->query("DROP TABLE $templates");
    //$wpdb->query("DROP TABLE $applications");
    /* $wpdb->query( "ALTER TABLE $users DROP COLUMN first_name");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN middle_name");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN last_name");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN phone");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN fax");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN address");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN third_party");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN title");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN company_name");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN created_by");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN created_on");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN modified_by");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN modified_on");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN deleted_by");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN deleted_on");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN crm_id");
      $wpdb->query( "ALTER TABLE $users DROP COLUMN crm_lead_id"); */
}
/* 0846 - 08/06/15 - add application menu on admin */
add_action('admin_menu', 'application_form_setup_menu');
function application_form_setup_menu() {
    global $current_user; //user global
    get_currentuserinfo(); // Make sure global is set, if not set it.
    add_menu_page('application-form-register', 'Programs', 'application-form', 'application-form-view');
    add_menu_page( 'profile update', 'Profile', 'application-form', 'profile.php', '', '', 5 );
    /* check current user is customer */
    if (user_can($current_user, "subscriber") || user_can($current_user, "modified") || user_can($current_user, "administrator") || user_can($current_user, "customer") || user_can($current_user, "new_user" )) {
        add_submenu_page('application-form-view', 'All Program Types ', 'All Program Types', 'application-form', 'application-form-register', 'view_application_form');
        //add_menu_page('My Invoices', 'My Invoices', 'read', 'my_invoices_list', 'my_invoices_list');
        // add_menu_page('My Companies', 'My Companies', 'application-form', 'my-companies', 'my_companies', '', 6);
        //add_menu_page('My Contacts', 'My Contacts', 'application-form', 'my-contacts', 'my_contacts', '', 7);
        //add_submenu_page('application-form-register', 'Create', 'Create', 'application-form', 'create-form-register', 'application_form_init');
    }
    /* check current user is staff */
    if (user_can($current_user, "staff")) {
        add_menu_page('All Companies', 'All Companies', 'application-form', 'all-companies', 'all_companies', '', 8);
        add_submenu_page('application-form-view', 'All', 'All', 'application-form', 'application-form-register', 'view_application_form');
        add_submenu_page('application-form-view', 'View Application', 'View Application', 'application-form', 'create-form-register', 'application_form_init');
    }
    /* check current user is administrator */
    //if (user_can($current_user, "administrator")) {
        add_submenu_page('application-form-view', 'Templates', 'Templates', 'application-form', 'templates', 'view_templates');
        //add_submenu_page('app_tmp', 'Templates', 'Templates', 'application-form', 'application-form-register', 'view_templates');
    //}
        
    if (!user_can($current_user, "administrator")) {
        if (user_can($current_user, "moderator")) {
            remove_menu_page('Change Password', 'Change Password', 'read', 'change_password', 'change_password', '');
            remove_menu_page('theme_my_login');
            remove_menu_page('IB-Contact-Form');
            remove_menu_page('options-general.php');
        } else {
            add_menu_page('Change Password', 'Change Password', 'read', 'change_password', 'change_password', '');
        }
    }
    add_submenu_page('application-form-view', 'View', 'Program Documents', 'application-form', 'application-form-view', 'view_all_applications');
    //add_submenu_page('application-form-register', 'Documents', 'Documents', 'application-form', 'documents', 'documents');
    add_submenu_page('application-form-view', ' Application Form', ' Application Form', 'application-form', '-application-form', '_application_form');
    add_submenu_page('application-form-view', "Assign Roles", "Permission", "application-form", "app_mang_role", "manage_templates_roles");
    add_submenu_page('application-form-view', "Renewal Notification", "Renewal notification", "application-form", "renewal_notification", "renewal_notification");
    //add_menu_page( 'Logut', 'Logout', 'read', 'logout', 'Logout','',1);
    //add_submenu_page( 'application-form-register', 'Payments', 'Payments', 'application-form', 'payments', 'payments' );
}
// Add short code to call application_form_init
add_shortcode('application_form', 'application_form_init');
/* 0846 - 08/06/15 - function for show application form */
function application_form_init($params = '') {
    global $current_user;
    get_currentuserinfo();
    echo "<script>var admin_url='" . admin_url() . "'
    var ajaxurl = '" . admin_url("admin-ajax.php") . "';";
    if (isset($_GET['view']) && $_GET['view'] == 'true') {
        echo "var view = true;";
    } else {
        echo "var view = false;";
    }
    if (user_can($current_user, "staff")) {
        echo "var staff=true;";
    } else {
        echo "var staff=false;";
    }
    echo "</script>";
    //wp_enqueue_style( 'bootstrap-css', plugin_dir_url(__FILE__).'css/bootstrap.min.css' );
    //wp_enqueue_style( 'switchery-css', plugin_dir_url(__FILE__).'css/switchery.min.css' );
    //wp_enqueue_style( 'application-form-css', plugin_dir_url(__FILE__).'css/application-form.css' );
    wp_enqueue_style('upload-file-css', plugin_dir_url(__FILE__) . 'css/uploadfile.css');
    include('register-application-form.php');
}
/* 0787---8/5/2015----- function to save application form details into the database When click on next button on application form */
add_action('admin_post_application-form-on-nextbtn', 'application_form_on_nextbtn');
function application_form_on_nextbtn() {
    global $current_user;
    global $wpdb;
    get_currentuserinfo();
    $pageCode = 'application-form-register';
    //print_r($_POST['data']);
    $json = json_encode($_POST['data']);
    //echo "<pre>";print_r($json);die();
    $company_id = get_company_id();
 
    if ($_POST['editid'] != "0" && is_numeric($_POST['editid'])) {
        //Check on click of next button any new data is filled or not
        $log_sql_crm = 'select id,content from ' . $wpdb->prefix . 'logger where LOWER(ref_type) = "application" and ref_id = "' . $_POST['editid'] . '" and UPPER(type) = "CRM" order by id desc limit 0,1';
        $log_details_crm = $wpdb->get_results($log_sql_crm);
        if ($log_details_crm) { 
        $pre_log_content = json_decode($log_details_crm[0]->content);
        $new_log_content = json_decode($json);
        $diff_result = get_json_diff($pre_log_content, $new_log_content, 'ARRAY');
        }
        /* checking crm id */
        $sql_data = $wpdb->query('select crm_id from ' . $wpdb->prefix . 'application_data where crm_id is not NULL AND id=' . $_POST['editid']);
        if ($wpdb->num_rows == 1) {
            if(isset($diff_result) && $diff_result !=NULL){-
            $wpdb->update($wpdb->prefix . 'application_data', array('application_data' => $json, 'status' => 'Modified'), array('id' => $_POST['editid']));
            $log_data = array(); //Declare array to stored log data
            $log_data['ref_type'] = "Application";
            $log_data['ref_id'] = $_POST['editid'];
            $log_data['title'] = "Existing Application Updated";
            $log_data['description'] = "Application Updated From Portal";
            $log_data['content'] = $json;
            //Used to log activity
            IB_Logging::ib_log_activity($log_data);
        }
        } else {
            $sql_is_draft = $wpdb->query('select * from ' . $wpdb->prefix . 'application_data where status="New" AND id=' . $_POST['editid']);
            if ($wpdb->num_rows == 0) {
                $wpdb->update($wpdb->prefix . 'application_data', array('application_data' => $json, 'status' => 'Draft'), array('id' => $_POST['editid']));
            } else {
                $wpdb->update($wpdb->prefix . 'application_data', array('application_data' => $json, 'status' => 'New'), array('id' => $_POST['editid']));
            }
            $log_data = array(); //Declare array to stored log data
            $log_data['ref_type'] = "Application";
            $log_data['ref_id'] = $_POST['editid'];
            $log_data['title'] = "Existing Application Updated";
            $log_data['description'] = "Application Updated From Portal";
            $log_data['content'] = $json;
            //Used to log activity
            IB_Logging::ib_log_activity($log_data);
        }
		/* assign roles change via application edit/submit */
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'])) {
            $technical = $_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'];
        } else {
            $technical = '';
        }
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_billingcontact'])) {
            $billing = $_POST['data']['new_application']['_linked']['contact']['new_billingcontact'];
        } else {
            $billing = '';
        }
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'])) {
            $legal = $_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'];
        } else {
            $legal = '';
        }
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'])) {
            $chief = $_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'];
        } else {
            $chief = '';
        }
        $edit_id = $_POST['editid'];
        change_user_roles_from_application($technical, $billing, $legal, $chief, $edit_id);
        echo trim($_POST['editid']);
    } else {
        if (isset($_POST["quotation_id"])) {
            $quotation_id = $_POST["quotation_id"];
        } else {
            $quotation_id = '';
        }
        $wpdb->insert($wpdb->prefix . 'application_data', array('program_id' => $_POST['program_id'], 'company_id' => $current_user->company_id, 'user_id' => get_current_user_id(), 'application_data' => $json, 'status' => 'Draft', 'created_by' => get_current_user_id(), 'created_on' => current_time('mysql', 1), 'quotation_id' => $quotation_id));
        $lastid = trim($wpdb->insert_id);
        /* assign roles change via application edit/submit */
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'])) {
            $technical = $_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'];
        } else {
            $technical = '';
        }
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_billingcontact'])) {
            $billing = $_POST['data']['new_application']['_linked']['contact']['new_billingcontact'];
        } else {
            $billing = '';
        }
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'])) {
            $legal = $_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'];
        } else {
            $legal = '';
        }
        if (isset($_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'])) {
            $chief = $_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'];
        } else {
            $chief = '';
        }
        change_user_roles_from_application($technical, $billing, $legal, $chief, $lastid);
        $log_data = array(); //Declare array to stored log data
        $log_data['ref_type'] = "Application";
        $log_data['ref_id'] = $lastid;
        $log_data['title'] = "New Application Register";
        $log_data['description'] = "Application Registered into database";
        $log_data['content'] = $json;
        //Used to log activity
        IB_Logging::ib_log_activity($log_data);
        //check quotation id
        if (isset($_POST["quotation_id"]) && $_POST["quotation_id"] != 0) {
            $quotation_id = $_POST["quotation_id"];
            //check if scope document uploaded or not.
            $sql = "SELECT post_id FROM " . $wpdb->prefix . "quotation_scope_data qsa, wp_terms wt WHERE wt.term_id = qsa.category_term_id AND qsa.quotation_id = " . $_POST['quotation_id'] . " AND wt.slug = 'customer-quotation' ORDER By id DESC LIMIT 0,1";
            $quotation_doc = $wpdb->get_row($sql);
            //Case where quotation document and id added to DB if quotation id and doc exists
            if ($quotation_doc) {
                $sql = "select term_id FROM " . $wpdb->prefix . "terms WHERE slug='scope-of-accreditation-document'";
                $media_category = $wpdb->get_row($sql);
                //saving file in database
                $wpdb->insert($wpdb->prefix . 'application_docs', array('application_id' => $lastid, 'doc_id' => $quotation_doc->post_id, 'doc_category' => $media_category->term_id));
                //Adding Document data in logger on updation
                $doc_path = array();
                $sql = "SELECT wad.doc_id,wt.slug FROM " . $wpdb->prefix . "application_docs wad, " . $wpdb->prefix . "terms wt WHERE wad.doc_category = wt.term_id AND application_id = " . $lastid . " ORDER By id DESC";
                $app_docs = $wpdb->get_results($sql);
                if ($app_docs) {
                    foreach ($app_docs as $doc):
                        $sql = "SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE post_id = " . $doc->doc_id;
                        $post = $wpdb->get_row($sql);
                        if (isset($doc_path[$doc->slug]))
                            $doc_path[$doc->slug] .= ";" . $post->meta_value;
                        else
                            $doc_path[$doc->slug] = $post->meta_value;
                    endforeach;
                    $json_data = json_encode($doc_path);
                    $log_data = array(); //Declare array to stored log data
                    $log_data['ref_type'] = "Application_docs";
                    $log_data['ref_id'] = $lastid;
                    $log_data['title'] = "Application Docs Updated";
                    $log_data['description'] = "Application Docs Updated From Portal";
                    $log_data['content'] = $json_data;
                    //Used to log activity
                    IB_Logging::ib_log_activity($log_data);
                }
                //Adding Document data in logger on updation
                //Adding document data in cron table
                $sql = "SELECT slug FROM " . $wpdb->prefix . "terms WHERE term_id = " . $media_category->term_id;
                $category = $wpdb->get_row($sql);

                $wpdb->query("INSERT INTO " . $wpdb->prefix . "cron_files (post_id, category_name,file_path,action_type,ref_type,ref_id,status,attempt) VALUES(" . $quotation_doc->post_id . ",'" . $category->slug . "','" . get_post_meta( $quotation_doc->post_id , '_wp_attached_file', true ) . "','add','application'," . $lastid . ",'open','0')");
                //Adding document data in cron table
            }
            //Case where quotation document and id added to DB if quotation id and doc exists
        }

        if (isset($media_category->term_id))
            echo $lastid . "_" . $media_category->term_id;
        else
            echo $lastid;
    }
}
/* 0787---8/5/2015----- function to save application form details into the database */
add_action('wp_ajax_application-form', 'validate_payment');
function validate_payment()
{
    postRequest($_POST);
}
add_action('admin_post_application-form', 'application_form_save_action'); // If the user is logged in
function application_form_save_action() {
    $pageCode = 'application-form-register';
    global $wpdb;
    $json = json_encode($_POST['data']);
    $company_id = get_company_id();
    $renewal_options = '';
    if ($_POST['editid'] != 0) {
         $log_sql_crm = 'select id,content from ' . $wpdb->prefix . 'logger where LOWER(ref_type) = "application" and ref_id = "' . $_POST['editid'] . '" and UPPER(type) = "CRM" order by id desc limit 0,1';
        $log_details_crm = $wpdb->get_results($log_sql_crm);
        if ($log_details_crm) { 
        $pre_log_content = json_decode($log_details_crm[0]->content);
        $new_log_content = json_decode($json);
        $diff_result = get_json_diff($pre_log_content, $new_log_content, 'ARRAY');
        }
        $log_sql_portal_doc = 'select id,content from ' . $wpdb->prefix . 'logger where LOWER(ref_type) = "application_docs" and ref_id = "' . $_POST['editid'] . '" and UPPER(type) = "PORTAL" order by id desc limit 0,1;';
        $log_sql_crm_doc = 'select id,content from ' . $wpdb->prefix . 'logger where LOWER(ref_type) = "application_docs" and ref_id = "' . $_POST['editid'] . '" and UPPER(type) = "CRM" order by id desc limit 0,1;';
        $log_details_portal_doc = $wpdb->get_results($log_sql_portal_doc);
        $log_details_crm_doc = $wpdb->get_results($log_sql_crm_doc);
        if ($log_details_portal_doc && $log_details_crm_doc) { 
        $pre_log_content_crm = json_decode($log_details_crm_doc[0]->content);
        $new_log_content_portal = json_decode($log_details_portal_doc[0]->content);
        $diff_result_doc = get_json_diff($pre_log_content_crm, $new_log_content_portal, 'ARRAY');
        }
   //  echo "portal <pre>";
   // print_r($log_sql_portal_doc);
   // echo "</pre>";
   // echo "New <pre>";
   // print_r($log_sql_crm_doc);
   // echo "</pre>";
   //  die;
        // echo "<pre>";print_r($diff_result);print_r($diff_result_doc);die;
        $sql_data = $wpdb->query('select crm_id from ' . $wpdb->prefix . 'application_data where crm_id is not NULL AND id=' . $_POST['editid']);
        if ($wpdb->num_rows == 1) {
            if((isset($diff_result) && $diff_result !=NULL) || (isset($diff_result_doc) && $diff_result_doc !=NULL)){
            $wpdb->update($wpdb->prefix . 'application_data', array('application_data' => $json, 'status' => 'Modified'), array('id' => $_POST['editid']));
            //Send Modified Application notification to Staff
            $get_current_user_role_new = get_current_user_role();
            $bnfw = BNFW::factory();
            if (strtolower($get_current_user_role_new) != 'staff') {
                $sql = 'SELECT * FROM ' . $wpdb->prefix . 'users where ID=' . get_current_user_id();
                $result_user = $wpdb->get_results($sql);
                $link_to_users = get_permalink(get_page_by_path('listings')) . '?page=create-form-register&id=' . base64_encode($_POST['editid']) . '&view=true';
                if ($bnfw->notifier->notification_exists('modified-application-staff')) {
                    $notifications = $bnfw->notifier->get_notifications('modified-application-staff');
                    foreach ($notifications as $notification) {
                        $setting = $bnfw->notifier->read_settings($notification->ID);
                        foreach ($setting['users'] as $users_role) {
                            $main_role = strtolower(str_replace('role-', '', $users_role));
                            $sql = "select `user_email`,`display_name` from " . $wpdb->prefix . "users where `user_type` = '" . $main_role . "'";
                            $staff_user = $wpdb->get_results($sql);
                            foreach ($staff_user as $staff_user) {
                                $emailstaff = $staff_user->user_email;
                                $display_name = (isset($staff_user->display_name))?$staff_user->display_name:'';
                                $usernamestaff = ucwords($display_name);
                                $subjectstaff = $setting['subject'];
                                $date = date('Y-m-d');
                                $messagestaff = $setting['message'];
                                $messagestaff = str_replace('[firstname]', $result_user[0]->first_name, $messagestaff);
                                $messagestaff = str_replace('[lastname]', $result_user[0]->last_name, $messagestaff);
                                $messagestaff = str_replace('[username]', $usernamestaff, $messagestaff);
                                $messagestaff = str_replace('[date]', $date, $messagestaff);
                                $messagestaff = str_replace('[linkToApplicationView]', $link_to_users, $messagestaff);
                                $subjectstaff = str_replace('[firstname]', $result_user[0]->first_name, $subjectstaff);
                                $subjectstaff = str_replace('[lastname]', $result_user[0]->last_name, $subjectstaff);
                                wp_mail($emailstaff, $subjectstaff, wpautop($messagestaff));
                               }
                            }
                        }
                    }
            }
            $log_data = array(); //Declare array to stored log data
            $log_data['ref_type'] = "Application";
            $log_data['ref_id'] = $_POST['editid'];
            $log_data['title'] = "Existing Application Updated";
            $log_data['description'] = "Application Updated From Portal";
            $log_data['content'] = $json;
            //Used to log activity
            IB_Logging::ib_log_activity($log_data);
        }
            /* assign roles change via application edit/submit */
            /* assign roles change via application edit/submit */
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'])) {
                $technical = $_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'];
            } else {
                $technical = '';
            }
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_billingcontact'])) {
                $billing = $_POST['data']['new_application']['_linked']['contact']['new_billingcontact'];
            } else {
                $billing = '';
            }
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'])) {
                $legal = $_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'];
            } else {
                $legal = '';
            }
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'])) {
                $chief = $_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'];
            } else {
                $chief = '';
            }
            $edit_id = $_POST['editid'];
            change_user_roles_from_application($technical, $billing, $legal, $edit_id);
            set_site_message($pageCode, 'success', "Application form successfully updated");
            //$_SESSION['wp_notices']['status'] = "Application form successfully updated";
        } else {
            $sql_data = $wpdb->query('select status from ' . $wpdb->prefix . 'application_data where status = "Draft" AND id=' . $_POST['editid']);
            if ($wpdb->num_rows == 1) {
                $bnfw = BNFW::factory();
                //if ($bnfw->notifier->notification_exists('new-application')) {
                    //$notifications = $bnfw->notifier->get_notifications('new-application');
                    //foreach ($notifications as $notification) {
                    //	$bnfw->engine->send_new_application_email($bnfw->notifier->read_settings($notification->ID), $user);
                    //}
                //}
                set_site_message($pageCode, 'success', "Application form successfully submitted");
                //Send New Application notification to Customer
                if ($bnfw->notifier->notification_exists('new-application')) {
                    $notifications_staff = $bnfw->notifier->get_notifications('new-application');
                    foreach ($notifications_staff as $notification_staff) {
                        $setting_staff = $bnfw->notifier->read_settings($notification_staff->ID);
                        $sql = "select `salutaions`,`display_name`,`user_email`,`first_name` from " . $wpdb->prefix . "users where `ID` = '" . get_current_user_id() . "'";
						$staff_user = $wpdb->get_row($sql);
                        $emailstaff = $staff_user->user_email;
                        $firstname = ucfirst($staff_user->first_name);
                        $subjectcustomer = $setting_staff['subject'];
                        $date = date('Y-m-d');
                        $messagecustomer = $setting_staff['message'];
                        $messagecustomer = str_replace('[saluirstnametations]', $staff_user->salutaions, $messagecustomer);
                        $messagecustomer = str_replace('[username]', $staff_user->display_name, $messagecustomer);
                        $messagecustomer = str_replace('[firstname]', $firstname, $messagecustomer);
                        $messagecustomer = str_replace('[date]', $date, $messagecustomer);
                        wp_mail($emailstaff, $subjectcustomer, wpautop($messagecustomer));

                    }
                }

				//Send New Application notification to contact
                if ($bnfw->notifier->notification_exists('new-application-assoc-contact')) {
                    $notifications_staff = $bnfw->notifier->get_notifications('new-application-assoc-contact');
                    foreach ($notifications_staff as $notification_staff) {
                        $setting_staff = $bnfw->notifier->read_settings($notification_staff->ID);
                        $assoc_user_sql = "select " . $wpdb->prefix . "users.`salutaions`," . $wpdb->prefix . "application_user_roles.`roles`," . $wpdb->prefix . "users.`display_name`," . $wpdb->prefix . "users.`first_name`," . $wpdb->prefix . "users.`user_email` from " . $wpdb->prefix . "users join " . $wpdb->prefix . "application_user_roles on " . $wpdb->prefix . "application_user_roles.user_id =  " . $wpdb->prefix . "users.ID where " . $wpdb->prefix . "application_user_roles.`application_id` = '" . $_POST['editid'] . "'";
						$assoc_user = $wpdb->get_row($assoc_user_sql);
						$emailuser = $assoc_user->user_email;
						$roles_arr = unserialize($assoc_user->roles);
						$key_arr = array_keys($roles_arr[0]);
						$verb = (count($key_arr)==1)?"is":"are";
						$key_arr=array_map(function($word) { return ucfirst($word); }, $key_arr);
						$roles_str = join(' and ', array_filter(array_merge(array(join(', ', array_slice($key_arr, 0, -1))), array_slice($key_arr, -1))));
						//$roles_str =  implode(", ",$key_arr);
                        $emailassocuser = $assoc_user->user_email;
                        $subjectcustomer = $setting_staff['subject'];
                        $date = date('Y-m-d');
                        $messagecustomer = $setting_staff['message'];
                        $messagecustomer = str_replace('[salutations]', $assoc_user->salutaions, $messagecustomer);
                        $messagecustomer = str_replace('[firstname]', ucfirst($assoc_user->first_name), $messagecustomer);
                        $messagecustomer = str_replace('[currentusername]', $assoc_user->display_name, $messagecustomer);
                        $messagecustomer = str_replace('[username]', $staff_user->display_name, $messagecustomer);
                        $messagecustomer = str_replace('[verb]', $verb, $messagecustomer);
                        $messagecustomer = str_replace('[roles]', $roles_str, $messagecustomer);
                        $messagecustomer = str_replace('[date]', $date, $messagecustomer);
						wp_mail($emailassocuser, $subjectcustomer, wpautop($messagecustomer));

                    }
                }




            } 
            // else {
            //     set_site_message($pageCode, 'success', "Application form successfully updated");
            // }
            if( (isset($_POST['renewal_year'])) && (!empty($_POST['renewal_year'])) ) {
            	//Calculate and store application renewal fields
            	$renewal_period = intval($_POST['renewal_year']);
            	//Get initial application for getting renewal options set by staff
            	$initial_application_data = $wpdb->get_row("SELECT id, renewal_options, application_exp_date from " . $wpdb->prefix . "application_data WHERE new_application_id=" . $_POST['editid']);
				if( (isset($initial_application_data)) && (!empty($initial_application_data->renewal_options)) ) {
					$renewal_options = json_decode($initial_application_data->renewal_options, true);
					$renewal_app_exp_date = '';
					$renewal_app_start_date = null;
					//Calculate renewed application's expiry date based customer selection for renewal period.
					if(isset($initial_application_data->application_exp_date) && (!empty($initial_application_data->application_exp_date))) {
						$app_exp_date = new DateTime(date("Y-m-d H:i:s" , strtotime($initial_application_data->application_exp_date)));
						$old_app_exp_date = clone $app_exp_date;
						$modifier= '+'.$renewal_period.' years';
						$app_exp_date->modify($modifier);
						while($app_exp_date->format('m')!=$old_app_exp_date->format('m')) {
							$app_exp_date->modify('-1 day');
						}
						$renewal_app_exp_date = $app_exp_date->format('Y-m-d H:i:s');
						$old_app_exp_date->modify('+1 day');
						$renewal_app_start_date = $old_app_exp_date->format('Y-m-d H:i:s');
					}
					$renewal_options['new_application_start_date'] = $renewal_app_start_date;
					$renewal_options['new_application_exp_date'] = $renewal_app_exp_date;
					$renewal_options['renewal_year'] = $renewal_period;
					//Update renewal_options in initial application to store renewed details in the renewal_options field.
					$wpdb->update($wpdb->prefix . 'application_data', array('renewal_options' => json_encode($renewal_options)), array('id' => $initial_application_data->id));
					//Update and set new application expiry date for renewed application based on renewal period.
					$wpdb->update($wpdb->prefix . 'application_data', array('application_exp_date' => $renewal_app_exp_date), array('id' => $_POST['editid']));
					//Update and set initial application is_renewed to true
					$wpdb->query("UPDATE " . $wpdb->prefix . "application_renewal_notification SET `is_renewed` = '1' WHERE application_id = " . $initial_application_data->id);
           		}
            }
            $wpdb->update($wpdb->prefix . 'application_data', array('application_data' => $json, 'status' => 'New', 'renewal_options' => $renewal_options), array('id' => $_POST['editid']));
            $log_data = array(); //Declare array to stored log data
            $log_data['ref_type'] = "Application";
            $log_data['ref_id'] = $_POST['editid'];
            $log_data['title'] = "Existing Application Updated";
            $log_data['description'] = "Application Updated From Portal";
            $log_data['content'] = $json;
            //Used to log activity
            IB_Logging::ib_log_activity($log_data);
            /* assign roles change via application edit/submit */
            /* assign roles change via application edit/submit */
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'])) {
                $technical = $_POST['data']['new_application']['_linked']['contact']['new_technicalcontactid'];
            } else {
                $technical = '';
            }
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_billingcontact'])) {
                $billing = $_POST['data']['new_application']['_linked']['contact']['new_billingcontact'];
            } else {
                $billing = '';
            }
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'])) {
                $legal = $_POST['data']['new_application']['_linked']['contact']['new_legalcontactid'];
            } else {
                $legal = '';
            }
            if (isset($_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'])) {
                $chief = $_POST['data']['new_application']['_linked']['contact']['new_chiefadminofficerid'];
            } else {
                $chief = '';
            }
            $edit_id = $_POST['editid'];
            change_user_roles_from_application($technical, $billing, $legal, $chief, $edit_id);
            //set_site_message($pageCode, 'success', "Application form successfully updated");
        }

        $user = isset($user)?$user:wp_get_current_user();

        //Send New Application notification to Staff
        $bnfw = BNFW::factory();
        if ($bnfw->notifier->notification_exists('new-application')) {
            $notifications = $bnfw->notifier->get_notifications('new-application');
            foreach ($notifications as $notification) {
                $bnfw->engine->send_new_application_email($bnfw->notifier->read_settings($notification->ID), $user);
            }
        }
        $sql_check_status = 'select `status` from ' . $wpdb->prefix . 'application_data where  id=' . $_POST['editid'];
        $result_status_check = $wpdb->get_row($sql_check_status);
        //Send New Application notification to Staff
        if($result_status_check->status !='Modified'){
        $user_id_staff_mail = get_current_user_id();
        $user_data_staff_mail = get_userdata($user_id_staff_mail);
        $firstname_staff = $user_data_staff_mail->data->first_name;
        $lastname_staff = $user_data_staff_mail->data->last_name;
        $url_staff = '' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($_POST['editid']) . '&view=true';
        if ($bnfw->notifier->notification_exists('new-application-staff')) {
            $notifications_staff = $bnfw->notifier->get_notifications('new-application-staff');
            foreach ($notifications_staff as $notification_staff) {
                $setting_staff = $bnfw->notifier->read_settings($notification_staff->ID);
                $sql_data = 'select `program_id`,`id` from ' . $wpdb->prefix . 'application_data where  id=' . $_POST['editid'];
                $program_id = $wpdb->get_row($sql_data);
                $sql = "select `user_id` from " . $wpdb->prefix . "program_user_association where `program_id` = " . $program_id->program_id . "";
                $user_id = $wpdb->get_results($sql);
                $applicationid = $program_id->id;
                foreach ($user_id as $user_id) {
                    $sql = "select `user_email`,`display_name` from " . $wpdb->prefix . "users where `ID` = '" . $user_id->user_id . "'";
                    $staff_user = $wpdb->get_row($sql);
                    $emailstaff = $staff_user->user_email;
                    $username_staff = ucwords($staff_user->display_name);
                    $subjectstaff = $setting_staff['subject'];
                    $date = date('Y-m-d');
                    $messagestaff = $setting_staff['message'];
                    $messagestaff = str_replace('[firstname]', $firstname_staff, $messagestaff);
                    $messagestaff = str_replace('[applicationid]', $applicationid, $messagestaff);
                    $messagestaff = str_replace('[lastname]', $lastname_staff, $messagestaff);
                    $messagestaff = str_replace('[date]', $date, $messagestaff);
                    $messagestaff = str_replace('[linkToApplicationView]', $url_staff, $messagestaff);
                    $messagestaff = str_replace('[username]', $username_staff, $messagestaff);
                    $subjectstaff = str_replace('[firstname]', $firstname_staff, $subjectstaff);
                    $subjectstaff = str_replace('[lastname]', $lastname_staff, $subjectstaff);
                    wp_mail($emailstaff, $subjectstaff, wpautop($messagestaff));
                }
            }
        }
    }
    }

	// application / quotation payment
	if( isset( $_POST['payment_mode'] ) ){

		$boolPaymentStatus = paymentTransaction( $_POST['chasePaymentAmount'], 'application', $_POST['editid'] );
		if( true == $boolPaymentStatus )
		{
			unset( $_SESSION['wp_success'][$pageCode]);
			set_site_message($pageCode, 'success', "Application payment done successfully.");
			wp_redirect(site_url() . '/index.php/listings/?page=application-form-register&view=approved&paged=1');
		}
	}
    wp_redirect(site_url() . '/index.php/listings/?page=application-form-register&view=approved&paged=1');
    //wp_redirect(add_query_arg(array('page' => 'application-form-register', 'action' => 'InvalidData'), admin_url()));
}

/* 0787---12/5/2015----- function to delete application */
add_action('admin_post_delete-application-form', 'delete_application_form');
function delete_application_form() {

    $pageCode = 'application-form-register';
    global $wpdb;
    if (isset($_GET['id'])) {

		// Send email to customer and staff if any application is deleted
		$getApplicationData = $wpdb->get_row( 'SELECT * FROM '.$wpdb->prefix.'application_data WHERE id='.base64_decode( $_GET['id'] ) );
		if( isset( $getApplicationData ) && $getApplicationData->status == 'New' ) {

			$arrStaffIds = array();
			$bnfw = BNFW::factory();
			$notification_name = 'application-deletion-by-customer';
			if($bnfw->notifier->notification_exists($notification_name)) {
				//Prepare data which will be sent in the mail for users
				$notifications = $bnfw->notifier->get_notifications($notification_name);
				$user = get_userdata( get_current_user_id() );

				//Send renewal email notification according to user_type
				if(!empty($notifications)) {
					$notification = $notifications[0];
					$setting = $bnfw->notifier->read_settings($notification->ID);

					// send mail to customer
					$arrEmailStuff = deleteApplicationMailContent( $user->first_name . ' ' . $user->last_name, $setting, $getApplicationData->id, $getApplicationData->application_name );
					$strEmailSubject = $arrEmailStuff[0];
					$strEmailContent = $arrEmailStuff[1];
					wp_mail($user->user_email, $strEmailSubject, wpautop( $strEmailContent ) );

					// send mail to all staff members who are associated with the application program
					$user_ids = $wpdb->get_results("SELECT `user_id` FROM " . $wpdb->prefix . "program_user_association WHERE `program_id` = ".$getApplicationData->program_id);
					if(!empty($user_ids)) {

						foreach ($user_ids as $user_id) {
							$arrStaffIds[] = $user_id->user_id;
						}
						$email_to_user = $wpdb->get_results("SELECT user_email, first_name, last_name FROM " . $wpdb->prefix . "users WHERE ID IN(" . implode( ',', $arrStaffIds ) . " )" );
						foreach ($email_to_user as $user_id) {
							$arrEmailStuff = deleteApplicationMailContent( $user_id->first_name . ' ' . $user_id->last_name, $setting, $getApplicationData->id, $getApplicationData->application_name );
							$strEmailSubject = $arrEmailStuff[0];
							$strEmailContent = $arrEmailStuff[1];
							wp_mail($user_id->user_email, $strEmailSubject, wpautop( $strEmailContent ) );
						}
					}
				}
			}
		}

        $wpdb->update($wpdb->prefix . 'application_data', array('status' => 'delete', 'deleted_by' => get_current_user_id(), 'deleted_on' => current_time('mysql', 1)), array('id' => base64_decode($_GET['id'])));

        $sql = 'select * from '.$wpdb->prefix.'application_docs where application_id='.base64_decode($_GET['id']);
        $result = $wpdb->get_results($sql);
        foreach ($result as $key => $value) {
            remove_application_documents($value->id,$value->application_id,$value->doc_id,$value->doc_category);
        }
    }

    set_site_message($pageCode, 'success', "Application form successfully deleted");
    //$_SESSION['wp_errors']['status'] = "Application form successfully deleted";
    wp_redirect(site_url() . '/index.php/listings/?page=application-form-register&view=approved');
    //wp_redirect(add_query_arg(array('page' => 'application-form-register', 'action' => 'InvalidData'), admin_url()));
}

function deleteApplicationMailContent( $strUserName, $arrEmailSetting, $intApplicationId, $strApplicationName ) {

	$strEmailSubject = str_replace( '[firstname] [lastname]',$strUserName, $arrEmailSetting['subject'] );
	$strEmailContent = $arrEmailSetting['message'];
	$strEmailContent = str_replace('[username]', $strUserName, $strEmailContent);
	$strEmailContent = str_replace('[application-id]', $intApplicationId, $strEmailContent);
	$strEmailContent = str_replace('[application-name]', $strApplicationName, $strEmailContent);

	return array( $strEmailSubject, $strEmailContent );
}

/* 0787 - 08/06/15 - function to delete contact users added by super user */
add_action('admin_post_delete-contact-user', 'delete_contact_user');
function delete_contact_user() {
//$_SESSION['wp_page'] = $_GET['page'];
// wp_delete_user($_GET['hash']);
    global $wpdb;
    if (isset($_GET['id'])) {
        // $wpdb->query("update " . $wpdb->prefix . "users set deleted_by = '" . get_current_user_id() . "',deleted_on = '" . date("Y-m-d H:m:s") . "' where ID=" . $_GET['id'] . "");
        $wpdb->query("delete from " . $wpdb->prefix . "users where ID=" . $_GET['id'] . "");
    }
    if (isset($_GET['page'])) {
        if (isset($_GET['redirect_url']) && !empty($_GET['redirect_url'])) {
            set_site_message('my-contacts', 'success', "Contact Deleted Successfully");
//$_SESSION['wp_notices']['user_deleted'] = __("Contact Deleted Successfully");
            wp_redirect(base64_decode($_GET['redirect_url']) . '?page=' . $_GET['page']);
        } else {
            set_site_message('my-contacts', 'success', "Contact Deleted Successfully");
//$_SESSION['wp_notices']['user_deleted'] = __("Contact Deleted Successfully");
            wp_redirect(admin_url() . 'admin.php?page=' . $_GET['page']);
        }
    } else {
        user_form();
    }
}
/* 0787---8/5/2015----- function to get Company id */
function get_company_id() {
    global $wpdb;
    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);
    if (isset($user->company_id) && $user->company_id != '') {
        return $user->company_id;
    }
}
add_shortcode('view-application-form', 'view_application_form');
/* function for view application form */
function view_application_form() {
	include('view-application-form.php');
}
/* function for view all applications */
function view_all_applications() {
    wp_enqueue_style('theme-css', get_bloginfo('template_directory') . '/core/css/ib-custom.css');
    include('view-all-applications.php');
}
function view_templates() {
    include('templates.php');
}
/* 0846 - 16/06/15 - function to show manage roles template page */
function manage_templates_roles() {
    include('manage_templates_role.php');
}
function application_documents() {
    include('templates/upload-application-document.php');
}
/* 0846 - 08/06/15 - all companies */
function all_companies() {
    include('all_companies.php');
}
/* 0787 - 11/08/15 - function to show Renewal Notification */
function renewal_notification() {
    include('renewal_notification.php');
}
add_action('wp_enqueue_scripts', 'include_application_form_scripts');
add_action('admin_enqueue_scripts', 'include_application_form_scripts');
/* 0846 - 08/06/15 - function to add scripts and style sheets on the plugin pages */
function include_application_form_scripts() {
    /* all srcipts */
    wp_enqueue_script('jquery-js', plugin_dir_url(__FILE__) . 'js/jquery-1.11.1.min.js', array(), '1.0.0', false);
    /* page where we want sorting,seraching,pagination add below condition */
    /* datatables js and css */
    wp_enqueue_script('jquery-steps-js', plugin_dir_url(__FILE__) . 'js/jquery.steps.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('jquery-datatable-min', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('datatable-responsive', plugin_dir_url(__FILE__) . 'js/dataTables.responsive.min.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('datatable-bootstrap', plugin_dir_url(__FILE__) . 'js/dataTables.bootstrap.js', array('jquery'), '1.0.0', false);
    if ( isset($_GET['page'])  ) {
        wp_enqueue_style('bootstrap-css', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
    }
    wp_enqueue_style('datatable-bootstrap-css', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap.css');
    wp_enqueue_style('application-css', plugin_dir_url(__FILE__) . 'css/application-form.css');
    wp_enqueue_style('jquery-steps-css', plugin_dir_url(__FILE__) . 'css/jquery.steps.css');
    wp_enqueue_style('datatable-responsive-css', plugin_dir_url(__FILE__) . 'css/responsive.dataTables.min.css');
    wp_enqueue_script('general', plugin_dir_url(__FILE__) . 'js/general.js', array('jquery', 'jquery-ui-js', 'switchery-js'), '1.0.0', false);
    wp_enqueue_script('switchery-js', plugin_dir_url(__FILE__) . 'js/switchery.min.js', array(), '1.0.0', false);
    wp_enqueue_script('upload-file-js', plugin_dir_url(__FILE__) . 'js/jquery.uploadfile.min.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('jquery-ui-js', plugin_dir_url(__FILE__) . 'js/jquery-ui.js', array('jquery'), '1.0.0', false);
}
/* 0846 - 08/06/15 - function to save user list from application form */
add_action('admin_post_user-form', 'user_form_save_action'); // If the user is logged in
function user_form_save_action() {
    //$_SESSION['wp_page'] = $_GET['page'];
    $current_user = wp_get_current_user();
    $user_name = isset($_POST['name'])?$_POST['name']:'';
    $user_email = (isset($_POST['email']) && (!empty($_POST['email']))) ? $_POST['email'] : "" ;

	global $wpdb;
    $error = '';
    $table_name = $wpdb->prefix . 'users';
    $deactive_user_id = $wpdb->get_var('SELECT ID from '.$table_name.' where user_email="'.$_POST['email'].'" AND user_status=-1');
    if($deactive_user_id!='')
    {
        if (isset($_GET['page']) && $_GET['page'] != "") {
            if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                if ($error !== '') {
                    //$_SESSION['wp_page'] = $_GET['page'];
                    //$_SESSION['wp_errors']['my-contacts']['user_exists'] = $error;
                }
                set_site_message('my-contacts', 'success', "Contact Added Successfully");
                if(active_user($user_email)){
                    wp_redirect(base64_decode($_POST['redirect_url']) . '?page=' . $_GET['page']);
                }
            } else {
                wp_redirect(admin_url() . "admin.php?page=" . $_GET['page']);
            }
        } else {
            $success = "Contact Added Successfully";
            $error = '';
            if(active_user($user_email)){
                user_form($error, $success, $deactive_user_id, $_POST['label']);
            }
        }
        die;
    }
    $is_update = false;
    if ($_POST['id'] == 0) {
        remove_filter('profile_update', 'numediaweb_custom_user_profile_fields', 20);
        if (!email_exists($user_email)) {
            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
            /* 0846 - 08/06/15 - in place of user_name in below function we take user_email because we change the functionality to take user_name as user_email */
            $user_id = @wp_create_user($user_email, $random_password, $user_email);
            //$user_id = wp_update_user(array('first_name' => $_POST['fname'], 'last_name' => $_POST['lname'], 'ID' => $user_id));
            $user = get_user_by('id', $user_id);

            $wpdb->query("update " . $wpdb->prefix . "users set display_name = '" . $_POST['fname'] . " " . $_POST['lname'] . "', first_name = '" . $_POST['fname'] . "',last_name='" . $_POST['lname'] . "',salutaions='" . $_POST['salutaions'] . "', phone = '" . $_POST['phone'] . "', country=" . $_POST['country'] . ",state=" . $_POST['state'] . ",city='" . $_POST['city'] . "', title = '" . $_POST['title'] . "', fax = '" . $_POST['fax'] . "', address = '" . $_POST['address'] . "', created_by = " . get_current_user_id() . ", created_on = '" . date("Y-m-d H:m:s") . "', company_id = '" . $_POST['company_id'] . "' where ID=" . $user_id);
            $get_current_user_role_new = get_current_user_role();
			$bnfw = BNFW::factory();
            if (strtolower($get_current_user_role_new) != 'staff') {
                $sql = 'SELECT * FROM ' . $wpdb->prefix . 'users where ID=' . get_current_user_id();
                $result_user = $wpdb->get_results($sql);
                $link_to_users = get_permalink(get_page_by_path('listings')) . '?page=my-contacts';
                if ($bnfw->notifier->notification_exists('new-contact')) {
                    $notifications = $bnfw->notifier->get_notifications('new-contact');
                    foreach ($notifications as $notification) {
                        $setting = $bnfw->notifier->read_settings($notification->ID);
                        foreach ($setting['users'] as $users_role) {
                            $main_role = strtolower(str_replace('role-', '', $users_role));
                            $sql = "select `user_email` from " . $wpdb->prefix . "users where `user_type` = '" . $main_role . "'";
                            $staff_user = $wpdb->get_results($sql);
                            foreach ($staff_user as $staff_user) {
                                $emailstaff = $staff_user->user_email;
                                $display_name = (isset($staff_user->display_name))?$staff_user->display_name:'';
                                $usernamestaff = ucwords($display_name);
                                $subjectstaff = $setting['subject'];
                                $date = date('Y-m-d');
                                $messagestaff = $setting['message'];
                                $messagestaff = str_replace('[firstuser]', $_POST['fname'], $messagestaff);
                                $messagestaff = str_replace('[lastuser]', $_POST['lname'], $messagestaff);
                                $messagestaff = str_replace('[firstname]', $result_user[0]->first_name, $messagestaff);
                                $messagestaff = str_replace('[lastname]', $result_user[0]->last_name, $messagestaff);
                                $messagestaff = str_replace('[username]', $usernamestaff, $messagestaff);
                                $messagestaff = str_replace('[date]', $date, $messagestaff);
                                $messagestaff = str_replace('[linkToUser]', $link_to_users, $messagestaff);
                                $subjectstaff = str_replace('[firstname]', $result_user[0]->first_name, $subjectstaff);
                                $subjectstaff = str_replace('[lastname]', $result_user[0]->last_name, $subjectstaff);
                                wp_mail($emailstaff, $subjectstaff, wpautop($messagestaff));
                               }
                            }
                        }
                    }
            }
            if ($bnfw->notifier->notification_exists('new-contact-to-customer')) {
                    $notifications = $bnfw->notifier->get_notifications('new-contact-to-customer');
                    foreach ($notifications as $notification) {
                        $setting = $bnfw->notifier->read_settings($notification->ID);
                        $emailcustomer = $current_user->user_email;
                        $subjectcustomer = $setting['subject'];
                        $date = date('Y-m-d');
                        $messagecustomer = $setting['message'];
                        $messagecustomer = str_replace('[firstuser]', $_POST['fname'], $messagecustomer);
                        $messagecustomer = str_replace('[lastuser]', $_POST['lname'], $messagecustomer);
                        $messagecustomer = str_replace('[firstname]', ucfirst($result_user[0]->first_name), $messagecustomer);
                        $messagecustomer = str_replace('[lastname]', $result_user[0]->last_name, $messagecustomer);
                        $messagecustomer = str_replace('[date]', $date, $messagecustomer);
                        $messagecustomer = str_replace('[linkToUser]', $link_to_users, $messagecustomer);
                        $subjectcustomer = str_replace('[firstname]', $result_user[0]->first_name, $subjectcustomer);
                        $subjectcustomer = str_replace('[lastname]', $result_user[0]->last_name, $subjectcustomer);
                        wp_mail($emailcustomer, $subjectcustomer, wpautop($messagecustomer));
                }
            }
            $error = '';
            $success = 'User added successfully';
            if (isset($_GET['page']) && $_GET['page'] != "") {
                set_site_message('my-contacts', 'success', "Contact Added Successfully");
            }
            //$_SESSION['wp_notices']['user_added'] = __('Contact added successfully.');
            //$_SESSION['wp_page'] = $_GET['page'];
        } else {
        	if(isset($_POST['label']) && (!empty($_POST['label'])) && ($_POST['label']=='applicantbillingid')) {
        		$user = get_user_by( 'email', $_POST['email']);
        		$is_billing_exist_for_application = $wpdb->get_row("select * from ".$wpdb->prefix."application_user_roles where user_id = ".$user->data->ID." and application_id = ".$_POST['application_id']);
        		if(empty($is_billing_exist_for_application)) {
        			if(!empty($user)) {
        				$wpdb->insert($wpdb->prefix.'application_user_roles',array('user_id'=>$user->data->ID,'application_id'=>$_POST['application_id'],'roles'=>serialize(array(array("billing"=>1)))));
        			}
        			$error = '';
        			$success = 'User added successfully';
        			user_form($error, $success, $user->data->ID, $_POST['label']);
        			$user_id=$user->data->ID;
        		}else {
        			if (isset($_GET['page']) && $_GET['page'] != "") {
        				set_site_message('my-contacts', 'error', "Contact already exists");
        			}
        			$error = 'This user already assign as billing for this application.';
        			$success = '';
        			$user_id='';
        		}

        	}else
        	{
	            if (isset($_GET['page']) && $_GET['page'] != "") {
	                set_site_message('my-contacts', 'error', "Contact already exists");
	            }
	            $error = 'Contact already exists.';
				$success = '';
				$user_id='';
        	}
            //$_SESSION['wp_errors']['user_exists'] = __('User already exists.  Password inherited.');
            //wp_redirect(admin_url()."/admin-post.php?page=my-contacts&error=".$error);

        }
    } else {
        $user = get_user_by('id', trim($_POST['id']));
        $user_info = get_userdata($user->ID);
        $user_role = implode(', ', $user_info->roles);
        $u = new WP_User($user->ID);
        $roles = get_user_meta($_POST['id'], "wp_capabilities");
        $dataarray = array('salutaions' => $_POST['salutaions'], 'display_name' => $_POST['fname'] . " " . $_POST['lname'], 'phone' => $_POST['phone'], 'country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zipcode' => $_POST['zipcode'], 'first_name' => $_POST['fname'], 'last_name' => $_POST['lname'], 'title' => $_POST['title'], 'fax' => $_POST['fax'], 'address' => $_POST['address'], 'modified_by' => get_current_user_id(), 'modified_on' => date("Y-m-d H:m:s"));
        $wpdb->update($table_name, $dataarray, array('id' => $_POST['id']));
        $get_current_user_role_new = get_current_user_role();
        if (strtolower($get_current_user_role_new) != 'staff') {
            $sql = 'SELECT * FROM ' . $wpdb->prefix . 'users where ID=' . get_current_user_id();
            $result_user = $wpdb->get_results($sql);
            $link_to_users = get_permalink(get_page_by_path('listings')) . '?page=my-contacts';
            $bnfw = BNFW::factory();
            if ($bnfw->notifier->notification_exists('new-contact-update')) {
                $notifications = $bnfw->notifier->get_notifications('new-contact-update');
                foreach ($notifications as $notification) {
                    $setting = $bnfw->notifier->read_settings($notification->ID);
                    foreach ($setting['users'] as $users_role) {
                        $main_role = strtolower(str_replace('role-', '', $users_role));
                        $sql = "select `user_email` from " . $wpdb->prefix . "users where `user_type` = '" . $main_role . "'";
                        $staff_user = $wpdb->get_results($sql);
                        foreach ($staff_user as $staff_user) {
                            $emailstaff = $staff_user->user_email;
                            $subjectstaff = $setting['subject'];
                            $date = date('Y-m-d');
                            $messagestaff = $setting['message'];
                            $messagestaff = str_replace('[firstuser]', $_POST['fname'], $messagestaff);
                            $messagestaff = str_replace('[lastuser]', $_POST['lname'], $messagestaff);
                            $messagestaff = str_replace('[firstname]', $result_user[0]->first_name, $messagestaff);
                            $messagestaff = str_replace('[lastname]', $result_user[0]->last_name, $messagestaff);
                            $messagestaff = str_replace('[date]', $date, $messagestaff);
                            $messagestaff = str_replace('[linkToUser]', $link_to_users, $messagestaff);
                            $subjectstaff = str_replace('[firstname]', $result_user[0]->first_name, $subjectstaff);
                            $subjectstaff = str_replace('[lastname]', $result_user[0]->last_name, $subjectstaff);
                            wp_mail($emailstaff, $subjectstaff, wpautop($messagestaff));
                        }
                    }
                }
            }
        }
        if (isset($user_info->crm_id) && !empty($user_info)) {
            $is_update = true;
            createRole('modified', 'Modified', array('read' => true, 'application-form' => false));  //create modified role
            $user_info->add_role('modified');
        }
        $user_id = $_POST['id'];
        //$_SESSION['wp_page'] = $_GET['page'];
        set_site_message('my-contacts', 'success', "Contact Details Updated Successfully");
        //  $_SESSION['wp_notices']['user_updated'] = __("Contact Updated Successfully");
    }
    if (empty($error)) {
        $roles = get_user_meta($current_user->ID, "wp_capabilities");
        if (!empty($roles) && isset($roles[0]) && array_key_exists('staff', $roles[0])) {
            try {
                approve_user_by_staff($user_id, $is_update);
            } catch (Exception $e) {
                $error = "CRM approve error " . $e->getMessage();
            }
        }
    }
    if (isset($_GET['page']) && $_GET['page'] != "") {
        if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
            if ($error !== '') {
                //$_SESSION['wp_page'] = $_GET['page'];
                //$_SESSION['wp_errors']['my-contacts']['user_exists'] = $error;
            }
            wp_redirect(base64_decode($_POST['redirect_url']) . '?page=' . $_GET['page']);
        } else {
            wp_redirect(admin_url() . "admin.php?page=" . $_GET['page']);
        }
    } else {
        user_form($error, $success, $user_id, $_POST['label']);
    }
}
add_action('admin_post_user-add-form', 'user_form');
function user_form($error = "", $success = "", $id = "", $label = "") {
    if ($error != "") {
        echo '<span class="message error"><ul><li>' . $error . '</li></ul></span>';
    }
    echo '<style>
		.message.success,.message.error{
		width: 95% !important;
		margin-left: 20px !important;
		text-align: center;}</style>';
    if ($success != "") {
        echo '<span class="message success"><ul><li>' . $success . '</li></ul></span>';
        echo "<script>";
        if ($label == "applicanttechid") {
            if($id!=''){
                echo "top.alert_close('technical'," . $id . ");";
            }
            if(isset($_POST['billing_id']))
            {
                echo "top.alert_close('billing'," . $_POST['billing_id'] . ");";
            }
            if(isset($_POST['legal_id']))
            {
                echo "top.alert_close('legal'," . $_POST['legal_id'] . ");";
            }
            if(isset($_POST['chief_id']))
            {
                echo "top.alert_close('chief'," . $_POST['chief_id'] . ");";
            }
        } else if ($label == "applicantbillingid") {
            if($id!=''){
                echo "top.alert_close('billing'," . $id . ");";
            }
            if(isset($_POST['technical_id']))
            {
                echo "top.alert_close('technical'," . $_POST['technical_id'] . ");";
            }
            if(isset($_POST['legal_id']))
            {
                echo "top.alert_close('legal'," . $_POST['legal_id'] . ");";
            }
            if(isset($_POST['chief_id']))
            {
                echo "top.alert_close('chief'," . $_POST['chief_id'] . ");";
            }
        } else if ($label == "applicantlegalid") {
            if($id!=''){
                echo "top.alert_close('legal'," . $id . ");";
            }
            if(isset($_POST['technical_id']))
            {
                echo "top.alert_close('technical'," . $_POST['technical_id'] . ");";
            }
            if(isset($_POST['billing_id']))
            {
                echo "top.alert_close('billing'," . $_POST['billing_id'] . ");";
            }
            if(isset($_POST['chief_id']))
            {
                echo "top.alert_close('chief'," . $_POST['chief_id'] . ");";
            }
        } else if ($label == "applicantchiefid") {
            if($id!=''){
                echo "top.alert_close('chief'," . $id . ");";
            }
            if(isset($_POST['technical_id']))
            {
                echo "top.alert_close('technical'," . $_POST['technical_id'] . ");";
            }
            if(isset($_POST['legal_id']))
            {
                echo "top.alert_close('billing'," . $_POST['billing_id'] . ");";
            }
            if(isset($_POST['legal_id']))
            {
                echo "top.alert_close('legal'," . $_POST['legal_id'] . ");";
            }
        }
        echo "</script>";
    }
    include('add_users.php');
}
//0846 - 08/06/15 - 1. Add a new field in registration form ...
add_action('register_form', 'application_register_form');
function application_register_form() {
    $display_name = (!empty($_POST['display_name']) ) ? trim($_POST['display_name']) : '';
    $company_name = (!empty($_POST['company_name']) ) ? trim($_POST['company_name']) : '';
    $phone = (!empty($_POST['phone']) ) ? trim($_POST['phone']) : '';
    $fax = (!empty($_POST['fax']) ) ? trim($_POST['fax']) : '';
    $mailing_address = (!empty($_POST['mailing_address']) ) ? trim($_POST['mailing_address']) : '';
    $zipcode = (!empty($_POST['mailing_address']) ) ? trim($_POST['mailing_address']) : '';
    $country = (!empty($_POST['country']) ) ? trim($_POST['country']) : '';
    $state = (!empty($_POST['state']) ) ? trim($_POST['state']) : '';
    $city = (!empty($_POST['city']) ) ? trim($_POST['city']) : '';
    $website_url = (!empty($_POST['website_url']) ) ? trim($_POST['website_url']) : '';
    global $wpdb;
    global $country_result;
    $company_sql = 'select company_name from  ' . $wpdb->prefix . 'users';
    $company_result = $wpdb->get_results($company_sql);
    $country_result = get_countries_list();
    $state_sql = 'select * from ' . $wpdb->prefix . 'state';
    $state_result = $wpdb->get_results($state_sql);
}
//0846 - 08/06/15 - 2. Add validation. In this case, we make sure first_name is required.
add_filter('registration_errors', 'application_registration_errors', 10, 3);
function application_registration_errors($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['display_name']) || !empty($_POST['display_name']) && trim($_POST['display_name']) == '') {
        $errors->add('display_name_error', __('<strong>ERROR</strong>: You must include your name.', 'ias'));
    }
    if (empty($_POST['country']) || !empty($_POST['country']) && trim($_POST['country']) == '') {
        $errors->add('country_error', __('<strong>ERROR</strong>: You must include your Country.', 'ias'));
    }
    if (empty($_POST['state']) || !empty($_POST['state']) && trim($_POST['state']) == '') {
        $errors->add('state_error', __('<strong>ERROR</strong>: You must include your State.', 'ias'));
    }
    if (empty($_POST['city']) || !empty($_POST['city']) && trim($_POST['city']) == '') {
        $errors->add('city_error', __('<strong>ERROR</strong>: You must include your City.', 'ias'));
    }
    if (!empty($_POST['phone']) && !is_numeric($_POST['phone'])) {
        $errors->add('phone_error', __('<strong>ERROR</strong>: You must include a Number Only in Phone.', 'ias'));
    }
    if (!empty($_POST['zipcode']) && !is_numeric($_POST['zipcode'])) {
        $errors->add('zipcode_error', __('<strong>ERROR</strong>: You must include a Number Only in Zipcode.', 'ias'));
    }
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $_POST['website_url']) && !empty($_POST['website_url'])) {
        $errors->add('website_url_error', __('<strong>ERROR</strong>: You must include a Correct Website Url.', 'ias'));
    }
    return $errors;
}
//0846 - 08/06/15 - 3. Finally, save our extra registration user meta.
add_action('profile_update', 'application_user_register');
add_action('user_register', 'application_user_register');
function application_user_register($user_id) {
    global $wpdb;
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        $name = isset($_POST['display_name']) ? $_POST['display_name']:'';
    }
    if ((isset($_POST['first_name'])) && $_POST['first_name'] == "") {
        $_POST['first_name'] = $name;
        $_POST['last_name'] = '';
    }
    $user_country = (isset($_POST['country'])) ? $_POST['country']:'';
    $user_first_name = (isset($_POST['first_name'])) ? $_POST['first_name']:'';
    $user_last_name = (isset($_POST['last_name'])) ? $_POST['last_name']:'';
    $user_state = (isset($_POST['state'])) ? $_POST['state']:'';
    $user_city = (isset($_POST['city'])) ? $_POST['city']:'';
    $user_website_url = (isset($_POST['website_url'])) ? $_POST['website_url']:'';
    $user_zipcode = (isset($_POST['zipcode'])) ? $_POST['zipcode']:'';
    $user_preferred_form = (isset($_POST['preferred_form'])) ? $_POST['preferred_form']:'';
    $user_company_name = (isset($_POST['company_name'])) ? $_POST['company_name']:'';
    $user_mailing_address = (isset($_POST['mailing_address'])) ? $_POST['mailing_address']:'';
    $user_fax = (isset($_POST['fax'])) ? $_POST['fax']:'';
    $user_phone = (isset($_POST['phone'])) ? $_POST['phone']:'';
    $dataarray = array('display_name' => $name, 'country' => $user_country, 'first_name' => $user_first_name, 'last_name' => $user_last_name, 'state' => $user_state, 'city' => $user_city, 'website_url' => $user_website_url, 'zipcode' => $user_zipcode, 'preferred_form' => $user_preferred_form, 'company_name' => $user_company_name, 'address' => $user_mailing_address, 'fax' => $user_fax, 'phone' => $user_phone, 'modified_by' => $user_id, 'modified_on' => date('Y-m-d H:s:m'));
    $table_name = $wpdb->prefix . 'users';
    $wpdb->update($table_name, $dataarray, array("ID" => $user_id));
    update_user_meta($user_id, 'first_name', $user_first_name);
    update_user_meta($user_id, 'last_name', $user_last_name);
    if (!empty($_POST['company_name'])) {
        global $wpdb;
        $company_sql = 'select name from  ' . $wpdb->prefix . 'company where name="' . $_POST['company_name'] . "'";
        $results = $wpdb->get_results($company_sql);
        if ($wpdb->num_rows == 0 && $_REQUEST['action'] != "update") {
            $company_array = array('name' => $_POST['company_name'], 'address' => $_POST['mailing_address'], 'fax' => $_POST['fax'], 'phone' => $_POST['phone'], 'status' => "new_company");
            $table_name = $wpdb->prefix . 'company';
            $wpdb->insert($table_name, $company_array);
            $company_id = $wpdb->insert_id;
            $capabilites = get_user_meta($user_id, "wp_capabilities");
            if (!empty($capabilites) && isset($capabilites[0]['new_user'])) {
                $capabilites[0]['administrator'] = $capabilites[0]['new_user'];
                unset($capabilites[0]['new_user']);
            }
            if ($_POST['action'] == 'register') {
                $admin = array("administrator");
                $application_user_data_array = array('application_data_id' => 0, 'user_id' => $user_id, 'roles' => serialize($capabilites), 'is_primary' => 1);
                $table_name = $wpdb->prefix . 'application_user_roles';
                $wpdb->insert($table_name, $application_user_data_array);
            }
        }
    }
}
/* 0846 - 08/06/15 - function to replace username with email label */
add_action('login_form_login', function() {
    add_filter('gettext', function($text) {
        if ('Username' === $text) {
            return 'Email';
        } else {
            return $text;
        }
    }, 20);
});
add_action('login_head', function() {
    ?>
    <style>
        #username {
            display:none;
        }
    </style>

    <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/jquery/jquery.js'); ?>"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#username').css('display', 'none');
        });
    </script>
    <?php
});
//0846 - 08/06/15 - Remove error for username, only show error for email only.
add_filter('registration_errors', function($wp_error, $sanitized_user_login, $user_email) {
    if (isset($wp_error->errors['empty_username'])) {
        unset($wp_error->errors['empty_username']);
    }
    if (isset($wp_error->errors['username_exists'])) {
        unset($wp_error->errors['username_exists']);
    }
    return $wp_error;
}, 10, 3);
add_action('login_form_register', function() {
    if (isset($_POST['user_login']) && isset($_POST['user_email']) && !empty($_POST['user_email'])) {
        $_POST['user_login'] = $_POST['user_email'];
    }
});
/* 0846 - 08/06/15 - show custom field at show profile as well as  profile */
add_action('show_user_profile', 'application_add_custom_user_profile_fields');
add_action('_user_profile', 'application_add_custom_user_profile_fields');
function application_add_custom_user_profile_fields($user) {
    //$all = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user->ID ) );
    global $wpdb;
    $user_result = $wpdb->get_results("select * from " . $wpdb->prefix . "users where ID =" . $user->ID);
    $all = json_decode(json_encode($user_result), true);
    $all = $all[0];
    $country_result = get_countries_list();
    $state_sql = 'select * from ' . $wpdb->prefix . 'state';
    $state_result = $wpdb->get_results($state_sql);
    global $current_user;
    get_currentuserinfo();
    if (!user_can($current_user, "administrator")) {
        ?>
<table class="form-table">
        <tbody>
        <tr class="user-country-wrap">
        <th><label for="country">Country</label></th>
        <td>
          <select class="select-box width-100 select-box-selected" name="country" id="country">
                    <option value="">Select</option>
                    <?php foreach ($country_result as $val) { ?>
                        <option value="<?php echo $val->id; ?>" <?php
                        if (isset($all['country']) && $val->id == $all['country']) {
                            echo "selected";
                        }
                        ?>><?php echo $val->country; ?></option>
                            <?php } ?>
        </select>
        </td>
        </tr>
          <tr class="user-state-wrap">
        <th><label for="state">State</label></th>
        <td>
         <select class="select-box width-100 select-box-selected"  name="state" id="state">
                    <option value="">Select</option>
                    <?php foreach ($state_result as $val) { ?>
                        <option value="<?php echo $val->id; ?>" <?php
                        if (isset($all['state']) && $val->id == $all['state']) {
                            echo "selected";
                        }
                        ?>><?php echo $val->state; ?></option>
                            <?php } ?>
                </select>
        </td>
        </tr>
 
        <tr class="user-zipcode-wrap">
            <th><label for="zipcode">Zipcode</label></th>
            <td><input placeholder="Zipcode" type="text" name="zipcode" id="zipcode" value="<?php echo $all['zipcode']; ?>" class="pop-up-innput" /></td>
        </tr>


        <tr class="user-company-name-wrap">
            <th><label for="company_name">Company Name</label></th>
            <td><input placeholder="Company Name" type="text" name="company_name" id="company_name" value="<?php echo $all['company_name']; ?>" class="pop-up-innput" /></td>
        </tr>

        <tr class="user-phone-wrap">
            <th><label for="phone">Phone</label></th>
            <td><input placeholder="Phone" type="text" name="phone" id="phone" value="<?php echo $all['phone']; ?>" class="pop-up-innput" /></td>
        </tr>

        <tr class="user-mailing-address-wrap">
            <th><label for="mailing_address">Mailing Address</label></th>
            <td>    <input placeholder="Mailing Address" type="text" name="mailing_address" id="mailing_address" value="<?php echo $all['address']; ?>" class="pop-up-innput" /></td>
        </tr>
        <tr class="user-fax-wrap">
            <th><label for="fax">Fax</label></th>
            <td>   <input placeholder="Fax" type="text" name="fax" id="fax" value="<?php echo $all['fax']; ?>" class="pop-up-innput" /></td>
        </tr>
        </tbody>
        </table>
        <?php
    }
}
/* 0846 - 08/06/15 - add script for autocomplete at front end */
add_action('wp_enqueue_scripts', 'application_form_front_script');
function application_form_front_script() {
    wp_enqueue_script('jquery-ui-js', plugin_dir_url(__FILE__) . 'js/jquery-ui.js', array(), '1.0.0', true);
    wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.css');
}
/* 0846 - 08/06/15 - function to change email content after registration */
//if (!function_exists('wp_new_user_notification')) {
//
//    function wp_new_user_notification($user_id, $plaintext_pass = '') {
//        $user = new WP_User($user_id);
//
//        $user_login = stripslashes($user->user_login);
//        $user_email = stripslashes($user->user_email);
//
//        $message = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
//        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
//        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";
//
//        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);
//
//        if (empty($plaintext_pass))
//            return;
//
//        //$message = __('Hi there,') . "\r\n\r\n";
//        //$message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
//        //$message .= wp_login_url() . "\r\n";
//        //$message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
//        //$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
//        //$message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
//        //$message .= __('Adios!');
//
//      $message = __('Hi there,') . "\r\n\r\n";
//
//        wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);
//    }
//
//}
/* 0846 - 08/06/15 - function to hide  application form from application plugin admin menu */
add_action('admin_footer', 'application_form_footer_function');
function application_form_footer_function() {
    echo "<script>
    jQuery('.toplevel_page_application-form-view ul li').each(function(){
        /*jQuery(this).attr('id',jQuery(this).text().replace(/\s/g,'_').toLowerCase());*/
        if(jQuery(this).text()==' Application Form' || jQuery(this).text()=='All Program Types' || jQuery(this).text()=='View Application' || jQuery(this).text()=='Templates' || jQuery(this).text()=='Programs'){ jQuery(this).hide();}
    });
    </script>";
}
/* 0846 - 08/06/15 - function to send create pass mail after active user form admin */
add_action('admin_post_activate-user', 'activate_user');
function activate_user() {
    global $wpdb, $wp_hasher;
    $errors = new WP_Error();
    if (empty($_REQUEST['user_login'])) {
        $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
    } elseif (strpos($_REQUEST['user_login'], '@')) {
        $user_data = get_user_by('email', trim($_REQUEST['user_login']));
        if (empty($user_data))
            $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    /**
     * Fires before errors are returned from a password reset request.
     *
     * @since 2.1.0
     */
    do_action('lostpassword_post');
    if ($errors->get_error_code())
        return $errors;
    if (!$user_data) {
        $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail address.'));
        return $errors;
    }
    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    /**
     * Fires before a new password is retrieved.
     *
     * @since 1.5.0
     * @deprecated 1.5.1 Misspelled. Use 'retrieve_password' hook instead.
     *
     * @param string $user_login The user login name.
     */
    do_action('retreive_password', $user_login);
    /**
     * Fires before a new password is retrieved.
     *
     * @since 1.5.1
     *
     * @param string $user_login The user login name.
     */
    do_action('retrieve_password', $user_login);
    /**
     * Filter whether to allow a password to be reset.
     *
     * @since 2.7.0
     *
     * @param bool true           Whether to allow the password to be reset. Default true.
     * @param int  $user_data->ID The ID of the user attempting to reset a password.
     */
    $allow = apply_filters('allow_password_reset', true, $user_data->ID);
    if (!$allow) {
        return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
    } elseif (is_wp_error($allow)) {
        return $allow;
    }
    // Generate something random for a password reset key.
    $key = wp_generate_password(20, false);
    /**
     * Fires when a password reset key is generated.
     *
     * @since 2.5.0
     *
     * @param string $user_login The username for the user.
     * @param string $key        The generated password reset key.
     */
    do_action('retrieve_password_key', $user_login, $key);
    // Now insert the key, hashed, into the DB.
    if (empty($wp_hasher)) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
    }
    $hashed = $wp_hasher->HashPassword($key);
    $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
    $message .= sprintf(__('Hi, %s'), $user_login) . "\r\n\r\n";
    $message = __('As requested by you, your password for below account is reset.') . "\r\n\r\n";
    $message .= network_home_url('/') . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('Please click here ');
    $message .= '<a href="' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\">rest your password</a> and login to your IAS account \r\n";
    $message = __('If you not requested for this, please ignore this email.') . "\r\n\r\n";
    if (is_multisite())
        $blogname = $GLOBALS['current_site']->site_name;
    else
    /*
     * The blogname option is escaped with esc_html on the way into the database
     * in sanitize_option we want to reverse this for the plain text arena of emails.
     */
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $title = sprintf(__('[%s] Password Reset'), $blogname);
    /**
     * Filter the subject of the password reset email.
     *
     * @since 2.8.0
     *
     * @param string $title Default email title.
     */
    $title = apply_filters('retrieve_password_title', $title);
    /**
     * Filter the message body of the password reset mail.
     *
     * @since 2.8.0
     * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    $message = apply_filters('retrieve_password_message', $message, $key, $user_login, $user_data);
    if ($message && !wp_mail($user_email, wp_specialchars_decode($title), $message))
        wp_die(__('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.'));
    return true;
}
add_action('wp_ajax_get_user_ajax', 'application_form_change_select');
/* 0846 - 08/06/15 - function to show technical/billing/legal user list on application form thorugh ajax */
function application_form_change_select() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    global $wp_roles;
    $roles = $wp_roles->roles;
    global $current_user;
    get_currentuserinfo();
    if (user_can($current_user, "staff")) {
        $sql = "select display_name,ID from  " . $wpdb->prefix . "users Where user_nicename!='admin' AND deleted_on IS NULL GROUP BY ID ORDER BY " . $wpdb->prefix . "users.display_name";
    } else {

        $sql = "select " . $wpdb->prefix . "users.display_name,
                " . $wpdb->prefix . "users.ID
                from " . $wpdb->prefix . "users
                LEFT JOIN " . $wpdb->prefix . "company on " . $wpdb->prefix . "company.id = " . $wpdb->prefix . "users.company_id
                where
                (
                " . $wpdb->prefix . "company.id = ".$current_user->company_id."
                AND " . $wpdb->prefix . "users.deleted_on IS NULL
                AND " . $wpdb->prefix . "users.user_nicename!='admin'
                )
                OR
                (
                " . $wpdb->prefix . "users.ID in (
                select " . $wpdb->prefix . "application_user_roles.user_id ID
                FROM " . $wpdb->prefix . "application_data
                LEFT JOIN " . $wpdb->prefix . "application_user_roles on " . $wpdb->prefix . "application_user_roles.application_id = " . $wpdb->prefix . "application_data.id
                where " . $wpdb->prefix . "application_data.company_id = ".$current_user->company_id."
                AND " . $wpdb->prefix . "application_user_roles.roles like '%".$_POST["type"]."%'
                )
                )
                GROUP BY wp_users.ID ORDER BY " . $wpdb->prefix . "users.display_name ASC";

        //$sql = "select display_name,ID from  " . $wpdb->prefix . "users where ID in (select ID from " . $wpdb->prefix . "users where company_id=" . $current_user->company_id . ") AND user_nicename!='admin' AND deleted_on IS NULL GROUP BY ID ORDER BY " . $wpdb->prefix . "users.display_name";
    }
    //$sql = '
    //    SELECT  *
    //    FROM        ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
    //    ON          ' . $wpdb->users . '.ID             =       ' . $wpdb->usermeta . '.user_id
    //    WHERE       ' . $wpdb->usermeta . '.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'
    //    AND created_by = ' . get_current_user_id() . ' AND (
    //';
    //$i = 1;
    //
    //
    //$sql .= ' ' . $wpdb->usermeta . '.meta_value    LIKE    \'%"' . $_POST['type'] . '"%\' ';
    //
    //$sql .= ' ) ';
    //$sql .= ' ORDER BY display_name ';
    $result = $wpdb->get_results($sql);
    $selectstr = '<option value="">Select</option>';
    foreach ($result as $val) {
        if ($val->display_name != '') {
            if ($_POST['id'] != 0 && $val->ID == $_POST['id']) {
                $selectstr .= '<option value="' . $val->ID . '" selected="selected">' . $val->display_name . '</option>';
            } else {
                $selectstr .= '<option value="' . $val->ID . '">' . $val->display_name . '</option>';
            }
        }
    }
    echo $selectstr;
    die;
}
add_action('wp_ajax_content_ajax', 'application_form_change_content');
/* 0846 - 08/06/15 - function to change content after select of users from application form */
function application_form_change_content() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    if (isset($_POST['id']) && $_POST['id'] != '') {
        $sql = "select * from $table_name where ID =" . $_POST['id'];
        $result = $wpdb->get_results($sql);
        echo json_encode($result);
    } else {
        echo "[]";
    }
    die;
}
add_action('wp_ajax_get_invoice_from_crm', 'get_invoice_from_crm');
add_action("wp_ajax_nopriv_get_invoice_from_crm", "get_invoice_from_crm");
/* 0846 - 08/06/15 - function to get state after select of country from application form */
function get_invoice_from_crm() {
    $CrmOperationsobj = new CrmOperations();
    $resultsfromcrm = $CrmOperationsobj->getCrmEntityDetails('invoice', array('type' => 'and', 'conditions' => array(array('attribute' => 'new_applicationid', 'operator' => 'eq', 'value' => $_POST['app_id']))), 'list');
// echo "<pre>";print_r($resultsfromcrm);
    $resulttotal = $resultsfromcrm->TotalRecordCount;
    foreach ($resultsfromcrm->Entities as $result)
        ;
    echo $result->name . "~" . $result->totalamount->FormattedValue;
}
add_action('wp_ajax_get_state', 'get_state');
add_action("wp_ajax_nopriv_get_state", "get_state");
/* 0846 - 08/06/15 - function to get state after select of country from application form */
function get_state() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'state';
    ;
    $sql = "select * from $table_name where country_id =" . $_POST['id'];
    $result = $wpdb->get_results($sql);
    $selectstr = '<option value="Select">Select</option>';
    foreach ($result as $val) {
        if ($_POST[''] != 0 && $val->id == $_POST['']) {
            $selectstr .= '<option value="' . $val->id . '" selected="selected">' . $val->state . '</option>';
        } else {
            $selectstr .= '<option value="' . $val->id . '">' . $val->state . '</option>';
        }
    }
    echo $selectstr;
    die;
}
add_action('wp_ajax_get_city', 'application_form_get_city');
/* 0846 - 08/06/15 - function to get city after select of state from application form */
function application_form_get_city() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'city';
    ;
    $sql = "select * from $table_name where state_id =" . $_POST['id'];
    $result = $wpdb->get_results($sql);
    $selectstr = '<option value="Select">Select</option>';
    foreach ($result as $val) {
        if ($_POST[''] != 0 && $val->id == $_POST['']) {
            $selectstr .= '<option value="' . $val->id . '" selected="selected">' . $val->city . '</option>';
        } else {
            $selectstr .= '<option value="' . $val->id . '">' . $val->city . '</option>';
        }
    }
    echo $selectstr;
    die;
}
add_action('login_form', 'application_login_form');
function application_login_form() {
    ?>
    
            <div class="col-lg-12 padding-left-0" style="color: #fff;margin-bottom: 5px;"  >
            <label for="user_type" >
            <!-- <?php _e('User Type', 'ias') ?> -->
            <!-- <br /> -->
            <div class="col-lg-4 padding-left-0">
            <label>
            <input type="radio" name="user_type" id="customer" class="input" value="customer" size="25" checked="checked"/>
            Customer</label></div>
            <div class="col-lg-4">
            <label>
             <input type="radio" name="user_type" id="assessor" class="input" value="assessor" size="25" <?php if(isset($_POST['user_type']) && $_POST['user_type']=='assessor'){?> checked="checked" <?php }?> />
            Assessor</label></div>
            <div class="col-lg-4">
            <label>
            <input type="radio" name="user_type" id="staff" class="input" value="staff" size="25" />
            Staff </label></div>
            </label>
            </div>
    
    <?php
}
//saving application document
add_action('admin_post_add_application_document', 'admin_add_application_document');
function admin_add_application_document() {
    // Handle request then generate response using echo or leaving PHP and using HTML
    global $wpdb;
    $templates = array();
    $templates = $_REQUEST[temp_id];
    foreach ($templates as $value) {
        $upload_doc = $_REQUEST["upload_doc_" . $value];
        $sql = 'select * from wp_application where id = ' . $value;
        $result = $wpdb->get_row($sql);
        //if already template is added
        if (sizeof($result))
            $wpdb->update('wp_application_templates', array('template_render_order' => $template_render_order, 'tab_slug' => $tab_slug), array('id' => $result->id));
        else
            $wpdb->insert('wp_application_templates', array('application_id' => $_REQUEST["app_id"], 'template_id' => $value, 'template_render_order' => $template_render_order, 'tab_slug' => $tab_slug), array('%d', '%d', '%d', '%d'));
        wp_redirect(add_query_arg(array('page' => 'application-form-view'), admin_url() . "admin.php?settings-updated=true'"));
    }
}
//saving application template data
add_action('admin_post_add_application_template', 'admin_add_application_template');
function admin_add_application_template() {
    // Handle request then generate response using echo or leaving PHP and using HTML
    global $wpdb;
    $templates = array();
    if (isset($_REQUEST['temp_id'])) {
        $templates = $_REQUEST['temp_id'];
        $wpdb->query('delete from ' . $wpdb->prefix . 'application_templates where program_id=' . $_REQUEST["app_id"]);
        foreach ($templates as $value) {
            $template_render_order = $_REQUEST["template_render_order_" . $value];
            $tab_slug = $_REQUEST["tab_slug_" . $value];
            $sql = 'select * from ' . $wpdb->prefix . 'application_templates where template_id =' . $value . ' and program_id = ' . $_REQUEST["app_id"];
            $result = $wpdb->get_row($sql);
            //if already template is added
            if (sizeof($result)) {
                $wpdb->update($wpdb->prefix . 'application_templates', array('template_render_order' => $template_render_order, 'tab_slug' => $tab_slug, 'modified_by' => get_current_user_id(), 'modified_on' => date("Y-m-d H:s:m")), array('id' => $result->id));
            } else {
                $wpdb->insert($wpdb->prefix . 'application_templates', array('program_id' => $_REQUEST["app_id"], 'template_id' => $value, 'template_render_order' => $template_render_order, 'tab_slug' => $tab_slug, 'created_by' => get_current_user_id(), 'created_on' => date("Y-m-d H:s:m")), array('%d', '%d', '%d', '%s', '%d', '%s'));
            }
            wp_redirect(add_query_arg(array('page' => 'application-form-view'), admin_url() . "admin.php?settings-updated=true'"));
        }
    } else {
        wp_redirect(add_query_arg(array('page' => 'application-form-view'), admin_url() . "admin.php"));
    }
}
/* 0846 - 16-06-15 funciton to manage role for each templates */
add_action('admin_post_manage_templates_role', 'update_templates_role');
function update_templates_role() {
    global $wpdb;
    $sql = 'select * from wp_templates';
    $result = $wpdb->get_results($sql);
    global $wp_roles;
    $roles = $wp_roles->roles;
    $myarray = array();
    foreach ($result as $val) {
        unset($myarray);
        foreach ($roles as $role) {
            if (isset($_POST[str_replace(" ", "_", $val->name) . "-" . str_replace(" ", "_", $role['name'])])) {
                $cap = '';
                for ($i = 0; $i < count($_POST[str_replace(" ", "_", $val->name) . "-" . str_replace(" ", "_", $role['name'])]); $i++) {
                    $cap .= $_POST[str_replace(" ", "_", $val->name) . "-" . str_replace(" ", "_", $role['name'])][$i];
                }
                $myarray[$role['name']] = $cap;
            }
        }
        $wpdb->update($wpdb->prefix . "templates", array('capabilities' => serialize($myarray)), array('id' => $val->id));
    }
    wp_redirect(admin_url() . "admin.php?page=app_mang_role");
}
/* 0846 - 06-05-15 change password individual */
add_shortcode('change-password', 'change_password');
function change_password($params = '') {
    require_once 'change-password.php';
}
add_action('admin_post_update_password', 'update_password');
function update_password() {
    global $error;
    $pageCode = 'change-password';
    $error = new WP_Error();
    if (isset($_POST['submit']) && isset($_POST['currentpassword']) &&
            !empty($_POST['currentpassword']) && isset($_POST['newpassword']) &&
            !empty($_POST['newpassword']) && isset($_POST['confirmpassword']) &&
            !empty($_POST['confirmpassword'])) {

        $currentpassword = $_POST['currentpassword'];
        $newpassword = $_POST['newpassword'];
        $confirmpassword = $_POST['confirmpassword'];
        $user = get_user_by('id', get_current_user_id());
        $crm_update_status = false;
        if ($user && wp_check_password($currentpassword, $user->data->user_pass, $user->ID)) {
            if ($confirmpassword == $newpassword) {
                $reg_exp_password = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/';
                if(!preg_match($reg_exp_password, $_POST['newpassword'])){
                    set_site_message($pageCode, 'error', "Use combination of at least one Uppercase, Lowercase, Number and Special Character.");
                    // $_SESSION['cp_error_msg'] = 'Please enter valid current password';
                    if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                        wp_redirect(base64_decode($_POST['redirect_url']) . '&updated=false');
                    } else {
                        wp_redirect(admin_url() . 'admin.php?page=change_password&updated=false');
                    }
                }else{
                    $crm_update = change_password_on_crm($user->ID, $newpassword);
                    if (!is_wp_error($crm_update)) {
                        $crm_update_status = true;
                        $update = wp_set_password($_POST['newpassword'], $user->ID);
                        if (!is_wp_error($update)) {
                            wp_cache_delete($user->ID, 'users');
                            wp_cache_delete($user->user_login, 'userlogins');
                            wp_logout();
                            if (wp_signon(array('user_login' => $user->user_login, 'user_password' => $_POST['newpassword']), false)):
                                set_site_message($pageCode, 'success', "Password Updated Successfully");
                                //$_SESSION['cp_success_msg'] = 'Password Updated Successfully';
                                if (isset($_POST['calling_from']) && $_POST['calling_from'] == 'frontend') {
                                    wp_redirect(site_url() . 'index.php/login-2/');
                                } else {
                                    wp_redirect(admin_url());
                                }
                            endif;
                            ob_start();
                        } else {
                            $crm_update = change_password_on_crm($user->ID, $user->data->user_pass);
                            wp_set_auth_cookie($current_user_id, true);
                        }
                    } else {
                        set_site_message($pageCode, 'error', "Password reset error");
                        // $_SESSION['cp_error_msg'] = 'Password reset error  ';
                        if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                            wp_redirect(base64_decode($_POST['redirect_url']) . '&updated=false');
                        } else {
                            wp_redirect(admin_url() . 'admin.php?page=change_password&updated=false');
                        }
    //                    wp_set_auth_cookie($current_user_id, true);
                    }
                    if ($crm_update_status) {
                        if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                            wp_redirect(base64_decode($_POST['redirect_url']) . '&updated=true');
                        } else {
                            wp_redirect(admin_url() . 'admin.php?page=change_password&updated=true');
                        }
                    }
                }
            } else {
                set_site_message($pageCode, 'error', "Password mismatch. Make sure the new and confirm passwords are identical");
                //$_SESSION['cp_error_msg'] = 'Password mismatch. Make sure the new and confirm passwords are identical';
                if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                    wp_redirect(base64_decode($_POST['redirect_url']) . '&updated=false');
                } else {
                    wp_redirect(admin_url() . 'admin.php?page=change_password&updated=false');
                }
            }
        } else {
            set_site_message($pageCode, 'error', "Please enter valid current password");
            // $_SESSION['cp_error_msg'] = 'Please enter valid current password';
            if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                wp_redirect(base64_decode($_POST['redirect_url']) . '&updated=false');
            } else {
                wp_redirect(admin_url() . 'admin.php?page=change_password&updated=false');
            }
        }
    } else {
        set_site_message($pageCode, 'error', "Mandatory field(s) are required");
        // $_SESSION['cp_error_msg'] = 'Mandatory field(s) are required';
        if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
            wp_redirect(site_url() . '/index.php/listings/?page=change_password&updated=false');
        } else {
            wp_redirect(admin_url() . 'admin.php?page=change_password&updated=false');
        }
    }
}
function login_cookie($user_login, $user) {
    global $wpdb;
    $user_result = $wpdb->get_results("select " . $wpdb->prefix . "application_user_roles.roles," . $wpdb->prefix . "users.user_type from " . $wpdb->prefix . "users LEFT JOIN " . $wpdb->prefix . "application_user_roles on " . $wpdb->prefix . "users.ID = " . $wpdb->prefix . "application_user_roles.user_id where " . $wpdb->prefix . "users.ID=" . $user->ID);
    $_SESSION["user_roles"] = unserialize($user_result[0]->roles);
    $_SESSION["user_type"] = $user_result[0]->user_type;
}
add_action('wp_signon', 'login_cookie', 10, 2);
/* function to remove change password from  profile page */
add_filter('user_contactmethods', 'remove_password_from_profile', 10, 1);
function remove_password_from_profile($contactmethods) {
    global $current_user;
    get_currentuserinfo();
    if (!user_can($current_user, "administrator")) {
        ?><style>#password,.user-pass2-wrap{display:none;}</style><?php
    }
}
/* 0846 - 08/06/15 - function to add profile updated widget on dashboard of admin */
/**
 * Profile widget for Dashboard
 */
function my_wp_dashboard_profile() {
    global $wpdb;
    //$result = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users WHERE modified_on >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY LIMIT 0,5');
    $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY user_registered DESC LIMIT 0,5');
    echo '<table id="profile_update" class="wp-list-table widefat fixed striped pages" cellspacing="0" width="50%">
    <thead>
        <tr>
            <th align="left">Users Name</th>
            <th align="left">Created Date</th>
       </tr>
       </thead>
       <tbody>';
    foreach ($result as $val) {
        echo '<tr>';
        echo '<td>' . $val->display_name . '</td>';
        echo '<td>' . $val->user_registered . '</td>';
        echo '</tr>';
    }
    echo '</tbody>
    </table>';
}
/**
 * add Profile Widget via function wp_add_profile_dashboard_widget()
 */
function wp_add_profile_dashboard_widget() {
    wp_add_dashboard_widget('my_wp_dashboard_test', __('Recent Profile Updates'), 'my_wp_dashboard_profile');
}
/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup', 'wp_add_profile_dashboard_widget');
/**
 * quotation widget for Dashboard
 */
function my_wp_dashboard_quotation() {
//    global $wpdb;
//    $result = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users WHERE modified_on >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY LIMIT 0,5');
//    $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY user_registered DESC LIMIT 0,5');
//    echo '<table id="profile_update" class="wp-list-table widefat fixed striped pages" cellspacing="0" width="50%">
//    <thead>
//        <tr>
//          <th align="left">Users Name</th>
//            <th align="left">Created Date</th>
//       </tr>
//     </thead>
//     <tbody>';
//    foreach ($result as $val) {
//        echo '<tr>';
//        echo '<td>' . $val->display_name . '</td>';
//        echo '<td>' . $val->user_registered . '</td>';
//        echo '</tr>';
//    }
//    echo '</tbody>
//  </table>';
}
/**
 * add Profile Widget via function wp_add_my_quotation_widget()
 */
function wp_add_my_quotation_widget() {
    global $current_user;
    get_currentuserinfo();
    if (user_can($current_user, "administrator") || user_can($current_user, "modified") || user_can($current_user, "customer")) {
        wp_add_dashboard_widget('my_wp_dashboard_quotation', __('My Quotation'), 'my_wp_dashboard_quotation');
    }
}
/**
 * use hook, to integrate my quotation widget
 */
add_action('wp_dashboard_setup', 'wp_add_my_quotation_widget');
/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup', 'wp_add_profile_dashboard_widget');
/**
 * Invoice widget for Dashboard
 */
function my_wp_dashboard_invoice() {
//    global $wpdb;
//    $result = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users WHERE modified_on >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY LIMIT 0,5');
//    $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY user_registered DESC LIMIT 0,5');
//    echo '<table id="profile_update" class="wp-list-table widefat fixed striped pages" cellspacing="0" width="50%">
//    <thead>
//        <tr>
//          <th align="left">Users Name</th>
//            <th align="left">Created Date</th>
//       </tr>
//     </thead>
//     <tbody>';
//    foreach ($result as $val) {
//        echo '<tr>';
//        echo '<td>' . $val->display_name . '</td>';
//        echo '<td>' . $val->user_registered . '</td>';
//        echo '</tr>';
//    }
//    echo '</tbody>
//  </table>';
}
/**
 * add Invoice Widget via function wp_add_my_quotation_widget()
 */
function wp_add_my_invoice_widget() {
    global $current_user;
    get_currentuserinfo();
    if (user_can($current_user, "administrator") || user_can($current_user, "modified") || user_can($current_user, "customer")) {
        wp_add_dashboard_widget('my_wp_dashboard_invoice', __('My Invoice'), 'my_wp_dashboard_invoice');
    }
}
/**
 * use hook, to integrate my invoice widget
 */
add_action('wp_dashboard_setup', 'wp_add_my_invoice_widget');
/**
 * quotation widget for Dashboard
 */
function my_wp_dashboard_notification() {
//    global $wpdb;
//    $result = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'users WHERE modified_on >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY LIMIT 0,5');
//    $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY user_registered DESC LIMIT 0,5');
//    echo '<table id="profile_update" class="wp-list-table widefat fixed striped pages" cellspacing="0" width="50%">
//    <thead>
//        <tr>
//          <th align="left">Users Name</th>
//            <th align="left">Created Date</th>
//       </tr>
//     </thead>
//     <tbody>';
//    foreach ($result as $val) {
//        echo '<tr>';
//        echo '<td>' . $val->display_name . '</td>';
//        echo '<td>' . $val->user_registered . '</td>';
//        echo '</tr>';
//    }
//    echo '</tbody>
//  </table>';
}
/**
 * add Profile Widget via function wp_add_my_notification_widget()
 */
function wp_add_my_notification_widget() {
    global $current_user;
    get_currentuserinfo();
    if (user_can($current_user, "administrator") || user_can($current_user, "modified") || user_can($current_user, "customer")) {
        wp_add_dashboard_widget('my_wp_dashboard_notification', __('My Notification'), 'my_wp_dashboard_notification');
    }
}
/**
 * use hook, to integrate my quotation widget
 */
add_action('wp_dashboard_setup', 'wp_add_my_notification_widget');
/* check wheather the user is aproved or not on lost password page */
function check_crm_id_password_reset($result, $user_id) {
    global $wpdb;
    $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users WHERE ID=' . $user_id . ' AND crm_id!=""');
    $rowCount = $wpdb->num_rows;
    if (!$rowCount /* The result of your captcha */) {
        //$result = new WP_Error('invalid_captcha', '<strong>ERROR:</strong> You are not approved yet.');
    }
    return $result;
}
add_filter('allow_password_reset', 'check_crm_id_password_reset', 10, 2);
//add_action( 'admin_bar_menu', 'toolbar_link_to_mypage', 999 );
//
//function toolbar_link_to_mypage( $wp_admin_bar ) {
//  $args = array(
//      'id'    => 'my_page',
//      'title' => 'My Page',
//      'href'  => 'http://mysite.com/my-page/',
//      'meta'  => array( 'class' => 'my-toolbar-page' )
//  );
//  $wp_admin_bar->add_node( $args );
//}
/* 0846 - 08/06/15 - function to remove menus as per the user role */
 function remove_menus() {
    global $current_user;
    if (user_can($current_user, "moderator")) {
    remove_menu_page( 'index.php' );                  //Dashboard
    }
  // remove_menu_page( '.php' );                   //Posts
  // remove_menu_page( 'upload.php' );                 //Media
  // remove_menu_page( '.php?post_type=page' );    //Pages
  // remove_menu_page( '-comments.php' );          //Comments
  // remove_menu_page( 'themes.php' );                 //Appearance
  // remove_menu_page( 'plugins.php' );                //Plugins
  // remove_menu_page( 'users.php' );                  //Users
  // remove_menu_page( 'tools.php' );                  //Tools
  // remove_menu_page( 'options-general.php' );        //Settings
  // remove_menu_page( 'theme_my_login' );             //TML
  } 
add_action('admin_menu', 'remove_menus');
// remove unwanted dashboard widgets for relevant users
function application_remove_dashboard_widgets() {
    $user = wp_get_current_user();
    if (!$user->has_cap('manage_options')) {
        remove_meta_box('dashboard_right_now', 'dashboard', 'side');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
    }
}
add_action('wp_dashboard_setup', 'application_remove_dashboard_widgets');
add_action('admin_post_file-upload', 'application_form_file_upload');
function application_form_file_upload() {
    //If directory doesnot exists create it.
    if (isset($_FILES["myfile"])) {
        $ret = array();
        $uploads = wp_upload_dir();
        $uploads_dir = $uploads['path'];
        $uploads_url = $uploads['url'];
        $error = $_FILES["myfile"]["error"];
        if (!is_array($_FILES["myfile"]['name'])) { //single file
            $fileName = $_FILES["myfile"]["name"];
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $uploads_dir . "/" . $_FILES["myfile"]["name"]);
            //echo "<br> Error: ".$_FILES["myfile"]["error"];
            $ret[$fileName] = $uploads_dir . "/" . $fileName;
        } else {
            $fileCount = count($_FILES["myfile"]['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = $_FILES["myfile"]["name"][$i];
                $ret[$fileName] = $uploads_dir . "/" . $fileName;
                move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $uploads_dir . "/" . $fileName);
            }
        }
        echo json_encode($ret);
        die;
    }
}
add_action('wp_ajax_save_application_document', 'save_application_document'); // Call when user upload application docs
add_action('admin_post_save_application_document', 'save_application_document'); // Call when user upload application docs

function save_application_document() {
    remove_filter('upload_dir', 'mgmt_third_party');
    global $wpdb;
    if(isset($_POST['app_id'])) $app_id = $_POST['app_id'];
    if(!isset($app_id))   $app_id = $_POST['applid'];
    $category_id = $_POST['category_id'];
    $appdoc_id = $_POST['appdoc_id'];
    $sql = "SELECT slug FROM " . $wpdb->prefix . "terms WHERE term_id = " . $category_id;
    $category = $wpdb->get_row($sql);
    if($_FILES){
    foreach ($_FILES as $file) {
        $upload = wp_handle_upload($file, array('test_form' => false));
        if (!isset($upload['error']) && isset($upload['file'])) {
            //uploading file in upload folder
            $filetype = wp_check_filetype(basename($upload['file']), null);
            $title = $file['name'];
            $ext = strrchr($title, '.');
            $title = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
            $attachment = array(
                'post_title' => addslashes($title),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $doc_post_id = wp_insert_attachment($attachment, $upload['file']);   //document post id
            //uploading file in upload folder
            //saving file in database
            $wpdb->query("INSERT INTO " . $wpdb->prefix . "application_docs (application_id, doc_id,doc_category) VALUES(" . $app_id . "," . $doc_post_id . "," . $category_id . ")");
            //Adding document data in cron table
            $wpdb->query("INSERT INTO " . $wpdb->prefix . "cron_files (post_id, category_name,file_path,action_type,ref_type,ref_id,status,attempt) VALUES(" . $doc_post_id . ",'" . $category->slug . "','" . get_post_meta( $doc_post_id , '_wp_attached_file', true ) . "','add','application'," . $app_id . ",'open','0')");
            //Adding document data in cron table
            //saving file in database
        }
    }
    //Adding Document data in logger on updation
   $doc_path = array();
    $sql = "SELECT wad.doc_id,wt.slug FROM " . $wpdb->prefix . "application_docs wad, " . $wpdb->prefix . "terms wt WHERE wad.doc_category = wt.term_id AND application_id = " . $app_id . " ORDER By id DESC";
    $app_docs = $wpdb->get_results($sql);
    if ($app_docs) {
        foreach ($app_docs as $doc):
            $sql = "SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE post_id = " . $doc->doc_id;
            $post = $wpdb->get_row($sql);
            if (isset($doc_path[$doc->slug]) && isset($post->meta_value))
                $doc_path[$doc->slug] .= ";" . $post->meta_value;
            else
                if(isset($post->meta_value)){
                $doc_path[$doc->slug] = $post->meta_value;}
        endforeach;
        $json_data = json_encode($doc_path);
        $log_sql_crm = 'select id,content from ' . $wpdb->prefix . 'logger where LOWER(ref_type) = "application_docs" and ref_id = "' . $app_id . '" and UPPER(type) = "CRM" order by id desc limit 0,1;';
        $log_details_crm = $wpdb->get_results($log_sql_crm);
        if ($log_details_crm) { 
        $pre_log_content = json_decode($log_details_crm[0]->content, true);
        }else{
            $pre_log_content = array();
        }
        $new_log_content = json_decode($json_data, true);
        $diff_result = get_doc_json_diff($pre_log_content, $new_log_content, 'ARRAY');
        if(isset($diff_result) && $diff_result != NULL){
        $wpdb->update($wpdb->prefix . 'application_data', array('status' => 'Modified'), array('id' => $app_id));
        $log_data = array(); //Declare array to stored log data
        $log_data['ref_type'] = "Application_docs";
        $log_data['ref_id'] = $app_id;
        $log_data['title'] = "Application Docs Updated";
        $log_data['description'] = "Application Docs Updated From Portal";
        $log_data['content'] = $json_data;
        //Used to log activity
        IB_Logging::ib_log_activity($log_data);
    }
    }
    //Adding Document data in logger on updation
    //retrieving documents from database
    $sql = "SELECT * FROM " . $wpdb->prefix . "application_docs WHERE application_id = " . $app_id . " AND doc_category = " . $category_id . " ORDER BY id DESC";
    $category_docs = $wpdb->get_results($sql);
    if ($category_docs) {
        foreach ($category_docs as $doc) {
            ?>

            <div class="col-md-8">
                <div class="pull-left">
                     <?php
					$filedata = wp_check_filetype( basename(wp_get_attachment_url( $doc->doc_id )));
					if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps") $fileclass = "file-icon-word";
					elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et") $fileclass = "file-icon-excel";
					elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png") $fileclass = "file-icon-image";
					elseif ($filedata["ext"] == "pdf") $fileclass = "file-icon-pdf";
					elseif ($filedata["ext"] == "zip") $fileclass = "file-icon-zip";
					elseif ($filedata["ext"] == "ppt") $fileclass = "file-icon-ppt";
					?>
					<span class="<?php echo $fileclass;?>"></span>
                    <span class="file-icon-label">
                        <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" download title="Download"> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 removefile" type='button' value='Remove' id='<?php echo $doc->id . "_" . $doc->application_id . "_" . $doc->doc_category . "_" . $appdoc_id; ?>' style="display:inline;" title="Remove">
                </div>
            </div>
            <div class="divider-10"></div>
            <?php
        }
        ?>

        <?php
    }
    //retrieving documents from database
    die;
}
}
/* 0846 - 08/06/15 - function to add company from all companies as well as my companies page */
add_action('admin_post_add_company', 'application_add_company');
function application_add_company() {
    global $wpdb;
    if (function_exists('start_session')) {
        start_session();
    }
    $_SESSION['wp_page'] = 'my-companies';
// Create for empty post data
    if (!isset($_REQUEST['submit']) &&
            !isset($_REQUEST['name']) && empty($_REQUEST['name']) &&
            !isset($_REQUEST['firstname']) && empty($_REQUEST['firstname']) &&
            !isset($_REQUEST['lastname']) && empty($_REQUEST['lastname']) &&
            !isset($_REQUEST['email']) && empty($_REQUEST['email']) &&
            !isset($_REQUEST['phone']) && empty($_REQUEST['phone']) &&
            !isset($_REQUEST['preferredcontactmethodcode']) && empty($_REQUEST['preferredcontactmethodcode']) &&
            !isset($_REQUEST['address']) && empty($_REQUEST['address']) &&
            !isset($_REQUEST['city']) && empty($_REQUEST['city']) &&
            !isset($_REQUEST['zipcode']) && empty($_REQUEST['zipcode']) &&
            !isset($_REQUEST['state']) && empty($_REQUEST['state']) &&
            !isset($_REQUEST['country']) && empty($_REQUEST['country'])) {
        if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])) {
            $_SESSION['wp_errors']['company_invalid_post_data'] = __("Mandatory field(s) are required..");
            wp_redirect($_SERVER["HTTP_REFERER"]);
        } else { // This case is occur when internet is disconnect and user direct hit the url
            $_SESSION['wp_errors']['company_invalid_post_data'] = __("Something went wrong.Please try again later.");
            wp_redirect(get_permalink(get_page_by_path('listings')) . '?page=my-companies&type=all');
        }
    }
    // Check for unique  company name
    $company_sql = 'SELECT id FROM ' . $wpdb->prefix . 'company where name="' . $_POST['name'] . '"';
    if ($_POST['id'] != 0) {
        $company_sql .= ' AND id != ' . $_POST['id'];
    }
    //echo $company_sql;
    $company_result = $wpdb->get_results($company_sql);
    $company_count = $wpdb->num_rows;
    /*
    if ($company_count > 0) {
        set_site_message('my-companies', 'error', "Company Already exists");
        //$_SESSION['wp_errors']['company_exists'] = __("Company Already exists ");
        wp_redirect($_SERVER["HTTP_REFERER"]);
        exit;
    }
    */
    $roles = get_user_meta(get_current_user_id(), 'wp_capabilities');
    if (!empty($roles) && isset($roles[0]) && array_key_exists('staff', $roles[0])) {
        $isstaff = true;
    }
    //if ($wpdb->num_rows == 0) {
        if ($_POST['id'] == 0) {
            if (email_exists($_REQUEST['email'])) {
                set_site_message('my-companies', 'error', "Email already exists please enter another email");
                wp_redirect(get_permalink(get_page_by_path('listings')) . '?page=my-companies&type=all');
            }
            $company_type = isset($_POST['company_type']) ? $_POST['company_type'] : "Company";
            $table = $wpdb->prefix . "company";
            $data = array('name' => $_POST['name'], 'email' => $_POST['email'], 'created_by' => get_current_user_id(), 'phone' => $_POST['phone'], 'created_on' => date('Y-m-d H:m:s'), 'address' => $_POST['address'], 'fax' => $_POST['fax'], 'country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zipcode' => $_POST['zipcode'], 'website_url' => $_POST['website_url'], 'status' => "New_company", 'type' => $company_type);
            //print_r($data);die;
            $wpdb->insert($table, $data);
            $company_id = $wpdb->insert_id;
            /* create new contact associated with new inserted company */
            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
            /* 0846 - 08/06/15 - in place of user_name in below function we take user_email because we change the functionality to take user_name as user_email */
            $user_id = wp_create_user($_REQUEST['email'], $random_password, $_REQUEST['email']);
            $user = get_user_by('id', $user_id);
            /* assign role agent if company is agency */
            if ($isstaff) {
                if ($company_type == 'Agency') {
                    $agent_array = get_role("Agent");
                    if (empty($agent_array)) {
                        add_role('agent', 'Agent', array('read' => true, 'application-form' => true));
                        $user = new WP_User($user_id);
                        $user->add_cap('Agent');
                    } else {
                        $user = new WP_User($user_id);
                        $user->add_cap('Agent');
                    }
                } else {
                    $user = new WP_User($user_id);
                    $user->remove_cap('Agent');
                }
            }
            $wpdb->query("update " . $wpdb->prefix . "users set display_name = '" . $_POST['firstname'] . " " . $_POST['lastname'] . "', first_name = '" . $_POST['firstname'] . "',last_name='" . $_POST['lastname'] . "',salutaions='" . $_POST['salutaions'] . "',preferred_form='" . $_POST['preferredcontactmethodcode'] . "', phone = '" . $_POST['phone'] . "', country=" . $_POST['country'] . ",state=" . $_POST['state'] . ",city='" . $_POST['city'] . "',  address = '" . $_POST['address'] . "', created_by = " . get_current_user_id() . ", created_on = '" . date("Y-m-d H:m:s") . "', company_id = '" . $company_id . "' where ID=" . $user_id);
            $sql = 'SELECT * FROM ' . $wpdb->prefix . 'users where ID=' . get_current_user_id();
            $result = $wpdb->get_results($sql);
            $get_current_user_role_new = get_current_user_role();
            if (strtolower($get_current_user_role_new) != 'staff') {
                $firstname_users = $result[0]->first_name;
                $lastname_users = $result[0]->last_name;
                $link_to_comapnies = get_permalink(get_page_by_path('listings')) . '?page=my-companies&type=all';
                $bnfw = BNFW::factory();
                if ($bnfw->notifier->notification_exists('new-company')) {
                    $notifications = $bnfw->notifier->get_notifications('new-company');
                    foreach ($notifications as $notification) {
                        $setting = $bnfw->notifier->read_settings($notification->ID);
                        foreach ($setting['users'] as $users_role) {
                            $main_role = strtolower(str_replace('role-', '', $users_role));
                            $sql = "select `user_email`,`display_name` from " . $wpdb->prefix . "users where `user_type` = '" . $main_role . "'";
                            $staff_user = $wpdb->get_results($sql);
                            foreach ($staff_user as $staff_user) {
                                $emailstaff = $staff_user->user_email;
                                $username_staff = ucwords($staff_user->display_name);
                                $subjectstaff = str_replace('New Company Added', 'Existing Company Details Updated', $setting['subject']);
                                $date = date('Y-m-d');
                                $messagestaff = $setting['message'];
                                $messagestaff = str_replace('[firstname]', $firstname_users, $messagestaff);
                                $messagestaff = str_replace('[lastname]', $lastname_users, $messagestaff);
                                $messagestaff = str_replace('[username]', $username_staff, $messagestaff);
                                $messagestaff = str_replace('[date]', $date, $messagestaff);
                                $messagestaff = str_replace('[companyname]', $_POST['name'], $messagestaff);
                                $messagestaff = str_replace('[linkToCompany]', $link_to_comapnies, $messagestaff);
                                $subjectstaff = str_replace('[firstname]', $firstname_users, $subjectstaff);
                                $subjectstaff = str_replace('[lastname]', $lastname_users, $subjectstaff);
                                wp_mail($emailstaff, $subjectstaff, wpautop($messagestaff));
                            }
                        }
                    }
                }
            }
            if ($isstaff) {
                try {
                    staff_company_approve($company_id, false);  // approve company
                } catch (Exception $e) {
                    $_SESSION['company_error_msg'] = "CRM Approve ERROR: " . $e->getMessage();
                    wp_redirect($_SERVER["HTTP_REFERER"]);
                    exit;
                }
            }
            /* approve user at the time of company creation */
            if ($isstaff) {
                try {
                    approve_user_by_staff($user_id, false);  // approve user
                } catch (Exception $e) {
                    $_SESSION['user_error_msg'] = "CRM Approve ERROR: " . $e->getMessage();
                    wp_redirect($_SERVER["HTTP_REFERER"]);
                    exit;
                }
            }
            if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                set_site_message('my-companies', 'success', "Company Added Successfully");
                //$_SESSION['wp_notices']['company_added'] = __("Company Added Successfully");
                wp_redirect($_POST['redirect_url']);
            } else {
                set_site_message('my-companies', 'success', "Company Added Successfully");
                //  $_SESSION['wp_notices']['company_added'] = __("Company Added Successfully");
                wp_redirect(admin_url() . 'admin.php?page=' . $_GET['page']);
            }
        } else {
            $status = "Modified";
            $company_result = $wpdb->get_results("SELECT status, crm_id FROM " . $wpdb->prefix . "company where " . $wpdb->prefix . "company.id = " . $_POST['id']);
            $users = $wpdb->get_results("SELECT ID FROM " . $wpdb->prefix . "users where company_id = " . $_POST['id']);
            //Do not update company status until it is approved
            if ((!empty($company_result)) && ($company_result[0]->crm_id == '')) {
                $status = $company_result[0]->status;
            }
            if ((!empty($users))) {
                $user_id = $users[0]->ID;
            }
            $company_type = isset($_POST['company_type']) ? $_POST['company_type'] : "Company";

            $update_item_sql ='SELECT '.$wpdb->prefix .'users.salutaions,'.$wpdb->prefix .'users.first_name as firstname,'.$wpdb->prefix .'users.last_name as lastname,'.$wpdb->prefix .'company.email,'.$wpdb->prefix .'company.phone,'.$wpdb->prefix .'company.fax,'.$wpdb->prefix .'company.name,'.$wpdb->prefix .'company.address,'.$wpdb->prefix .'company.preferredcontactmethodcode,'.$wpdb->prefix .'company.city,'.$wpdb->prefix .'company.zipcode,'.$wpdb->prefix .'company.state,'.$wpdb->prefix .'company.country,'.$wpdb->prefix .'company.website_url FROM '.$wpdb->prefix .'company JOIN '.$wpdb->prefix .'users on '.$wpdb->prefix .'users.company_id='.$wpdb->prefix .'company.id where '.$wpdb->prefix .'company.id='.$_POST['id'].' AND '.$wpdb->prefix .'users.ID='.$_POST['user_id'];
                $update_item_result = $wpdb->get_results($update_item_sql);
                $_POST['preferredcontactmethodcode'] = isset($_POST['preferredcontactmethodcode'])?$_POST['preferredcontactmethodcode']:'Email';
                $post_array = array_slice($_POST, 0, 8, true) +
                array("preferredcontactmethodcode" => $_POST['preferredcontactmethodcode']) +
                array_slice($_POST, 3, count($_POST)-3, true);
                $update_array = json_decode(json_encode($update_item_result),true) ;
                $diff_array = array_diff($_POST, $update_array[0]);
                $updated_string = '';
                foreach ($diff_array as $key => $value) {
                    if(isset($update_array[0][$key]) && isset($diff_array[$key])){
                        switch ($key) {
                            case 'firstname':
                                $label = 'First Name';
                                break;
                            case 'lastname':
                                $label = 'Last Name';
                                break;
                            case 'preferredcontactmethodcode':
                                $label = 'Preferred Contact Method';
                                break;
                            case 'name':
                                $label = 'Company Name';
                                break;
                            default :
                                $label = $key;
                        }
                        $updated_string .= ucfirst(str_replace("_", ' ', $label))." from '".$update_array[0][$key]."' to '".$diff_array[$key]."'<br/>";
                    }
                }
                //Add updated data in logger
                $json_data = json_encode($diff_array);
                $log_data = array(); //Declare array to stored log data
                $log_data['ref_type'] = "company";
                $log_data['ref_id'] = $_POST['id'];
                $log_data['title'] = "Company Updated";
                $log_data['description'] = "Company Updated From Portal";
                $log_data['content'] = $json_data;
                //Used to log activity
                IB_Logging::ib_log_activity($log_data);

            $wpdb->update($wpdb->prefix . 'company', array('name' => $_POST['name'], 'email' => $_POST['email'], 'address' => $_POST['address'], 'preferredcontactmethodcode' => $_POST['preferredcontactmethodcode'], 'phone' => $_POST['phone'], 'fax' => $_POST['fax'], 'country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zipcode' => $_POST['zipcode'], 'website_url' => $_POST['website_url'], 'status' => $status, 'modified_by' => get_current_user_id(), 'modified_on' => date('Y-m-d H:m:s'), 'type' => $company_type), array('id' => $_POST['id']));
            /* update user details */
            $wpdb->update($wpdb->prefix . 'users', array('first_name' => $_POST['firstname'], 'last_name' => $_POST['lastname'], 'phone' => $_POST['phone'], 'preferred_form' => $_POST['preferredcontactmethodcode'], 'modified_by' => get_current_user_id(), 'modified_on' => date('Y-m-d H:m:s'), 'country' => $_POST['country'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'zipcode' => $_POST['zipcode'], 'website_url' => $_POST['website_url']), array('ID' => $_POST['user_id']));
            /* update quotation progarm company details*/
            $wpdb->update($wpdb->prefix . 'quotation_program', array('companyname' => $_POST['name']), array('company_id' => $_POST['id']));
            /* assign role agent if company is agency */
            if (isset($isstaff) && $isstaff) {
                if ($company_type == 'Agency') {
                    $agent_array = get_role("Agent");
                    if (empty($agent_array)) {
                        add_role('agent', 'Agent', array('read' => true, 'application-form' => true));
                        $user = new WP_User($user_id);
                        $user->add_cap('Agent');
                    } else {
                        $user = new WP_User($user_id);
                        $user->add_cap('Agent');
                    }
                } else {
                    $user = new WP_User($user_id);
                    $user->remove_cap('Agent');
                }
            }
            if (isset($isstaff) && $isstaff) {
                try {
                    $company_sql = 'SELECT crm_id FROM ' . $wpdb->prefix . 'company where id="' . $_POST['id'] . '"';
                    $company_result = $wpdb->get_results($company_sql);
                    if (!empty($company_result) && !empty($company_result[0]->crm_id)) {
                        staff_company_approve($_POST['id'], true); // approve company
                    } else {
                        staff_company_approve($_POST['id'], false); // approve company
                    }
                } catch (Exception $e) {
                    $_SESSION['company_error_msg'] = "CRM Approve ERROR: " . $e->getMessage();
                    wp_redirect($_SERVER["HTTP_REFERER"]);
                    exit;
                }
            }
            $sql = 'SELECT * FROM ' . $wpdb->prefix . 'users where ID=' . get_current_user_id();
            $result = $wpdb->get_results($sql);
            $get_current_user_role_new = get_current_user_role();
            if (strtolower($get_current_user_role_new) != 'staff') {
                $firstname_users = $result[0]->first_name;
                $lastname_users = $result[0]->last_name;
                $link_to_comapnies = get_permalink(get_page_by_path('listings')) . '?page=my-companies&type=modified';
                $bnfw = BNFW::factory();
                if ($bnfw->notifier->notification_exists('existing-company-update')) {
                    $notifications = $bnfw->notifier->get_notifications('existing-company-update');
                    foreach ($notifications as $notification) {
                        $setting = $bnfw->notifier->read_settings($notification->ID);
                        foreach ($setting['users'] as $users_role) {
                            $main_role = strtolower(str_replace('role-', '', $users_role));
                            $sql = "select `user_email`,`display_name` from " . $wpdb->prefix . "users where `user_type` = '" . $main_role . "'";
                            $staff_user = $wpdb->get_results($sql);
                            foreach ($staff_user as $staff_user) {
                                $emailstaff = $staff_user->user_email;
                                $username_staff = ucwords($staff_user->display_name);
                                $subjectstaff = $setting['subject'];
                                $subjectstaff = str_replace("[firstname]", $firstname_users, $subjectstaff);
                                $subjectstaff = str_replace('[lastname]', $lastname_users, $subjectstaff);
                                $date = date('Y-m-d');
                                $messagestaff = $setting['message'];
                                //$messagestaff = str_replace('[common_header]', $common_header, $messagestaff);
                                $messagestaff = str_replace('[firstname]', $firstname_users, $messagestaff);
                                $messagestaff = str_replace('[lastname]', $lastname_users, $messagestaff);
                                $messagestaff = str_replace('[username]', $username_staff, $messagestaff);
                                $messagestaff = str_replace('[date]', $date, $messagestaff);
                                $messagestaff = str_replace('[companyname]', $_POST['name'], $messagestaff);
                                $messagestaff = str_replace('[linkToCompany]', $link_to_comapnies, $messagestaff);
                                $messagestaff = str_replace("[update_fields]", $updated_string, $messagestaff);
                                $subjectstaff = str_replace('[firstname]', $firstname_users, $subjectstaff);
                                $subjectstaff = str_replace('[lastname]', $lastname_users, $subjectstaff);
                                if($updated_string!=''){
                                    wp_mail($emailstaff, $subjectstaff, wpautop($messagestaff));
                                }
                            }
                        }
                    }
                }
            }
            if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                set_site_message('my-companies', 'success', "Company Details Updated Successfully");
                // $_SESSION['wp_notices']['company_updated'] = __("Company Updated Successfully");
                wp_redirect($_POST['redirect_url']);
            } else {
                set_site_message('my-companies', 'success', "Company Details Updated Successfully");
                //$_SESSION['wp_notices']['company_updated'] = __("Company Updated Successfully");
                wp_redirect(admin_url() . 'admin.php?page=' . $_GET['page']);
            }
        }
    //}
}
/* 0846 - 08/06/15 - function to delete companies from all companies as well as my companies page */
add_action('admin_post_delete_company', 'delete_application_company');
function delete_application_company() {
    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'company', array('id' => $_GET['hash']));
    if (isset($_GET['redirect_url']) && !empty($_GET['redirect_url'])) {
        set_site_message('my-companies', 'success', "Company Deleted Successfully");
        //  $_SESSION['wp_notices']['company_deleted'] = __("Company Deleted Successfully");
        wp_redirect(base64_decode($_GET['redirect_url']));
    } else {
        set_site_message('my-companies', 'success', "Company Deleted Successfully");
        //$_SESSION['wp_notices']['company_deleted'] = __("Company Deleted Successfully");
        wp_redirect(admin_url() . 'admin.php?page=' . $_GET['page']);
    }
}
/* 0787 - 27/06/15 - function to list invoice */
// add_shortcode('my-invoices-list', 'my_invoices_list');
// function my_invoices_list() {
//     include('my-invoices-list.php');
// }
/* backend designing code start */
// move admin bar to right
/* backend designing code end */
add_action('wp_ajax_remove_application_documents', 'remove_application_documents'); // Remove application docs
function remove_application_documents($id='',$application_id='',$doc_id='',$doc_category='') {
    global $wpdb;
    $doc_row_id = isset($_POST['doc_row_id'])?$_POST['doc_row_id']:$id; //$doc_row_id is row id in wp_application_docs table
    $app_id = isset($_POST['app_id'])?$_POST['app_id']:$application_id;
    $category_id = isset($_POST['category_id'])?$_POST['category_id']:$doc_id;
    $appdoc_id = isset($_POST['appdoc_id'])?$_POST['appdoc_id']:$doc_category;
    //deleting file from server
    $sql = "SELECT * FROM " . $wpdb->prefix . "application_docs WHERE id = " . $doc_row_id;
    $doc_row = $wpdb->get_row($sql);
	$deleted_doc_path = get_post_meta( $doc_row->doc_id , '_wp_attached_file', true );
    wp_delete_attachment($doc_row->doc_id, true);
    //deleting file from server
    //delete document in wp_application_docs table
    $wpdb->delete($wpdb->prefix . 'application_docs', array('id' => $doc_row_id));
    //Adding Document data in logger on updation
    $doc_path = array();
    $sql = "SELECT wad.doc_id,wt.slug FROM " . $wpdb->prefix . "application_docs wad, " . $wpdb->prefix . "terms wt WHERE wad.doc_category = wt.term_id AND application_id = " . $app_id . " ORDER By id DESC";
    $app_docs = $wpdb->get_results($sql);
    if ($app_docs) {
        foreach ($app_docs as $doc):
            $sql = "SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE post_id = " . $doc->doc_id;
            $post = $wpdb->get_row($sql);
            if (isset($doc_path[$doc->slug]) && isset($post->meta_value))
                $doc_path[$doc->slug] .= ";" . $post->meta_value;
            else
                if(isset($post->meta_value)){
                $doc_path[$doc->slug] = $post->meta_value;}
        endforeach;
        $json_data = json_encode($doc_path);
        $log_data = array(); //Declare array to stored log data
        $log_data['ref_type'] = "Application_docs";
        $log_data['ref_id'] = $app_id;
        $log_data['title'] = "Application Docs Updated";
        $log_data['description'] = "Application Docs Updated From Portal";
        $log_data['content'] = $json_data;
        //Used to log activity
        IB_Logging::ib_log_activity($log_data);
    }else
    {
        $json_data = json_encode($doc_path);
        $log_data = array(); //Declare array to stored log data
        $log_data['ref_type'] = "Application_docs";
        $log_data['ref_id'] = $app_id;
        $log_data['title'] = "Application Docs Updated";
        $log_data['description'] = "Application Docs Updated From Portal";
        $log_data['content'] = $json_data;
        //Used to log activity
        IB_Logging::ib_log_activity($log_data);
    }
    //Adding Document data in logger on updation
    //Adding document data in cron table
    $sql = "SELECT slug FROM " . $wpdb->prefix . "terms WHERE term_id = " . $category_id;
    $category = $wpdb->get_row($sql);
    if(isset($doc_row->doc_id) && isset($category->slug)){
        $wpdb->query("INSERT INTO " . $wpdb->prefix . "cron_files (post_id, category_name,file_path,action_type,ref_type,ref_id,status,attempt) VALUES(" . $doc_row->doc_id . ",'" . $category->slug . "','" . $deleted_doc_path . "','remove','application'," . $app_id . ",'open','0')");
    }
    //Adding document data in cron table
    //retrieving documents from database
    $sql = "SELECT * FROM " . $wpdb->prefix . "application_docs WHERE application_id = " . $app_id . " AND doc_category = " . $category_id . " ORDER BY id DESC";
    $category_docs = $wpdb->get_results($sql);

    foreach ($category_docs as $doc) {
        if(basename(wp_get_attachment_url($doc->doc_id))!=''){?>
        <div class="col-md-8">
            <div class="pull-left">
				<?php
				$filedata = wp_check_filetype( basename(wp_get_attachment_url( $doc->doc_id )));
				if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps") $fileclass = "file-icon-word";
				elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et") $fileclass = "file-icon-excel";
				elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png") $fileclass = "file-icon-image";
				elseif ($filedata["ext"] == "pdf") $fileclass = "file-icon-pdf";
				elseif ($filedata["ext"] == "zip") $fileclass = "file-icon-zip";
				elseif ($filedata["ext"] == "ppt") $fileclass = "file-icon-ppt";

				?>
				<span class="<?php if(isset($fileclass)){echo $fileclass;}?>"></span>
                <span class="file-icon-label">
                    <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                </span>
            </div>
            <div class="pull-right">
                <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" title="Download" download> &nbsp; </a>
                <input class="btn-wizard-upload margin-left-10 removefile" type='button' title="Remove" value='Remove' id='<?php echo $doc->id . "_" . $doc->application_id . "_" . $doc->doc_category . "_" . $appdoc_id; ?>' style="display:inline;">
            </div>
        </div>
        <div class="divider-10"></div>
        <?php }
    }if(isset($_GET['action']) && $_GET['action']=='delete-application-form')
    {
    }else
    {
        die;
    }
    //retrieving documents from database
}
function add_staff_caps() {
    // gets the author role
    global $current_user; // Use global
    get_currentuserinfo(); // Make sure global is set, if not set it.
    if (user_can($current_user, "staff")) {
        $user = new WP_User(get_current_user_id());
        $user->add_cap('list_users');
    } else {
        $user = new WP_User(get_current_user_id());
        $user->remove_cap('list_users');
    }
    // This only works, because it accesses the class instance.
    // would allow the author to  others' posts for current theme only
    //$role->add_cap( '_others_posts' );
}
add_action('admin_init', 'add_staff_caps');
add_action('manage_users_columns', 'remove_user_posts_column');
function remove_user_posts_column($column_headers) {
    unset($column_headers['posts']);
    return $column_headers;
}
// Function is used to display application activity log
add_shortcode('app-log', 'app_log');
function app_log() {
    require_once 'app-diff.php';
}
/* terms and condition show popup */
add_action('admin_post_terms_condition', 'terms_condition');
function terms_condition() {
    include('terms_condition.php');
}

/* Ajax function to get data from CRM */
add_action('wp_ajax_crm_application', 'crm_application_callback');
function crm_application_callback() {
    global $current_user, $wpdb;
	isset($_POST['length']) ? $lenght = $_POST['length'] : $lenght = 100;
    isset($_POST['start']) ? $start = $_POST['start'] : $start = 0;
    $where = array();
    if (isset($_POST['search']['value']) and ! empty($_POST['search']['value'])) {
        $search = $_POST['search']['value'];
        $where[] = " application_name like '%$search%' ";
    }
    
    if (isset($_POST['columns'][1]['search']['value']) and ! empty($_POST['columns'][1]['search']['value']) && $_POST['columns'][1]['search']['value']!='all') {
        $certificate_status = $_POST['columns'][1]['search']['value'];
        /* if($certificate_status == 'new_application'){
          $where[] = " (status like '%new_application%' OR status like '%modified%') ";
          } else */if ($certificate_status == 'approved') {
            $where[] = " (status like '%approved%' OR status like '%in_review%') ";
        } else {
            $where[] = " status like '%$certificate_status%' ";
        }
    }
    else if($_POST['columns'][1]['search']['value']=='' && isset($_POST['type']) && $_POST['type']!='all')
    {   
        $certificate_status = $_POST['type'];
        if ($certificate_status == 'approved') {
            $where[] = " (status like '%approved%' OR status like '%in_review%') ";
        } else {
            $where[] = " status like '%$certificate_status%' ";
        }
    }

    if (isset($_POST['order'][0]['column']) and ! empty($_POST['order'][0]['column'])) {
        switch($_POST['order'][0]['column'])
        {
            case 0:
                $order =  $wpdb->prefix . "application_data.id";
                break;
            case 1:
                $order =  $wpdb->prefix . "application_data.application_name";
                break;
            case 2:
                $order =  $wpdb->prefix . "application_data.certificate_name";
                break;
//             case 3:
//                 $order =  $wpdb->prefix . "application_data.id";
//                 break;
//             case 4:
//                 $order =  $wpdb->prefix . "application_data.id";
//                 break;
//             case 5:
//                 $order =  $wpdb->prefix . "application_data.id";
                break;
            case 6:
                $order =  $wpdb->prefix . "application_data.status";
                break;
            default:
                $order = $wpdb->prefix . "application_data.id";
                break;
        }
    }else
    {
        $order = $wpdb->prefix . "application_data.id";
    }

    if (isset($_POST['order'][0]['dir']) and ! empty($_POST['order'][0]['dir'])) {
        $by = $_POST['order'][0]['dir'];
    }else
    {
        $by = 'DESC';
    }

	if (isset($_POST['columns'][4]['search']['value']) and ! empty($_POST['columns'][4]['search']['value']) && $_POST['columns'][4]['search']['value']!='all') {
        $search_program = $_POST['columns'][4]['search']['value'];
		$program_id =  $wpdb->get_var("SELECT id FROM " . $wpdb->prefix . "programs where name='".$search_program."'");
        if ($search_program != '') {
            $where[] = " (wp_application_data.program_id =".$program_id.") ";
			if (strtolower($_POST['role']) == 'staff') {
				$where[] = '(status != "Draft")';
			}
        }
    }
    else if($_POST['columns'][4]['search']['value']=='' && isset($_POST['program_name']) && $_POST['program_name']!='all')
    {
        $search_program = $_POST['program_name'];
        $program_id =  $wpdb->get_var("SELECT id FROM " . $wpdb->prefix . "programs where name='".$search_program."'");
        if ($search_program != '') {
            $where[] = " (wp_application_data.program_id =".$program_id.") ";
            if (strtolower($_POST['role']) == 'staff') {
                $where[] = '(status != "Draft")';
            }
        }
    }

    if (!empty($where)) {
        $where = ' WHERE ' . implode('AND', $where);
    } else {
        if (strtolower($_POST['role']) == 'customer') {
            $where = 'WHERE status !="delete"';
        } else {
            $where =  'WHERE status != "Draft"';
        }
    }
    $role = strtolower($_POST['role']);

    $roles_join = '';
    $customer__join='';
    $k=1;
    $or_condition= '';
    if($role=='contact')
    {
    	$roles_sql = "SELECT * FROM " . $wpdb->prefix . "application_user_roles where user_id=".$current_user->ID." group by roles";
    	$result = $wpdb->get_results($roles_sql);
    	$roles_join .= "LEFT JOIN " . $wpdb->prefix . "application_user_roles as roles on (roles.application_id = " . $wpdb->prefix . "application_data.id AND roles.user_id =".$current_user->ID.") ";
        $or_condition = 'OR roles.user_id ='.$current_user->ID." ) ";
    	// foreach ($result as $key => $value) {

    	// 	$roles_array = unserialize($value->roles);
    	// 	$or_condition='';
    	// 	foreach ($roles_array[0] as $key => $value) {
    	// 		if(count($result)>1)
    	// 		{
    	// 			if($k!=count($result))
    	// 			{
    	// 				$where .= ' AND roles.roles like "%'.$key.'%" OR ';
    	// 			}else
    	// 			{
    	// 				$where .= ' roles.roles like "%'.$key.'%"';
    	// 			}

    	// 		}else
    	// 		{
    	// 			$where .= ' AND roles.roles like "%'.$key.'%"';
    	// 		}
    	// 		$k++;
    	// 	}
            
    	// }
        if(isset($or_condition))
        {
            $where .= ' AND (' . $wpdb->prefix . 'application_data.company_id='.$current_user->company_id;
        }else
        {
            $where .= ' AND ' . $wpdb->prefix . 'application_data.company_id='.$current_user->company_id;
        }
    }else if ($role != 'staff' && $role!='assessor') {
        //$customer__join = ' Inner Join ' . $wpdb->prefix . 'users as u on u.company_id =  `' . $wpdb->prefix . 'application_data`.company_id and u.ID =' . $current_user->ID;
		//$customer__join = 'AND wp_application_data.user_id='.$current_user->ID;
		$where .= ' AND ' . $wpdb->prefix . 'application_data.company_id='.$current_user->company_id;
		//$where .='AND wp_application_data.user_id='.$current_user->ID;
    }
    // Filter application data if staff login
    $program__join = '';
    if ($role == 'staff') {
        $program__join = ' Inner join ' . $wpdb->prefix . 'program_user_association as pua on `' . $wpdb->prefix . 'application_data`.program_id = pua.program_id ';
		$where .='AND pua.user_id =' . $current_user->ID;
        //$where .= " GROUP BY pua.program_id";
    }
    $renewal_join = ' LEFT JOIN ' . $wpdb->prefix . 'application_renewal_notification as arn on (arn.application_id = ' . $wpdb->prefix . 'application_data.id)';
    $renewal_fields = ", arn.staff_last_notified_on, arn.customer_last_notified_on, arn.is_renewed ";
	//echo "SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . $where . " ORDER BY wp_application_data.id DESC LIMIT $start,$lenght ";die;
    $results = $wpdb->get_results("SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . " ". $roles_join." ".$where . " ".$or_condition." GROUP BY " . $wpdb->prefix . "application_data.id ORDER BY " . $order . " ".$by." LIMIT $start,$lenght ");
    //echo $wpdb->last_query;die;
	//echo "SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . $where . " ORDER BY wp_application_data.id DESC LIMIT $start,$lenght ";die;
    $return = array();
    $return["recordsTotal"] = $return["recordsFiltered"] = $wpdb->get_var("SELECT count(wp_application_data.id) FROM (SELECT count(wp_application_data.id) id FROM `" . $wpdb->prefix . "application_data` ".$program__join." ".$customer__join." ".$roles_join." ".$where." ".$or_condition." GROUP BY " . $wpdb->prefix . "application_data.id) as wp_application_data"); //count($results);
    $return["data"] = array();
    $status = array('new_application' => 'New', 'modified' => 'Modified',
        'approved' => 'In Reveiw', 'send_to_customer' => 'Send To Customer',
        'missing_item' => 'Missing Item', 'cancelled' => 'Cancelled',
        'completed' => 'Completed', 'draft' => 'Draft');
    foreach ($results as $result):
        $appData = json_decode($result->application_data, true);
        $renewal_data = json_decode($result->renewal_options);
        $company_name = '';
		//$country = get_name('country', $appData['new_application']['_linked']['new_country']['new_countryid'], 'country');
        //$state = get_name('state', $appData['new_application']['_linked']['new_state']['new_stateid'], 'state');
        if (isset($result->company_id)) {
            $company_name = get_name('company', $result->company_id, 'name');
			$country = get_name('company', $result->company_id, 'country');
			$country = get_name('country', $country, 'country');
			$state = get_name('company', $result->company_id, 'state');
			$state = get_name('state', $state, 'state');
			$city = get_name('company', $result->company_id, 'city');
        }
		$program_name =  $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "programs where id=".$result->program_id);
        $exp = ($result->application_exp_date != '') ? date('d/m/Y', strtotime($result->application_exp_date)) : '';
        $opr = '<ul>';
		$third_party_array = array(2,7,10);
        $result->status = strtolower($result->status);
		$json = json_decode($result->application_data);

        if (strtolower($_POST['role']) == 'staff') {
            /* $opr .= '<a href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=false"  title="View Application"></a>&nbsp;&nbsp;';
              $opr .= '<a href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;'; */
            if (strtolower($result->status) == 'new' || strtolower($result->status) == 'modified' || strtolower($result->status) == 'draft') {
                $request_url = get_admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($result->id) . '&is_ajax=true';
                $redirect_url = site_url() . '/index.php/listings?page=application-form-register&view=approved';
                if ($result->status == 'modified') {
                    $opr .= '<li><a  title="View Difference From Last Update" href="#app_diff" class="colorbox-inline" onclick="getAppDiff(\'' . $result->id . '\',\'app\', \'' . $company_name . '\', \'' . get_admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($result->id) . '\', \'' . $result->certificate_name . '\', \'' . $redirect_url . '\' );getAppDocDiff(\'' . $result->id . '\', \'doc\', \'' . $company_name . '\')" ><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-random"></span></li></a>';
                }
                if (!empty($result->certificate_name) && !in_array($result->program_id,$third_party_array)) {
                    $opr .= '<li><a href="#" onclick="application_push_to_crm(\'' . $request_url . '\', \'' . $redirect_url . '\')" class="approveLink" value=""  title="Push To CRM"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-okglyphicon glyphicon-ok"></span></li></a>';
                } else {
                                            $certificate_name = (isset($result->certificate_name) && ($result->certificate_name != '')) ? $result->certificate_name : '';
					$new_inspectionagencycert = (isset($json->new_application->_linked->new_certificate->new_inspectionagencycert)) ? $json->new_application->_linked->new_certificate->new_inspectionagencycert:'';
                    $opr .= '<li><a href="#app_certification_popup" id="approve-link-' . $result->id . '" onclick="return openCertificatePopUp(\'' . $result->id . '\',\'' . $result->program_id . '\',\'' . $new_inspectionagencycert . '\',\'' . $certificate_name . '\')" data-href="' . $request_url . '" redirect-href="' . $redirect_url . '" class="colorbox-inline" value=""  title="Push To CRM"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-ok"></span></li></a>';
                }
            }
			if(empty($result->certificate_url)) {
                if(strtolower($result->status) != 'completed'){
	            $opr .= '<li><a  class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
	            }
                if (strtolower($result->status) == 'new' || strtolower($result->status) == 'modified' || strtolower($result->status) == 'draft') {
	                $opr .= '<li><a  title="Edit Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=false"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></li></a>';
	            }
            }
        } else if ((user_can($current_user, "customer") || is_user_company_admin() || is_user_company_contact()) && (empty($result->certificate_url))){
			if(strtolower($result->status) != 'completed'){ 
            $opr .= '<li><a  title="View Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
			}
            if ($result->new_application_id == 0 && strtolower($result->status) != 'completed') {
                $opr .= '<li><a  title="Edit Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></li></a>';
            }
        } else if (user_can($current_user, "assessor") && (empty($result->certificate_url)) && strtolower($result->status) != 'completed') {
            $opr .= '<li><a  title="View Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
        }
        if (is_user_company_admin() && (empty($result->crm_id)) && strtolower($result->status) != 'completed') {
            $opr .= '<li><a title="Delete Application" class="icon-3" href="' . get_admin_url() . 'admin-post.php?action=delete-application-form&id=' . base64_encode($result->id) . '" onclick="return confirm(\'Are you sure you want to delete this application ?\');"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></li></a>';
        }
        if((true == is_user_company_billing() && $current_user->created_by!=get_current_user_id()) && ($result->gp_number=='' || $result->status=='New' || $result->status=='Draft' || $result->crm_id==''))
        {
                $opr = '';
        }
        $renew = '';
		if( ((int)$result->new_application_id == 0) || ($result->is_renewed < 1) ) {
			$button_text = "Renew";
			if (($role == 'staff') && ($result->staff_last_notified_on != '')) {
				$button_text = "Submit";
				if (!empty($renewal_data)) {
					$button_text = "Edit";
				}
				$renew = '<a title="' . $button_text . '" class="btn btn-xs btn-success margin-zero-auto" href="' . site_url() . '/index.php/listings/?page=payment-settings&app_id=' . $result->id . '&certificate_id=' . $result->certificate_crm_id . '">' . $button_text . '</a>';
			} else if (($role == 'customer') && ($result->customer_last_notified_on != '') && (!empty($renewal_data)) ) {
				$renew = '<a title="' . $button_text . '" class="btn btn-xs btn-success margin-zero-auto" href = "' . admin_url() . 'admin-post.php?action=renew_application&appid=' . base64_encode($result->id) . '" title="Renew">'.$button_text.'</a>';
			} else if (($role == 'customer') && ($result->customer_last_notified_on != '') && (empty($renewal_data)) && ($result->status != 'Completed')) {
				$renew = '<a href="#check_renewal_popup" class="btn btn-xs btn-success margin-zero-auto colorbox-inline-payment" title="Renew">Renew</a>';
			}
		}
        $opr.='</ul>';
        $certificate_name = isset($result->certificate_name) ? $result->certificate_name : 'Not Available';
        if (isset($result->certificate_url) && !preg_match("~^(?:f|ht)tps?://~i", $result->certificate_url)) {
            $result->certificate_url = "http://" . $result->certificate_url;
        }
        if(!preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i', $result->certificate_url))
        {
            $result->certificate_url = '';
        }
        if(isset($state) && $state!='' && isset($country) && $country!='')
        {
            $add = $state . ', ' . $country;
        }else if(isset($state) && $state!='')
        {
            $add = $state;
        }else if(isset($country) && $country!='')
        {
            $add = $country;
        }else
        {
            $add = '-';
        }
		$certificate_url = (isset($result->certificate_url) && (!empty($result->certificate_url)) && $certificate_name!='Not Available') ? '<a class="pull-left certificate_url" target="_blank" style="line-height: 25px;width:150px;" download="" href="' . $result->certificate_url . '">'.$certificate_name.'<span class="btn-wizard-download-tbl">&nbsp;</span></a>' : $certificate_name;
        $application_name = isset($result->application_name) ? $result->application_name : 'Not Available';
        $return["data"][] = array(
            $result->id, $application_name,$certificate_url /*$certificate_name,*/
            , $program_name,
            /*$city*/$company_name,
            $add,
            ucwords($result->status), /*$exp,
            $renew, '',*/
            $opr
        );
    endforeach;
    echo json_encode($return);
    die;
}
add_action('wp_ajax_retrieve_application_scope_documents', 'retrieve_application_scope_documents'); // Remove application docs
function retrieve_application_scope_documents() {
    global $wpdb;

    $app_id = $_POST['app_id'];
    if (isset($_POST['category_id']) && isset($_POST['appdoc_id'])) {
        $category_id = $_POST['category_id'];
        $appdoc_id = $_POST['appdoc_id'];
        //retrieving documents from database
        $sql = "SELECT * FROM " . $wpdb->prefix . "application_docs WHERE application_id = " . $app_id . " AND doc_category = " . $category_id . " ORDER BY id DESC";
        $category_docs = $wpdb->get_results($sql);
        foreach ($category_docs as $doc) {
            ?>
            <div class="col-md-8">
                <div class="pull-left">
                     <?php
					$filedata = wp_check_filetype( basename(wp_get_attachment_url( $doc->doc_id )));
					if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps") $fileclass = "file-icon-word";
					elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et") $fileclass = "file-icon-excel";
					elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png") $fileclass = "file-icon-image";
					elseif ($filedata["ext"] == "pdf") $fileclass = "file-icon-pdf";
					elseif ($filedata["ext"] == "zip") $fileclass = "file-icon-zip";
					elseif ($filedata["ext"] == "ppt") $fileclass = "file-icon-ppt";

					?>
					<span class="<?php echo $fileclass;?>"></span>
                    <span class="file-icon-label">
                        <?php echo basename(wp_get_attachment_url($doc->doc_id)); ?>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" title="Download" download> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 removefile" type='button' value='Remove' title="Remove" id='<?php echo $doc->id . "_" . $doc->application_id . "_" . $doc->doc_category . "_" . $appdoc_id; ?>' style="display:inline;">
                </div>
            </div>
            <div class="divider-10"></div>
            <?php
        }
        //retrieving documents from database
        die();
    }
}
function get_name($table_name = '', $id = '', $attribute_name = '') {
    if (!empty($table_name) AND ! empty($id) AND ! empty($attribute_name)) {
        global $wpdb;
        return $wpdb->get_var("SELECT $attribute_name FROM `" . $wpdb->prefix . "$table_name` WHERE id = $id");
    }
    return '';
}
/* 0846 -send address details when company select at the time of application form */
add_action('wp_ajax_change_address', 'change_address');
function change_address() {
    if ($_POST['select_value'] != "") {
        global $wpdb;
        $select_query = "Select address,country,state,city,zipcode,email,phone,fax,website_url from " . $wpdb->prefix . "company where id=" . $_POST['select_value'];
        $result = $wpdb->get_results($select_query);
        echo json_encode($result);
        die;
    } else {
        echo "0";
        die;
    }
}
add_action('wp_ajax_save_program_document', 'save_program_document'); // Call when user upload program document
add_action('admin_post_save_program_document', 'save_program_document');
function save_program_document() {
	remove_filter('upload_dir', 'mgmt_third_party');
    global $wpdb;
    $program_id = $_POST['program_id'];
	if($_FILES){
    foreach ($_FILES as $file) {
        $upload = wp_handle_upload($file, array('test_form' => false));
        if (!isset($upload['error']) && isset($upload['file'])) {
            //uploading file in upload folder
            $filetype = wp_check_filetype(basename($upload['file']), null);
            $title = $file['name'];
            $ext = strrchr($title, '.');
            $title = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
            $attachment = array(
                'post_title' => addslashes($title),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $doc_post_id = wp_insert_attachment($attachment, $upload['file']);   //document post id
            //uploading file in upload folder
            //saving file in database
            $wpdb->insert($wpdb->prefix . 'program_docs', array('program_id' => $program_id, 'doc_id' => $doc_post_id));
            //saving file in database
        }
    }
	}

    //retrieving documents from database
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $program_id . " AND additional!=1 ORDER BY ID DESC";
    $program_docs = $wpdb->get_results($sql);
    if ($program_docs) {
        foreach ($program_docs as $doc) {
            ?>
            <div class="divider-15"></div>
            <div class="col-md-12">
                <div class="pull-left">
                    <?php
					$filedata = wp_check_filetype( basename(wp_get_attachment_url( $doc->doc_id )));
					if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps") $fileclass = "file-icon-word";
					elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et") $fileclass = "file-icon-excel";
					elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png") $fileclass = "file-icon-image";
					elseif ($filedata["ext"] == "pdf") $fileclass = "file-icon-pdf";
					elseif ($filedata["ext"] == "zip") $fileclass = "file-icon-zip";
					elseif ($filedata["ext"] == "ppt") $fileclass = "file-icon-ppt";

					?>
					<span class="<?php echo $fileclass;?>"></span>
                    <span class="file-icon-label">
                        <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" title="Download" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" title="Download" download> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 remove_programfile" type='button' value='Remove' id='<?php echo $doc->ID . "_" . $doc->program_id; ?>' style="display:inline;" title="Remove">
                </div>
            </div>
            <?php
        }
    }
    //retrieving documents from database
    die;
}



function mgmt_third_party( $param ){
    $mydir = '/mgmt_third_party';

    $param['path'] = $param['path'] . $mydir;
    $param['url'] = $param['url'] . $mydir;

    error_log("path={$param['path']}");
    error_log("url={$param['url']}");
    error_log("subdir={$param['subdir']}");
    error_log("basedir={$param['basedir']}");
    error_log("baseurl={$param['baseurl']}");
    error_log("error={$param['error']}");

    return $param;
}

add_filter('upload_dir', 'mgmt_third_party');

add_action('wp_ajax_save_additional_program_document', 'save_additional_program_document'); // Call when user upload program document
add_action('admin_post_save_additional_program_document', 'save_additional_program_document');
function save_additional_program_document() {
	global $wpdb;
    $program_id = $_POST['program_id'];
	if($_FILES){
    foreach ($_FILES as $file) {
		$upload = wp_handle_upload($file, array('test_form' => false));
        if (!isset($upload['error']) && isset($upload['file'])) {
            //uploading file in upload folder
            $filetype = wp_check_filetype(basename($upload['file']), null);
            $title = $file['name'];
            $ext = strrchr($title, '.');
            $title = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
            $attachment = array(
                'post_title' => addslashes($title),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $doc_post_id = wp_insert_attachment($attachment, $upload['file']);   //document post id
            //uploading file in upload folder
            //saving file in database
            $wpdb->insert($wpdb->prefix . 'program_docs', array('program_id' => $program_id, 'doc_id' => $doc_post_id,'additional' => 1));
            //saving file in database
        }
    }
	}

    //retrieving documents from database
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $program_id . " AND additional=1 ORDER BY ID DESC";
    $program_docs = $wpdb->get_results($sql);
    if ($program_docs) {
        foreach ($program_docs as $doc) {
            ?>
            <div class="divider-15"></div>
            <div class="col-md-12">
                <div class="pull-left">
                    <?php
					$filedata = wp_check_filetype( basename(wp_get_attachment_url( $doc->doc_id )));
					if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps") $fileclass = "file-icon-word";
					elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et") $fileclass = "file-icon-excel";
					elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png") $fileclass = "file-icon-image";
					elseif ($filedata["ext"] == "pdf") $fileclass = "file-icon-pdf";
					elseif ($filedata["ext"] == "zip") $fileclass = "file-icon-zip";
					elseif ($filedata["ext"] == "ppt") $fileclass = "file-icon-ppt";

					?>
					<span class="<?php echo $fileclass;?>"></span>
                    <span class="file-icon-label">
                        <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" title="Download" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" title="Download" download> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 remove_additional_programfile" type='button' value='Remove' id='<?php echo $doc->ID . "_" . $doc->program_id; ?>' style="display:inline;" title="Remove">
                </div>
            </div>
            <?php
        }
    }
    //retrieving documents from database
    die;
}
add_action('wp_ajax_remove_program_documents', 'remove_program_documents'); // Remove application docs
function remove_program_documents() {
    global $wpdb;
    $doc_row_id = $_POST['doc_row_id']; //$doc_row_id is row id in wp_program_docs table
    $program_id = $_POST['program_id'];
    //deleting file from server
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE id = " . $doc_row_id." AND additional!=1";
    $doc_row = $wpdb->get_row($sql);
    wp_delete_attachment($doc_row->doc_id, true);
    //deleting file from server
    //delete document in wp_application_docs table
    $wpdb->delete($wpdb->prefix . 'program_docs', array('id' => $doc_row_id));
    //retrieving documents from database
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $program_id . " AND additional!=1 ORDER BY ID DESC";
    $program_docs = $wpdb->get_results($sql);
    if ($program_docs) {
        foreach ($program_docs as $doc) {
            ?>
            <div class="divider-15"></div>
            <div class="col-md-12">
                <div class="pull-left">
                    <span class="file-icon-excel"></span>
                    <span class="file-icon-label">
                        <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" title="Download" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>"  download> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 remove_programfile" type='button' title="Remove" value='Remove' id='<?php echo $doc->ID . "_" . $doc->program_id; ?>' style="display:inline;">
                </div>
            </div>
            <?php
        }
    }
    die();
}

add_action('wp_ajax_remove_additional_program_documents', 'remove_additional_program_documents'); // Remove application docs
function remove_additional_program_documents() {
    global $wpdb;
    $doc_row_id = $_POST['doc_row_id']; //$doc_row_id is row id in wp_program_docs table
    $program_id = $_POST['program_id'];
    //deleting file from server
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE id = " . $doc_row_id." AND additional=1";
    $doc_row = $wpdb->get_row($sql);
    wp_delete_attachment($doc_row->doc_id, true);
    //deleting file from server
    //delete document in wp_application_docs table
    $wpdb->delete($wpdb->prefix . 'program_docs', array('id' => $doc_row_id));
    //retrieving documents from database
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $program_id . " AND additional=1 ORDER BY ID DESC";
    $program_docs = $wpdb->get_results($sql);
    if ($program_docs) {
        foreach ($program_docs as $doc) {
            ?>
            <div class="divider-15"></div>
            <div class="col-md-12">
                <div class="pull-left">
                    <span class="file-icon-excel"></span>
                    <span class="file-icon-label">
                        <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" title="Download" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>"  download> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 remove_additional_programfile" type='button' title="Remove" value='Remove' id='<?php echo $doc->ID . "_" . $doc->program_id; ?>' style="display:inline;">
                </div>
            </div>
            <?php
        }
    }
    die();
}
/* 0787--- function to get renewal payment amounts */
add_action('wp_ajax_get_renewal_payment', 'get_renewal_payment');
function get_renewal_payment() {
    global $wpdb;

	// see if payment is already done for the applc
	$getPaymentResult = $wpdb->get_row( 'SELECT * FROM '. $wpdb->prefix .'payment WHERE application_id = ' . $_POST['newapplication_id'] . ' AND status IN ( "Success", "Offline" )' );
	$boolIsPaymentDone = '';
	if( isset( $getPaymentResult ) ) {
		$boolIsPaymentDone = 'disabled = "disabled"';
		$strPaidAmount = 'Amount Paid';
	} else {
		$strPaidAmount = 'Total Payment Due';
	}

    $query = "SELECT renewal_options from " . $wpdb->prefix . "application_data WHERE new_application_id=" . $_POST['newapplication_id'];
    $result = $wpdb->get_results($query);
    if (($wpdb->num_rows > 0) && ((isset($result)) && (isset($result[0])) && isset($result[0]->renewal_options) && (!empty($result[0]->renewal_options)))) {
        $resultstring = '';
        $renewal_data = $result[0]->renewal_options;
        $renewal_data = json_decode($renewal_data);
        if(!empty($renewal_data)) {
        	$renewal_year = ((isset($renewal_data->renewal_year)) && (!empty($renewal_data->renewal_year))) ? $renewal_data->renewal_year : '';
        	$renewal_year_total = 'one_year_total_value';
        	switch($renewal_year) {
        		case '1':
        			$renewal_year_total = 'one_year_total_value';
        			break;
        		case '2':
        			$renewal_year_total = 'two_year_total_value';
        			break;
        		case '3':
        			$renewal_year_total = 'three_year_total_value';
        			break;
        	}
	        $resultstring .= '<table class="wp-list-table widefat fixed pages table table-striped table-hover renew_payment" id="dataTable">
			<thead><tr>';
	        $resultstring .= '<th>Description</th>';
	        $resultstring .= '<th style=" text-align: center; ">Quantity</th>';
	        $resultstring .= '<th style=" text-align: center; "><label><input type="radio" id="one_year_total" class="js-switch-payment one_year" name="renewal_year" value="1" style="display: inline-block;" ';
	        $resultstring .= (($renewal_year == 1 || empty($renewal_year)) ? 'checked': '').' '. $boolIsPaymentDone .'> One Year</label></th>';
	        $resultstring .= '<th style=" text-align: center; "><label><input type="radio" id="two_year_total" class="js-switch-payment two_year"  name="renewal_year" value="2" style="display: inline-block;" ';
	        $resultstring .= (($renewal_year == 2) ? 'checked': '').' '. $boolIsPaymentDone .'> Two Year</label></th>';
	        $resultstring .= '<th style=" text-align: center; "><label><input type="radio" id="three_year_total" class="js-switch-payment three_year"  name="renewal_year" value="3" style="display: inline-block;" ';
	        $resultstring .= (($renewal_year == 3) ? 'checked': '').' '. $boolIsPaymentDone .'> Three Year</label></th>';
	        $resultstring .= '</tr></thead><tbody>';
	        foreach ($renewal_data->items as $value) {
	        	if((isset($value)) && (!empty($value))) {
		            $renewal_field_name = $wpdb->get_results("SELECT name from " . $wpdb->prefix . "application_renewal_fields WHERE slug='" . $value->slug . "'");
		            $renewal_field_name1 = ((isset($renewal_field_name)) && (isset($renewal_field_name[0])) && (isset($renewal_field_name[0]->name))) ? $renewal_field_name[0]->name : "" ;
		            $resultstring .= '<tr>';
		            $resultstring .= '<td>' .$renewal_field_name1. '</td>';
		            $resultstring .= '<td class="text-right">' .(isset($value->qty)? $value->qty :"" ). '</td>';
		            $resultstring .= '<td class="oneyear hideyears text-right">' . format_currency($value->one_year) . '</td>';
		            $resultstring .= '<td class="twoyear hideyears text-right">' . format_currency($value->two_year) . '</td>';
		            $resultstring .= '<td class="threeyear hideyears text-right" style=" text-align: right; ">' . format_currency($value->three_year) . '</td>';
		            $resultstring .= '</tr>';
	        	}
	        }
	        $resultstring .= '</tbody></table>';
	        $resultstring .= '<table class="payment-total renewal-payment-tbl wp-list-table widefat fixed pages table table-striped table-hover renew_payment" id="dataTable" style="display: none;"><thead><tr><th style=" border-right: 1px solid #ddd; width: 28%; ">'. $strPaidAmount .'</th>';
	        $resultstring .= '<th colspan="3" class="text-center"><span id="one_year_total_value" class="test_one_label js-switch-payment_value" style="display:none">' . $renewal_data->total_one . '</span>'
	                . '<span id="two_year_total_value" class="test_two_label js-switch-payment_value" style="display:none">' . $renewal_data->total_two . '</span>'
	                . '<span id="three_year_total_value" class="test_three_label js-switch-payment_value" style="display:none">' . $renewal_data->total_three . '</span>'
	                . '</th>';
	        $resultstring .= '</tr></thead></table>';
        }
        if ($wpdb->num_rows != 0 && (!empty($renewal_data))) {
            echo $resultstring;
            echo "<script>jQuery(document).ready(function() {  jQuery('#".$renewal_year_total."').show();});</script>";
        } else {
        	echo "<script>jQuery(document).ready(function() {  jQuery('.invoice').hide();});</script>";
            echo "Payment details for renewal of this Accreditation has not been set yet, Please contact IAS support for more details";
        }
    } else {
    	echo "<script>jQuery(document).ready(function() {  jQuery('.invoice').hide();});</script>";
        echo "Payment details for renewal of this Accreditation has not been set yet, Please contact IAS support for more details";
    }
    die;
}
/* 0846 get user details by id using ajax in edit contact */
add_action('wp_ajax_get_user_detail_by_id', 'get_user_detail_by_id');
function get_user_detail_by_id() {
    global $wpdb;
    $query = "SELECT salutaions,company_id,first_name,last_name,title,user_email,phone,fax,company_name,address,country,city,state,zipcode from " . $wpdb->prefix . "users WHERE ID=" . $_POST['id'];
    $result = $wpdb->get_results($query);
    $dboperations = Dboperations::getInstance();
    $result[0]->country_name = $dboperations->get_country_by_id($result[0]->country);
    $result[0]->state_name = $dboperations->get_state_by_id($result[0]->state);
    if(!empty($result[0]->company_id)){
    $result[0]->company_name = $dboperations->get_company_data_by_id($result[0]->company_id);
    }else {
    $result[0]->company_name = '';
    }
    echo json_encode($result);
    die;
}
/* 0846 get company details by id using ajax in edit company */
add_action('wp_ajax_get_company_detail_by_id', 'get_company_detail_by_id');
function get_company_detail_by_id() {
    global $wpdb,$current_user;
    $role = get_current_user_role();
    if($role!='Contact' && $role!='Customer')
    {
        $query = "SELECT " . $wpdb->prefix . "company.name, " . $wpdb->prefix . "company.address, " . $wpdb->prefix . "company.country, " . $wpdb->prefix . "company.state, " . $wpdb->prefix . "company.fax, " . $wpdb->prefix . "company.city, " . $wpdb->prefix . "company.type," . $wpdb->prefix . "company.zipcode, " . $wpdb->prefix . "company.website_url," . $wpdb->prefix . "users.ID," . $wpdb->prefix . "users.first_name," . $wpdb->prefix . "users.last_name," . $wpdb->prefix . "users.user_email," . $wpdb->prefix . "company.preferredcontactmethodcode preferred_form," . $wpdb->prefix . "users.phone," . $wpdb->prefix . "users.salutaions from " . $wpdb->prefix . "company join " . $wpdb->prefix . "users on " . $wpdb->prefix . "company.id = " . $wpdb->prefix . "users.company_id  where " . $wpdb->prefix . "users.user_type='customer' AND " . $wpdb->prefix . "users.company_id=".$_POST['id'];
    }else if($role=='Contact')
    {
        $query = "SELECT " . $wpdb->prefix . "company.name, " . $wpdb->prefix . "company.address, " . $wpdb->prefix . "company.country, " . $wpdb->prefix . "company.state, " . $wpdb->prefix . "company.fax, " . $wpdb->prefix . "company.city, " . $wpdb->prefix . "company.type," . $wpdb->prefix . "company.zipcode, " . $wpdb->prefix . "company.website_url," . $wpdb->prefix . "users.ID," . $wpdb->prefix . "users.first_name," . $wpdb->prefix . "users.last_name," . $wpdb->prefix . "users.user_email," . $wpdb->prefix . "company.preferredcontactmethodcode preferred_form," . $wpdb->prefix . "users.phone," . $wpdb->prefix . "users.salutaions from " . $wpdb->prefix . "company join " . $wpdb->prefix . "users on " . $wpdb->prefix . "company.id = " . $wpdb->prefix . "users.company_id  where " . $wpdb->prefix . "users.user_type='customer' AND " . $wpdb->prefix . "users.company_id=".$current_user->company_id;


    }else
    {
        $query = "SELECT " . $wpdb->prefix . "company.name, " . $wpdb->prefix . "company.address, " . $wpdb->prefix . "company.country, " . $wpdb->prefix . "company.state, " . $wpdb->prefix . "company.fax, " . $wpdb->prefix . "company.city, " . $wpdb->prefix . "company.type," . $wpdb->prefix . "company.zipcode, " . $wpdb->prefix . "company.website_url," . $wpdb->prefix . "users.ID," . $wpdb->prefix . "users.first_name," . $wpdb->prefix . "users.last_name," . $wpdb->prefix . "users.user_email," . $wpdb->prefix . "company.preferredcontactmethodcode preferred_form," . $wpdb->prefix . "users.phone," . $wpdb->prefix . "users.salutaions from " . $wpdb->prefix . "company  LEFT JOIN " . $wpdb->prefix . "users on " . $wpdb->prefix . "users.company_id=" . $wpdb->prefix . "company.id WHERE " . $wpdb->prefix . "company.id=" . $_POST['id'];
    }
    
    
    if($role=='Customer')
    {
        $query .= " AND ".$wpdb->prefix."users.ID=".get_current_user_id();
    }
    $result = $wpdb->get_results($query);
    echo json_encode($result);
    die;
}
/* 0787 function for renewal noticification initail reminder */
add_action('wp_ajax_get_reminder_settings', 'get_reminder_settings'); // If the user is logged in
function get_reminder_settings() {
    $settings = get_option('app_renewal_notification_settings');
    if (!empty($settings)) {
        echo $settings;
    } else {
        echo 0;
    }
    die;
}
add_action('wp_ajax_save_reminder_settings', 'save_reminder_settings');
function save_reminder_settings() {
    $result = false;
    $settings = json_decode(stripslashes($_POST['data']), true);
    if (!empty($settings)) {
        $reminder_settings = get_option('app_renewal_notification_settings', 'option_does_not_exist');
        if ($reminder_settings != 'option_does_not_exist') {
            $result = update_option('app_renewal_notification_settings', json_encode($settings), '', 'yes');
        } else {
            $result = add_option('app_renewal_notification_settings', json_encode($settings), '', 'yes');
        }
    }
    echo json_encode($settings);
    exit(0);
}
/* 0846 change company user roles when assign roles from application sections */
function change_user_roles_from_application($technical = '', $billing = '', $legal = '', $chief = '', $application_id = '') {
    $application_id = isset($_POST['editid'])?$_POST['editid']:$application_id;
    global $wpdb;
    /* if user is technical add technical role */
    if (isset($technical) && $technical != '') {
        $query = 'select roles from ' . $wpdb->prefix . 'application_user_roles where user_id=' . $technical . ' AND application_id=' . $application_id;
        $technical_sql = $wpdb->query($query);
        if ($wpdb->num_rows != 0) {
            $roles_result = $wpdb->get_results($query);
            $tech_array = unserialize($roles_result[0]->roles);
            if (!in_array('technical', $tech_array)) {
                $tech_array[0]['technical'] = 1;
                $wpdb->update($wpdb->prefix . "application_user_roles", array('roles' => serialize($tech_array)), array('user_id' => $technical));
            }
        } else {
            $tech_array[0]['technical'] = 1;
            $wpdb->insert($wpdb->prefix . "application_user_roles", array('roles' => serialize($tech_array), 'user_id' => $technical, 'application_id' => $application_id));
        }
    }
    /* if user is billing add billing role */
    if (isset($billing) && $billing != '') {
        $query = 'select roles from ' . $wpdb->prefix . 'application_user_roles where user_id=' . $billing . ' AND application_id=' . $application_id;
        // Add role in company users roles table
        $billing_sql = $wpdb->query($query);
        if ($wpdb->num_rows != 0) {
            $roles_result = $wpdb->get_results($query);
            $billing_array = unserialize($roles_result[0]->roles);
            if (!in_array('billing', $billing_array)) {
                $billing_array[0]['billing'] = 1;
                $wpdb->update($wpdb->prefix . "application_user_roles", array('roles' => serialize($billing_array)), array('user_id' => $billing));
            }
        } else {
            $billing_array[0]['billing'] = 1;
            $wpdb->insert($wpdb->prefix . "application_user_roles", array('roles' => serialize($billing_array), 'user_id' => $billing, 'application_id' => $application_id));
        }
    }
    /* if user is legal add legal role */
    if (isset($legal) && $legal != '') {
        $query = 'select roles from ' . $wpdb->prefix . 'application_user_roles where user_id=' . $legal . ' AND application_id=' . $application_id;
        // Add role in company users roles table
        $legal_sql = $wpdb->query($query);
        if ($wpdb->num_rows != 0) {
            $roles_result = $wpdb->get_results($query);
            $legal_array = unserialize($roles_result[0]->roles);
            if (!in_array('legal', $legal_array)) {
                $legal_array[0]['legal'] = 1;
                $wpdb->update($wpdb->prefix . "application_user_roles", array('roles' => serialize($legal_array)), array('user_id' => $legal));
            }
        } else {
            $legal_array[0]['legal'] = 1;
            $wpdb->insert($wpdb->prefix . "application_user_roles", array('roles' => serialize($legal_array), 'user_id' => $legal, 'application_id' => $application_id));
        }
    }
    /* if user is chief add chief role */
    if (isset($chief) && $chief != '') {
        $query = 'select roles from ' . $wpdb->prefix . 'application_user_roles where user_id=' . $chief . ' AND application_id=' . $application_id;
        // Add role in company users roles table
        $chief_sql = $wpdb->query($query);
        if ($wpdb->num_rows != 0) {
            $roles_result = $wpdb->get_results($query);
            $chief_array = unserialize($roles_result[0]->roles);
            if (!in_array('chief', $chief_array)) {
                $chief_array[0]['chief'] = 1;
                $wpdb->update($wpdb->prefix . "application_user_roles", array('roles' => serialize($chief_array)), array('user_id' => $chief));
            }
        } else {
            $chief_array[0]['chief'] = 1;
            $wpdb->insert($wpdb->prefix . "application_user_roles", array('roles' => serialize($chief_array), 'user_id' => $chief, 'application_id' => $application_id));
        }
    }
}
/* 0846 when application comes for renewal then the below function works */
add_action('admin_post_renew_application', 'renew_application');
function renew_application() {
    global $wpdb;
    $sql = 'SELECT `status`,`program_id`,`user_id`,`company_id`,`application_data`,`created_by`,`created_on`,`modified_by`,`modified_on`,`deleted_by`,`deleted_on`,`crm_id`,`certificate_crm_id`,`quotation_id`,`application_exp_date`,`certificate_name`,`application_name`, `new_application_id`, `gp_number`
	FROM ' . $wpdb->prefix . 'application_data WHERE id = ' . base64_decode($_GET['appid']);
    $new_application_id = base64_decode($_GET['appid']);
    $result = $wpdb->get_row($sql);
    if ($wpdb->num_rows > 0) {
        if ($result->new_application_id != '') {
            $new_application_id = $result->new_application_id;
        } else {
        	$crmOperationsObj = new CrmOperations();
        	$renewed_application_name = $crmOperationsObj->getApplicationName($result->certificate_name,'renewal');
            $wpdb->query("INSERT INTO " . $wpdb->prefix . "application_data (`status`,`program_id`,`user_id`,`company_id`,`application_data`,`created_by`,`created_on`,`modified_by`,`modified_on`,`deleted_by`,`deleted_on`, `certificate_crm_id`,`certificate_name`,`application_name`,`gp_number`) values ('New','" . $result->program_id . "','" . $result->user_id . "','" . $result->company_id . "', '" . $result->application_data . "','" . $result->created_by . "','" . $result->created_on . "','" . $result->modified_by . "', '" . $result->modified_on . "','" . $result->deleted_by . "','" . $result->deleted_on . "','" . $result->certificate_crm_id . "','" . $result->certificate_name . "', '" . $renewed_application_name . "', '" . $result->gp_number . "')");
            //In case of renewed application, wipe out old application data and set new(renewed) application id for getting renewed applications.
            $wpdb->update($wpdb->prefix . "application_data", array('application_data' => NULL, 'new_application_id' => $wpdb->insert_id), array('id' => base64_decode($_GET['appid'])));
            $wpdb->update($wpdb->prefix . "application_docs", array('application_id' => $wpdb->insert_id), array('application_id' => base64_decode($_GET['appid'])));
            $new_application_id = $wpdb->insert_id;
        }
        wp_redirect(site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($new_application_id));
    } else {
        wp_redirect(site_url() . '/index.php/listings/?page=application-form-register&view=all');
    }
}
function smart_logout() {
    if (!is_user_logged_in()) {
        $smart_redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url();
        wp_safe_redirect($smart_redirect_to);
        exit();
    } else {
        check_admin_referer('log-out');
        wp_logout();
        $smart_redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : site_url();
        wp_safe_redirect($smart_redirect_to);
        exit();
    }
}
add_action('login_form_logout', 'smart_logout');
add_action('wp_ajax_change_third_party_number', 'change_third_party_number');
function change_third_party_number() {
    global $wpdb;
    $query = 'SELECT application_data from ' . $wpdb->prefix . 'application_data where id=' . $_POST['id'];
    $result = $wpdb->get_row($query);
	$app_array = json_decode($result->application_data, true);
    $app_array['new_application']['_linked']['new_certificate']['new_inspectionagencycert'] = $_POST['inspection_agency_hidden'];
	$wpdb->update($wpdb->prefix . 'application_data', array('application_data' => json_encode($app_array)), array('id' => $_POST['id']));
	die;
}

/*0787 function to get common header content for sending mail on the basis of slug*/
function common_header_on_notification_mail(){
$page = get_posts( array( 'name' => 'common-header-for-mail','post_type' => 'page' ) );
if (isset($page)){
return $page[0]->post_content;
}
}

add_action( 'login_form', 'change_login_button_text' );
function change_login_button_text()
{
    add_filter( 'gettext', 'get_login_button_text', 10, 2 );
}

function get_login_button_text( $translation, $text )
{
    if ( 'Log In' == $text ) {
        return 'Login';
    }
    return $translation;
}
add_action('admin_post_payment-popup', 'payment_popup');
function payment_popup($error = "", $success = "", $id = "", $label = "") {
	?>
		<script type='text/javascript' src='<?php echo plugin_dir_url(__FILE__); ?>/js/jquery-1.11.1.min.js?ver=1.0.0'></script>
		<link rel='stylesheet' id='bootstrap-css-css'  href='<?php echo plugin_dir_url(__FILE__); ?>/css/bootstrap.min.css?ver=4.2.2' type='text/css' media='all' />
		<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/core/css/ib-custom.css' type='text/css' media='all' />
		<style>body{background-image:none !important;}</style>
	<?php
        $mode = $_GET['mode'];
		if($mode == "paybycheck") $page = get_page_by_path( 'pay-by-check' );
		else if($mode == "paybywire") $page = get_page_by_path( 'pay-by-wire-transfer' );
        $page_id = $page->ID;

		$page_data = get_page( $page_id ); // You must pass in a variable to the get_page function. If you pass in a value (e.g. get_page ( 123 ); ), WordPress will generate an error. By default, this will return an object.

		echo '<div class="pop-heading-wp">'. $page_data->post_title .'</div>';// echo the title
		echo apply_filters('the_content', $page_data->post_content); // echo the content and retain WordPress filters such as paragraph tags.
}
/*0787 function To show ias documents By Program Types */
add_shortcode( 'IAS-documents', 'ias_document' );
function ias_document( ) {
    include('ias-documents.php');
}

/* callback new mitesh 02-10-15 */

add_action('wp_ajax_certificate_callback', 'certificate_callback');
add_shortcode('certificate_list', 'certificate_list');

function certificate_list() {
    global $wpdb, $per_page, $resulttotal, $current_user;
    $role = get_current_user_role();
    $status_opt = '<option value="">All Application</option>';
    if (user_can($current_user, "customer")) {
        $status_opt .='<option value="Draft" title="Draft">Draft</option>';
    }
    $status_opt .='<option value="New" title="New">New</option>
                <option value="In Review" title="In Review">In Review</option>
                <option value="Modified" title="Modified">Modified</option>' .
            /* <option value="send_to_customer" title="Send To Customer">Send To Customer</option> */
            '<option value="missing_items" title="Missing Items">Missing Items</option>
                <option value="completed" title="Completed">Completed</option>
                <option value="cancelled" title="Cancelled">Cancelled</option>';
    if (isset($_GET['view'])) {
        if ($_GET['view'] == 'pending')
            $type = 'New';
        elseif ($_GET['view'] == 'published')
            $type = 'completed';
        elseif ($_GET['view'] == 'approved')
            $type = '';
        else
            $type = '';
    } else {
        $type = '';
    }
    wp_enqueue_script('application_tb12_script', plugin_dir_url(__FILE__) . 'js/certificateTables.js', array('datatable-bootstrap'));


    wp_localize_script('application_tb12_script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'uesr_role' => $role, 'opt' => $status_opt, 'type' => $type));
    include('certificate_listing.php');
}

function certificate_callback() {
    global $current_user, $wpdb;
    isset($_POST['length']) ? $lenght = $_POST['length'] : $lenght = 100;
    isset($_POST['start']) ? $start = $_POST['start'] : $start = 0;
    $where = array();
    if (isset($_POST['search']['value']) and ! empty($_POST['search']['value'])) {
        $search = $_POST['search']['value'];
		if (strtolower($_POST['role']) == 'customer') {
           $where[] = "certificate_name like '%$search%' and status in ('In Review', 'Completed', 'Sent to Customer', 'Missing Items', 'Cancelled', 'Modified') ";
        } else {
			$where[] = "certificate_name like '%$search%' and status Not in ('Draft','New') ";

        }

    }

    if (isset($_POST['columns'][4]['search']['value']) and ! empty($_POST['columns'][4]['search']['value'])) {
        $search_program = $_POST['columns'][4]['search']['value'];
        $program_id = $wpdb->get_var("SELECT id FROM " . $wpdb->prefix . "programs where name='" . $search_program . "'");
        if ($search_program != '') {
            $where[] = " (wp_application_data.program_id =" . $program_id . ") ";
            if (strtolower($_POST['role']) == 'staff') {
                $where[] = '(status != "Draft")';
            }
        }
    }

    if (!empty($where)) {
        $where = ' WHERE ' . implode('AND', $where);
    } else {
        if (strtolower($_POST['role']) == 'customer') {
            $where = 'WHERE status in ("In Review", "Completed", "Sent to Customer", "Missing Items", "Cancelled", "Modified") ';
        } else {
            $where = 'WHERE status Not in ("Draft","New") ';
        }
    }
    $role = strtolower($_POST['role']);
    $customer__join = '';
    // Get logged in user company
    $user_company = $wpdb->get_row("SELECT company_id from " . $wpdb->prefix . "users where ID =" . $current_user->ID);
    $user_company = isset($user_company->company_id) ? $user_company->company_id : 0;
    $where .=' AND ('. $wpdb->prefix .'application_data.certificate_crm_id IS NOT NULL AND '. $wpdb->prefix .'application_data.certificate_crm_id != "") ';
    if ((strtolower($role) != 'staff') && (strtolower($role) != 'assessor')) {
        //$customer__join = ' Inner Join ' . $wpdb->prefix . 'users as u on u.company_id =  `' . $wpdb->prefix . 'application_data`.company_id and u.ID =' . $current_user->ID;
        //$customer__join = 'AND wp_application_data.user_id='.$current_user->ID;
        $where .='AND wp_application_data.company_id=' . $user_company;
    }
    // Filter application data if staff login
    $program__join = '';
    if (strtolower($role) == 'staff') {
        $program__join = ' Inner join ' . $wpdb->prefix . 'program_user_association as pua on `' . $wpdb->prefix . 'application_data`.program_id = pua.program_id ';
        $where .='AND pua.user_id =' . $current_user->ID;
        //$where .= " GROUP BY pua.program_id";
    }
    $renewal_join = ' LEFT JOIN ' . $wpdb->prefix . 'application_renewal_notification as arn on (arn.application_id = ' . $wpdb->prefix . 'application_data.id)';
    $renewal_fields = ", arn.staff_last_notified_on, arn.customer_last_notified_on, arn.is_renewed ";
   //echo "SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . $where . " ORDER BY wp_application_data.id DESC LIMIT $start,$lenght ";die;
   // echo "SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . $where . " ORDER BY wp_application_data.id DESC LIMIT $start,$lenght ";
   // die;

    $results = $wpdb->get_results("SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . $where . " GROUP BY " . $wpdb->prefix . "application_data.certificate_crm_id ORDER BY wp_application_data.id DESC LIMIT $start,$lenght ");

    //echo "SELECT " . $wpdb->prefix . "application_data.* " . $renewal_fields . " FROM " . $wpdb->prefix . "application_data " . $program__join . " " . $customer__join . " " . $renewal_join . " " . $where . " ORDER BY wp_application_data.id DESC LIMIT $start,$lenght ";

    $return = array();
//     $count_query = "SELECT count(".$wpdb->prefix."application_data.id) FROM ".$wpdb->prefix."application_data ".$program__join." ".$customer__join." ".$renewal_join." ".$where." GROUP BY " . $wpdb->prefix . "application_data.certificate_crm_id";
//     $return["recordsTotal"] = $return["recordsFiltered"] = $wpdb->get_var($count_query); //count($results);
    $return["recordsTotal"] = $return["recordsFiltered"] = $wpdb->num_rows; //count($results);
    $return["data"] = array();
    $status = array('new_application' => 'New', 'modified' => 'Modified',
        'approved' => 'In Reveiw', 'send_to_customer' => 'Send To Customer',
        'missing_item' => 'Missing Item', 'cancelled' => 'Cancelled',
        'completed' => 'Completed', 'draft' => 'Draft');
    foreach ($results as $result):
        $appData = json_decode($result->application_data, true);
        $renewal_data = json_decode($result->renewal_options);
        $company_name = '';
        //$country = get_name('country', $appData['new_application']['_linked']['new_country']['new_countryid'], 'country');
        //$state = get_name('state', $appData['new_application']['_linked']['new_state']['new_stateid'], 'state');
        if (isset($result->company_id)) {
            $company_name = get_name('company', $result->company_id, 'name');
            $country = get_name('company', $result->company_id, 'country');
            $country = get_name('country', $country, 'country');
            $state = get_name('company', $result->company_id, 'state');
            $state = get_name('state', $state, 'state');
            $city = get_name('company', $result->company_id, 'city');
        }
        // $program_name = $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "programs where id=" . $result->program_id);
        $exp = ($result->application_exp_date != '') ? date('d/m/Y', strtotime($result->application_exp_date)) : '';
        $opr = '<ul>';
        $third_party_array = array(2, 7, 10);
        $result->status = strtolower($result->status);
        $json = json_decode($result->application_data);
        if (strtolower($_POST['role']) == 'staff') {
            /* $opr .= '<a href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=false"  title="View Application"></a>&nbsp;&nbsp;';
              $opr .= '<a href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;'; */
            if (strtolower($result->status) == 'new' || strtolower($result->status) == 'modified' || strtolower($result->status) == 'draft') {
                $request_url = get_admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($result->id) . '&is_ajax=true';
                $redirect_url = site_url() . '/index.php/listings?page=application-form-register&view=approved';
                if ($result->status == 'modified') {
                    $opr .= '<li><a  title="View Difference From Last Update" href="#app_diff" class="colorbox-inline" onclick="getAppDiff(\'' . $result->id . '\',\'app\', \'' . $company_name . '\', \'' . get_admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($result->id) . '\', \'' . $result->certificate_name . '\', \'' . $redirect_url . '\' );getAppDocDiff(\'' . $result->id . '\', \'doc\', \'' . $company_name . '\')" ><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-random"></span></li></a>';
                }
                if (!empty($result->certificate_name) && !in_array($result->program_id, $third_party_array)) {
                    $opr .= '<li><a href="#" onclick="application_push_to_crm(\'' . $request_url . '\', \'' . $redirect_url . '\')" class="approveLink" value=""  title="Push To CRM"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-okglyphicon glyphicon-ok"></span></li> </a>';
                } else {
                    $certificate_name = (isset($result->certificate_name) && ($result->certificate_name != '')) ? $result->certificate_name : '';
                    $new_inspectionagencycert = (isset($json->new_application->_linked->new_certificate->new_inspectionagencycert)) ? $json->new_application->_linked->new_certificate->new_inspectionagencycert : '';
                    $opr .= '<li><a href="#app_certification_popup" id="approve-link-' . $result->id . '" onclick="return openCertificatePopUp(\'' . $result->id . '\',\'' . $result->program_id . '\',\'' . $new_inspectionagencycert . '\',\'' . $certificate_name . '\')" data-href="' . $request_url . '" redirect-href="' . $redirect_url . '" class="colorbox-inline" value=""  title="Push To CRM"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-ok"></span></li></a>';
                }
            }
            if (empty($result->certificate_url)) {
            	if(strtolower($result->status) != 'completed'){
                $opr .= '<li><a  class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
            	}
                if (strtolower($result->status) == 'new' || strtolower($result->status) == 'modified' || strtolower($result->status) == 'draft') {
                    $opr .= '<li><a  title="Edit Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=false"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></li></a>';
                }
            }
        } else if ((user_can($current_user, "customer") || is_user_company_admin()) && (empty($result->certificate_url))) {
        	if(strtolower($result->status) != 'completed'){
            $opr .= '<li><a  title="View Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
        	}
            if (($result->new_application_id == 0) && ((strtolower($result->status) == 'new' || strtolower($result->status) == 'modified' || strtolower($result->status) == 'draft'))) {
                $opr .= '<li><a  title="Edit Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></li></a>';
            }
        } else if (user_can($current_user, "assessor") && (empty($result->certificate_url))) {
            $opr .= '<li><a  title="View Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($result->id) . '&view=true"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
        }
        //echo $certificate_status = isset($result->certificate_status) ? $result->certificate_status : 'Not Available';die;
        if (is_user_company_admin() && (empty($result->crm_id))) {
            $opr .= '<li><a title="Delete Application" class="icon-3" href="' . get_admin_url() . 'admin-post.php?action=delete-application-form&id=' . base64_encode($result->id) . '" onclick="return confirm(\'Are you sure you want to delete this application ?\');"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></li></a>';
        }

        $renew = '';
        if (((int) $result->new_application_id == 0) || ($result->is_renewed < 1)) {
            $button_text = "Renew";
            if (($role == 'staff') && ($result->staff_last_notified_on != '')) {
                $button_text = "Submit Renewal Fee";
                if (!empty($renewal_data)) {
                    $button_text = "Edit Renewal Fee";
                }
                $renew = '<a title="' . $button_text . '" class="btn btn-xs btn-success margin-zero-auto" style="float:left;margin-left:5px !important;" href="' . site_url() . '/index.php/listings/?page=payment-settings&app_id=' . $result->id . '&certificate_id=' . $result->certificate_crm_id . '">' . $button_text . '</a>';
            } else if (($role == 'customer') && ($result->customer_last_notified_on != '') && (!empty($renewal_data))) {
                $renew = '<a title="' . $button_text . '" class="btn btn-xs btn-success margin-zero-auto" style="float:left;margin-left:5px !important;" href = "' . admin_url() . 'admin-post.php?action=renew_application&appid=' . base64_encode($result->id) . '" title="Renew">' . $button_text . '</a>';
            } else if (($role == 'customer') && ($result->customer_last_notified_on != '') && (empty($renewal_data)) && ($result->status != 'Completed')) {
                $renew = '<a href="#check_renewal_popup" class="btn btn-xs btn-success margin-zero-auto colorbox-inline-payment" style="float:left;margin-left:5px !important;" title="Renew">Renew</a>';

            }

        }
		$sessionforstandar = (isset($_SESSION['standard'.$result->id])?$_SESSION['standard'.$result->id]:null);
        $certificate_name = isset($result->certificate_name) ? $result->certificate_name : 'Not Available';
        $certificate_status = isset($result->certificate_status) ? $result->certificate_status : 'Not Available';
        $certificate_url = (isset($result->certificate_url) && (!empty($result->certificate_url)) && $certificate_name != 'Not Available') ? '<div style="position:relative;"><a class="pull-left width-100 certificate_url" target="_blank" style="line-height: 25px;" download="" href="https://' . $result->certificate_url . '">' . $certificate_name . '<span class="btn-wizard-download-tbl">&nbsp;</span></a></div>' : 'Not Available';        $application_name = isset($result->application_name) ? $result->application_name : 'Not Available';
         if(user_can($current_user, "customer") && (isset($result->certificate_status)) && ($result->certificate_status == 'Accredited'))
         {
        $renew .= '<a href="#scope_ext" class="btn btn-xs btn-success margin-zero-auto colorbox-inline cboxElement" style="float:left;margin-left:5px !important;" onclick="scope_ext('.$result->id.',&quot;'.$sessionforstandar.'&quot;)" title="Scope">Scope</a>';
        }
        $opr.='</ul>';
        $return["data"][] = array(
            '', $certificate_name, $certificate_url,
            $exp,$certificate_status,$renew,
            'id' => $result->id,'certificate_crm_id' => $result->certificate_crm_id
        );

    endforeach;
    echo json_encode($return);
    die;
}


add_action('wp_ajax_get_application_by_certificate_crm_id', 'get_application_by_certificate_crm_id');
        add_action('wp_ajax_nopriv_get_application_by_certificate_crm_id', 'get_application_by_certificate_crm_id');



function get_application_by_certificate_crm_id() {
    global $current_user,$wpdb;
    $result = array();
	$third_party_array = array(2,7,10);
	if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
        $crm_id = $_REQUEST['id'];

        $app_data = $wpdb->get_results("SELECT ad.id as app_id,ad.application_name,ad.certificate_status,ad.certificate_url,ad.status,ad.certificate_name,ad.new_application_id,ad.program_id,p.name as program_name,c.name as company, ad.crm_id from " . $wpdb->prefix . "application_data as ad left join " . $wpdb->prefix . "programs as p on p.id = ad.program_id left join " . $wpdb->prefix . "company as c on c.id = ad.company_id and c.id=ad.company_id  where certificate_crm_id ='".$crm_id."'");
       //echo "SELECT ad.id as app_id,ad.application_name,p.name as program_name,c.name from " . $wpdb->prefix . "application_data as ad join " . $wpdb->prefix . "programs as p join " . $wpdb->prefix . "company as c  on p.id = ad.program_id and c.id=ad.company_id  where certificate_crm_id ='".$crm_id."'";die;

		if (count($app_data) > 0) {
            foreach ($app_data as $ad) {
				$opr='';
                $temp = array();
                $temp['app_id'] = $ad->app_id;
                $temp['app_name'] = $ad->application_name;
                $temp['program_name'] = $ad->program_name;
				$temp['company'] = $ad->company;
                $temp['status'] = $ad->status;

			if (user_can($current_user, "staff")) {
            //$opr .= '<a href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($ad->app_id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></a>';

			if (strtolower($ad->status) == 'new' || strtolower($ad->status) == 'modified' || strtolower($ad->status) == 'draft') {
                $request_url = get_admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($ad->app_id) . '&is_ajax=true';
                $redirect_url = site_url() . '/index.php/listings?page=application-form-register&view=approved';
                if ($ad->status == 'modified') {
                    $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a  title="View Difference From Last Update" href="#app_diff" class="colorbox-inline" onclick="getAppDiff(\'' . $ad->app_id. '\',\'app\', \'' . $ad->company . '\', \'' . get_admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($ad->app_id) . '\', \'' . $ad->certificate_name . '\', \'' . $redirect_url . '\' );getAppDocDiff(\'' . $ad->app_id . '\', \'doc\', \'' . $ad->company . '\')" ><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-random"></span></li></a>';
                }
                if (!empty($ad->certificate_name) && !in_array($ad->program_id, $third_party_array)) {
                    $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a href="#" onclick="application_push_to_crm(\'' . $request_url . '\', \'' . $redirect_url . '\')" class="approveLink" value=""  title="Push To CRM"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-okglyphicon glyphicon-ok"></span></li></a>';
                } else {
                    $certificate_name = (isset($ad->certificate_name) && ($ad->certificate_name != '')) ? $ad->certificate_name : '';
                    $new_inspectionagencycert = (isset($json->new_application->_linked->new_certificate->new_inspectionagencycert)) ? $json->new_application->_linked->new_certificate->new_inspectionagencycert : '';
                    $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a href="#app_certification_popup" id="approve-link-' . $ad->app_id . '" onclick="return openCertificatePopUp(\'' . $ad->app_id . '\',\'' . $ad->program_id . '\',\'' . $new_inspectionagencycert . '\',\'' . $ad->certificate_name . '\')" data-href="' . $request_url . '" redirect-href="' . $redirect_url . '" class="colorbox-inline" value=""  title="Push To CRM"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-ok"></span></li></a>';
                }
            }
             if (empty($ad->certificate_url)) {
             	if(strtolower($ad->status) != 'completed'){
                $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a  class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($ad->app_id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
             	}
                if (strtolower($ad->status) == 'new' || strtolower($ad->status) == 'modified' || strtolower($ad->status) == 'draft') {
                    $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a  title="Edit Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($ad->app_id) . '&view=false"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></li></a>';
                }
            }
        } else if ((user_can($current_user, "customer") || is_user_company_admin()) && (empty($ad->certificate_url))) {
        	if(strtolower($ad->status) != 'completed'){
            $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a  class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($ad->app_id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
        	}
            if ($ad->new_application_id == 0 && strtolower($ad->status) != 'completed') {
                $opr .= '<li  style="float: left;list-style-type: none; margin-right: 5px;"><a  title="Edit Application" class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($ad->app_id) . '&view=false"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></li></a>';
            }
        } else if (user_can($current_user, "assessor") && (empty($ad->certificate_url)) && strtolower($ad->status) != 'completed') {
            $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a  class="icon-3" href="' . site_url() . '/index.php/listings/?page=create-form-register&id=' . base64_encode($ad->app_id) . '&view=true" title="View Application"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></li></a>';
        }
        if (is_user_company_admin() && (empty($ad->crm_id)) && strtolower($ad->status) != 'completed') {
            $opr .= '<li style="float: left;list-style-type: none; margin-right: 5px;"><a title="Delete Application" class="icon-3" href="' . get_admin_url() . 'admin-post.php?action=delete-application-form&id=' . base64_encode($ad->app_id) . '" onclick="return confirm(\'Are you sure you want to delete this application ?\');"><span style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-trash">'.$ad->crm_id.'</span></li></a>';
        }

				$temp['act']=$opr;
                $result[$ad->app_id] = $temp;

            }
        }
    }
    echo json_encode($result);
    die;
}

function wpse_183245_upload_dir( $dirs ) {
    $dirs['subdir'] = '/scope_ext_docs/certificates';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];
    return $dirs;
}
function upload_dir_for_invoice( $dirs ) {
    $dirs['subdir'] = '/upload_invoice';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];
    return $dirs;
}
add_action('wp_ajax_file_upload_for_scope_ext', 'file_upload_for_scope_ext');
function file_upload_for_scope_ext(){
    global $wpdb;
    session_start();
    remove_filter('upload_dir', 'mgmt_third_party');
    if(isset($_POST['page']) && $_POST['page'] == 'all-invoices'){
        remove_filter('upload_dir', 'wpse_183245_upload_dir');
        add_filter( 'upload_dir', 'upload_dir_for_invoice' );
    }else{
    add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    }
    if($_FILES){
        foreach ($_FILES as $file) {
            $rfile = $file['name'];
            $ext = substr($rfile, strpos($rfile, '.'));
            // $file['name'] = date('Y-m-d-').uniqid().$ext;
            $upload = wp_handle_upload($file, array('test_form' => false));
            if (!isset($upload['error']) && isset($upload['file'])) {
                //uploading file in upload folder
                // $wpdb->insert($wpdb->prefix . 'invoices', array('crm_id' => $_POST['crm_id'], 'certificate_id' => $_POST['certificate_id'], 'invoice_docs' => $upload['file'], 'created_on' => current_time('mysql', 1));
                // $wpdb->insert($wpdb->prefix . 'invoices', array('crm_id' => '2', 'certificate_id' => '1', 'invoice_docs' => $upload['url'], 'created_on' => current_time('mysql', 1)));
                $filetype = wp_check_filetype(basename($rfile), null);
                $title = $file['name'];
                $ext = strrchr($title, '.');
                $title = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
                $attachment = array(
                    'url'=>$upload['url'],
                    'filename'=>$rfile,
                    // 'fn'=>$file['name'],
                    'ext'=>$ext

                );
                //uploading file in upload folder
            }

        }
    }
if(isset($_POST['page']) && $_POST['page'] == 'all-invoices'){
    remove_filter('upload_dir', 'upload_dir_for_invoice');
    $app_id = isset($_POST['invoice_id'])?$_POST['invoice_id']:null;
}else {
   remove_filter('upload_dir', 'wpse_183245_upload_dir');
    $app_id = isset($_POST['scope_app_id'])?$_POST['scope_app_id']:null;
    }

    $res_jsn = '';
    $res_jsn = (isset($_SESSION['SE'.$app_id])?$_SESSION['SE'.$app_id]:json_encode(array()));
    $res_jsn = json_decode($res_jsn, true);
    if(isset($attachment['filename']) && (!empty($attachment['filename']))){
    $res_jsn['files'][$attachment['filename']] = $attachment;
    }
    echo $_SESSION['SE'.$app_id] = json_encode($res_jsn);die;

    }
add_action('wp_ajax_remove_scope_documents', 'remove_scope_documents');
function new_filename($filename, $filename_raw) {
    global $post;
    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    return $filename_raw;
}
add_filter('sanitize_file_name', 'new_filename', '10','2');
function remove_scope_documents(){
session_start();
     if(isset($_POST['page']) && $_POST['page'] == 'all-invoices'){
        remove_filter('upload_dir', 'wpse_183245_upload_dir');
    add_filter( 'upload_dir', 'upload_dir_for_invoice' );
    }else {
    add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    }
    $dirs = wp_upload_dir();
     if(isset($_POST['page']) && $_POST['page'] == 'all-invoices'){
    remove_filter( 'upload_dir', 'upload_dir_for_invoice' );
    $app_id = isset($_POST['invoice_id'])?$_POST['invoice_id']:null;
    }else {
    remove_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    $app_id = isset($_POST['scope_app_id'])?$_POST['scope_app_id']:null;
    }
    $base_dir = $dirs['path'] . '/';
    if (file_exists($base_dir.$_POST['file_name'])) {
        @unlink($base_dir.$_POST['file_name']);
       $result = array("result" => "success");
    } else {
       $result = array("result" => "error", "message" => "File not found");
    }

    $res_jsn = (isset($_SESSION['SE'.$app_id])?$_SESSION['SE'.$app_id]:json_encode(array()));

    $res_jsn = json_decode($res_jsn,true);

    unset($res_jsn['files'][$_POST['file_name']]);

    $res_jsn = array_merge($res_jsn, $result);

    if(isset($_POST['page']) && $_POST['page'] == 'all-invoices'){
    global $wpdb;
    $app_id = isset($_POST['invoice_id'])?$_POST['invoice_id']:null;
    $invoice_docs = $wpdb->get_var('select invoice_docs from ' . $wpdb->prefix . 'invoices where crm_id ="'.$app_id.'"');
    $invoice_docs = unserialize($invoice_docs);
    if(isset($invoice_docs) && (!empty($invoice_docs))){
    $invoicedocurl = (isset($_POST['invoicedocurl']))?$_POST['invoicedocurl']:'';
    if (in_array($invoicedocurl, $invoice_docs)){
    $key = array_search ($invoicedocurl, $invoice_docs);
    unset($invoice_docs[$key]);
   }
   if(empty($invoice_docs)){
        $invoice_docs = '';
   }else{
    $invoice_docs = serialize($invoice_docs);
   }
    $wpdb->update($wpdb->prefix . 'invoices', array('invoice_docs' => $invoice_docs), array('crm_id' => $_POST['invoice_id']));
    }
    }
    $_SESSION['SE'.$app_id] = json_encode($res_jsn);
    echo $_SESSION['SE'.$app_id];
   die;
}

add_action('wp_ajax_get_uploaded_scope_documents', 'get_uploaded_scope_documents');
function get_uploaded_scope_documents(){
    session_start();
    $files_data = '';
    $app_id = (isset($_POST['invoice_id']) && (!empty($_POST['invoice_id']))) ? $_POST['invoice_id']: "";
    if (isset($_POST['scope_app_id']) && (!empty($_POST['scope_app_id']))) {
        $app_id = $_POST['scope_app_id'];
        if(isset($_SESSION['SE'.$app_id]) && (!empty($_SESSION['SE'.$app_id]))) {
            $files_data = $_SESSION['SE'.$app_id];
        }
    } else if(isset($_SESSION['SE'.$app_id]) && (!empty($_SESSION['SE'.$app_id]))) {
        if (isset($_POST['invoice_id']) && (!empty($_POST['invoice_id']))) {
            $app_id = $_POST['invoice_id'];
            if(isset($_SESSION['SE'.$app_id]) && (!empty($_SESSION['SE'.$app_id]))) {
                $files_data = $_SESSION['SE'.$app_id];
            }
        }
    } else {
        get_uploaded_scope_documents_from_db($app_id,$check_for_all_invoices='');
    }
    echo $files_data;
    die;
}
function get_uploaded_scope_documents_from_db($app_id,$check_for_all_invoices){
    global $wpdb;
        $files = array();
        $files1 = array();
        $sql_data = 'select * from ' . $wpdb->prefix . 'invoices where crm_id ="'.$app_id.'"';
        $result = $wpdb->get_results($sql_data);
        if(isset($result) && (!empty($result))) {
            foreach($result as $key => $invoice) {
                $invoice_docs = unserialize($invoice->invoice_docs);
                if(isset($invoice_docs) && (!empty($invoice_docs))) {
                    foreach($invoice_docs as $doc) {
                        $file_info = pathinfo($doc);
                        if(isset($file_info) && (!empty($file_info))) {
                            $attachment = array(
                                'url'=>$doc,
                                'filename'=>$file_info['basename'],
                                // 'fn'=>$file_info['basename'],
                                'ext'=>".".$file_info['extension']
                            );
                            $files[$file_info['basename']] = $attachment;
                            $files1[] = $attachment;
                        }
                    }
                }
            }
        }
        $res_jsn = array("files" => $files);
        $_SESSION['SE'.$app_id] = json_encode($res_jsn);
        $res_jsn['result'] = "success";
        if($check_for_all_invoices == 'true'){
            return $files1;
        }else{
        echo json_encode($res_jsn);
        exit(0);
        }
}

add_action('wp_ajax_session_for_standards', 'session_for_standards');
function session_for_standards()
{
$files_data = '';

    if (isset($_POST['value']) && ($_POST['appid'])) {
        $app_id = $_POST['appid'];
        $_SESSION['standard'.$app_id] =$_POST['value'];
        echo $_SESSION['standard'.$app_id];die;

    }

}
add_action('wp_ajax_get_session_for_standards', 'get_session_for_standards');
function get_session_for_standards()
{
$files_data = '';

    if (isset($_POST['appid'])) {
        $app_id = $_POST['appid'];
        $_SESSION['standard'.$app_id] = isset($_SESSION['standard'.$app_id])?$_SESSION['standard'.$app_id]:'';
        echo $_SESSION['standard'.$app_id];die;
    }

}
function myEndSession() {
    session_destroy ();
}
add_action('wp_logout', 'myEndSession');

add_shortcode('all_invoices','all_invoice_for_staff');
function all_invoice_for_staff(){
echo do_shortcode('[my-invoices-list]');
}

/*function to allow et type extension on upload*/
function my_myme_types($mime_types){
    $mime_types['et'] = 'text/x-setext'; //Adding et extension
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);

function update_billing_address() {
	global $wpdb;
	if(isset($_POST['application_id']) && ($_POST['application_id'] > 0)) {
		$data = $billing_address_details = get_billing_address_by_app_id($_POST['application_id']);
		echo json_encode($data);
		exit(0);
	}
}
add_action('wp_ajax_update_billing_address', 'update_billing_address');
