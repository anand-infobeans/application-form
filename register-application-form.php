<?php
global $wpdb;
global $current_user; // Use global
get_currentuserinfo(); // Make sure global is set, if not set it.
$company_id = $current_user->company_id;
$company_name = $current_user->company_name;
//In case of generating application from quotation then show payment tab for showing quotation amount in application tabs
$show_payment_tab = (isset($_REQUEST['show_payment_tab']) && !empty($_REQUEST['show_payment_tab'])) ? $_REQUEST['show_payment_tab'] : 0;
$new_application_id = 0;

if (isset($_GET['appid'])) {
    $appid = base64_decode($_GET['appid']);
    $CrmOperationsobj = new CrmOperations();
    $resultsfromcrm = $CrmOperationsobj->getCrmEntityDetails('new_application', array('type' => 'and', 'conditions' => array(array('attribute' => 'new_applicationid', 'operator' => 'eq', 'value' => $appid))), 'list');
//echo "<pre>";print_r($resultsfromcrm);die;
}
if(!isset($appid) && isset($_GET['id']))
{
    $appid = base64_decode($_GET['id']);
}
$status = '';
if (isset($_GET['id']) || isset($_GET['crmid'])) {

    if (user_can($current_user, "staff") || user_can($current_user, "assessor")) {
        $sql = 'select * from ' . $wpdb->prefix . 'application_data where id='.base64_decode($_GET['id'])." AND status!='Draft'";
    } else if(user_can($current_user, "contact")){
        $sql = 'select * from ' . $wpdb->prefix . 'application_data left join ' . $wpdb->prefix . 'application_user_roles on ' . $wpdb->prefix . 'application_user_roles.application_id='.$wpdb->prefix.'application_data.id where ' . $wpdb->prefix . 'application_data.id=' . base64_decode($_GET['id']);
    }else
    {
        $sql = 'select * from ' . $wpdb->prefix . 'application_data where id=' . base64_decode($_GET['id']) . " AND user_id=" . get_current_user_id();
    }
    $result = $wpdb->get_results($sql);
    $wpdb->query($sql);
    if ($wpdb->num_rows == 0) {
        echo '<script>window.location.href="' . site_url() . '/index.php/listings/?page=application-form-register&view=all"</script>';
    }
    foreach ($result as $val)
    $application_data = $val->application_data;
    $application_data = json_decode($application_data);
    $crm_id = $val->crm_id;
    $program_id = $val->program_id;
    $status = $val->status;
    $app_id = $val->id;
    $created_by = $val->created_by;
    $certificate_name = $val->certificate_name;
	$app_name = (!empty($val->application_name)) ? $val->application_name : "Not Available" ;
	//$is_renewal =  $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "application_renewal_notification WHERE application_id=".$app_id);
	$is_renewal = 0;
    $renewed_application_sql = "select id, renewal_options from " . $wpdb->prefix . "application_data where new_application_id=" . base64_decode($_GET['id']);
    $renewed_application_result = $wpdb->get_results($renewed_application_sql);
    //In case of application is renewed application then show renewal charges which are set by staff
    if(isset($renewed_application_result) && (!empty($renewed_application_result))) {
    	$new_application_id = base64_decode($_GET['id']);
    	$show_payment_tab = 1;
		$is_renewal  = 1;
    }
 
 	// do not show payment tab if application is not pushed to CRM or application GP number is not generated
    if(((empty($val->gp_number)) || (strtolower($val->gp_number)=='null')))   {
    	$show_payment_tab = 0;
        echo '<script>payment_detail=0</script>';
    } else {
    	//In case of application having linked quotation then to show quotation amount, show payment tab in application
		$show_payment_tab = ((isset($val->quotation_id)) && ($val->quotation_id > 0)) ? 1 : $show_payment_tab;
        echo '<script>payment_detail=1</script>';
    }

    $redirect_url = admin_url() . 'admin-post.php?action=approve-application-form&id=' . base64_encode($val->id) . '&is_ajax=true';
    $app_redirect_url = site_url() . '/index.php/listings?page=application-form-register&view=approved';
    echo "<script>var redirecturl='" . $redirect_url . "';";
    echo "var app_redirect_url='" . $app_redirect_url . "';";
    echo "jQuery('#popup_app_id').val('$val->id');</script>";
    echo "<script>var appid='" . $crm_id . "'</script>";
} else {
    echo "<script>var appid=''</script>";
}
echo "<script>var new_application_id='" . $new_application_id . "'</script>";
$user_sql = 'select * from  ' . $wpdb->prefix . 'users where created_by=' . get_current_user_id();
$user_result = $wpdb->get_results($user_sql);

$country_result = get_countries_list();

$state_sql = 'select * from ' . $wpdb->prefix . 'state ';
$state_result = $wpdb->get_results($state_sql);

if (user_can($current_user, "staff")) {
    $company_sql = 'select * from ' . $wpdb->prefix . 'company WHERE name!="" ORDER BY name asc';
} else {
    $company_sql = 'select * from ' . $wpdb->prefix . 'company WHERE name!="" AND id=' . $company_id . ' ORDER BY name asc';
}

$company_result = $wpdb->get_results($company_sql);

$applications_sql = 'select * from ' . $wpdb->prefix . 'programs ';
$applications_result = $wpdb->get_results($applications_sql);

$sql_templates = 'SELECT * FROM ' . $wpdb->prefix . 'application_templates
   JOIN ' . $wpdb->prefix . 'templates ON ' . $wpdb->prefix . 'application_templates.template_id =' . $wpdb->prefix . 'templates.id
   INNER JOIN ' . $wpdb->prefix . 'tab_slugs on ' . $wpdb->prefix . 'tab_slugs.tab_slug = ' . $wpdb->prefix . 'application_templates.tab_slug
   where ';

if (isset($_REQUEST['program_id']) && $_REQUEST['program_id'] != "") {
    $sql_templates .= $wpdb->prefix . 'application_templates.program_id=' . $_REQUEST['program_id'];
} else {
    if (isset($program_id)) {
        $sql_templates .= $wpdb->prefix . 'application_templates.program_id=' . $program_id;
    } else {
        $sql_templates .= $wpdb->prefix . 'application_templates.program_id=1';
    }
}

$sql_templates .= ' AND template_render_order!=0 order by ' . $wpdb->prefix . 'tab_slugs.tab_order,template_render_order asc';

$template_result = $wpdb->get_results($sql_templates);

if (isset($program_id)) {
    $current_program_id = $program_id;
} else if (isset($_GET['program_id'])) {
    $current_program_id = $_GET['program_id'];
}

$current_role = get_user_meta(get_current_user_id(), "wp_capabilities");

//check applied for this quotation or not
if (isset($_REQUEST["id"]) && $_REQUEST["id"]) {
    $sql = "SELECT quotation_id FROM " . $wpdb->prefix . "application_data WHERE id =" . base64_decode($_REQUEST["id"]);
    $result = $wpdb->get_row($sql);

    //Get company details in the quotation
	if(!empty($result) && (!empty($result->quotation_id))){
		$sql = "SELECT wqa.id,wqa.company_id,wqa.amount,wqsd.post_id FROM " . $wpdb->prefix . "quotation_program wqa," . $wpdb->prefix . "quotation_scope_data wqsd , " . $wpdb->prefix . "terms wt WHERE wqa.id = wqsd.quotation_id AND wt.term_id = wqsd.category_term_id AND wqa.id=" . $result->quotation_id . " AND wt.slug = 'staff-quotation'";
		$quotation = $wpdb->get_row($sql);
	}
} else {
    if (isset($_GET["quotation_id"]) && $_GET["quotation_id"]) {
        //check applied for this quotation or not
        $sql = "SELECT id,quotation_id FROM " . $wpdb->prefix . "application_data WHERE quotation_id =" . $_GET["quotation_id"];
        $result = $wpdb->get_row($sql);

        if($result) { $application_data_id = base64_encode($result->id); }
        if (count($result))
            header("Location:" . get_permalink(get_page_by_path('listings')) . '?page=create-form-register&id=' . $application_data_id);

        //Get company details in the quotation

        $sql = "SELECT wqa.id,wqa.company_id,wqa.amount,wqsd.post_id FROM " . $wpdb->prefix . "quotation_program wqa," . $wpdb->prefix . "quotation_scope_data wqsd , " . $wpdb->prefix . "terms wt WHERE wqa.id = wqsd.quotation_id AND wt.term_id = wqsd.category_term_id AND wqa.id=" . $_GET["quotation_id"] . " AND wt.slug = 'staff-quotation'";
        $quotation = $wpdb->get_row($sql);
    }
}

add_thickbox();
?>
<style>label{margin: 10px}</style>
<div class="container inner-main-container ">
    <div class="col-md-12 padding-left-0 padding-right-0 inner-main-heading">
        <div class="col-md-8 padding-left-0 padding-right-0">
            <h1 class="post-title">IAS Application Form</h1>
        </div>
        <div class="col-md-4 padding-left-0 padding-right-0"></div>
    </div>

    <div class="floating-line"></div>
    <div class="clearfix"></div>
    <input type="hidden" id="status" name="status" value="<?php echo $status; ?>">
	<input type="hidden" id="certificate_name" name="certificate_name" value="<?php echo (isset($certificate_name)) ? $certificate_name: ''; ?>">
    <div class="application-frm-main-heading" style="position: relative;">
        Application for
        <?php $program_name = 'program';
        foreach ($applications_result as $applications) {
            ?>
            <?php
            if (isset($_GET['program_id']) && $_GET['program_id'] == $applications->id) {
                echo $applications->name;
                $program_name = $applications->name;
            } else if (isset($program_id) && $program_id == $applications->id) {
                echo $applications->name;
                $program_name = $applications->name;
            }
            ?>
        <?php }?>
        <!--Show download link start-->
        <?php
        //$program_id = '';
        if (isset($_REQUEST['program_id']) && !empty($_REQUEST['program_id']) && is_numeric($_REQUEST['program_id'])) {
            $program_id = $_REQUEST['program_id'];
        }
        if (!empty($program_id)) {
            $sql_docs = "select " . $wpdb->prefix . "postmeta.meta_value," . $wpdb->prefix . "posts.post_title from " . $wpdb->prefix . "postmeta join " . $wpdb->prefix . "posts ON " . $wpdb->prefix . "posts.id=" . $wpdb->prefix . "postmeta.post_id and post_id IN (select doc_id from " . $wpdb->prefix . "program_docs where program_id = " . $program_id . ")"; //.$appl_selected;
            $result_prog_docs = $wpdb->get_results($sql_docs, ARRAY_A);
            if (is_array($result_prog_docs) && count($result_prog_docs) > 0) {
                $upload_dir = wp_upload_dir();
                ?>
                <div class="pull-right" style="position: absolute;top:5px;right: 10px;background    -color: #fff;height: 30px;">
                    <div>
                        <a href="javascript:void(0);"
                           class="btn-wizard-download"
                           id='download_associated_document'>
                        </a>
                    </div>

                    <div class="program_docs" style="display: none;position: absolute;top:22px;right: 150px;z-index: 1;background-color: #fff;">
                        <ul class='dropdown-menu' style="display: block;padding: 0;width:160px;">
                            <li><a href='<?php echo site_url() . '/download-zip.php?programId=' . $program_id . '&programName=' . $program_name ?>'>Download all files</a></li>
                            <?php foreach ($result_prog_docs as $key => $rdocs) { ?>
                                <li>
                                    <a title="<?php echo $rdocs['post_title']; ?>" style="white-space: nowrap;overflow:hidden !important;text-overflow: ellipsis;" href="<?php echo $upload_dir['baseurl'] . '/' . $rdocs['meta_value'] ?>" download>
                                    <?php echo $rdocs['post_title']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="clearfix"></div>
                <?php
            }
        }
        ?>
        <!--Show download link end-->

    </div>
	<iframe name="application_docs" id="application_docs" height="0" width="0"></iframe>
    <form class="formhide margin-top-0 margin-bottom-0" action="<?php echo get_admin_url(); ?>admin-post.php"  method="post" id="application-form1" name="frmapplication" target="application_docs" enctype="multipart/form-data">
	<input type="hidden" name="action" value="application-form">
	<input type="hidden" name="category_id" value="">
	<input type="hidden" name="appdoc_id" value="">
	<input type="hidden" name="applid" value="">
        <?php
        /* get all the template in ascending order */
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $currentMode = 'e'; //r|w|e
        } else {
            $currentMode = 'w'; //r|w|e
        }
        if (isset($_GET['view']) && $_GET['view']) {
            $currentMode = 'r'; //r|w|e
        }

        $finalarry = array();
        if (!empty($template_result)) {
            foreach ($template_result as $template) {
                $template->capabilities = unserialize(str_replace("`", '"', $template->capabilities));
                foreach ($current_role[0] as $key => $role) {

                    if (isset($template->capabilities[ucfirst($key)])) {
                        if (strpos($template->capabilities[ucfirst($key)], $currentMode) !== false) {
							if($template->slug == "payments"){
								wp_enqueue_script('common_payment_js',  WP_PLUGIN_URL.'/ib-my-invoices/js/common.js');
                            }
							ob_start();
                            include("templates/" . $template->slug . ".php");
                            $tab_slug = str_ireplace("-", " ", $template->tab_slug);
                            $myvar[$tab_slug] = (isset($myvar[$tab_slug]) ? $myvar[$tab_slug] : '');
                            $myvar[$tab_slug] .= ob_get_contents();
                            ob_end_clean();
                            break;
                        }
                    }
                }
            }
        }

        $i = 1;
        ?>
        <div id="wizard" class="tab-content-bdr-0">
            <?php
            if ((int) $show_payment_tab > 0) {
                echo "<script>var show_payment_tab=1;</script>";
            }else
            {
                echo "<script>var show_payment_tab=0;</script>";
            }
            if (!empty($myvar)) {
                foreach ($myvar as $key => $value) {
                    if(is_user_company_billing() && !is_user_company_admin()){ 
                        if(strtolower($key) == 'payments'){
                            if (strtolower($key) == 'payments') {
                                if ((int) $show_payment_tab > 0) {
                                    ?>
                                    <h2><?php echo $key; ?></h2>
                                    <section>
                                    <?php echo $myvar[$key]; ?>
                                    </section>
                                    <?php
                                }else
                                {?>
                                    <div class="col-lg-12" style="margin-top:10px;">
                                        <table class="payment-total renewal-payment-tbl wp-list-table widefat fixed pages table table-striped table-hover renew_payment" id="dataTable">
                                            <thead><tr><th style=" border-right: 1px solid #ddd; width: 28%; "><font color="green">Payment for this application is not generated yet</font></th></tr></thead>
                                        </table>
                                    </div>
                                <?php }
                            } else {
                                ?>
                                <h2><?php echo $key; ?></h2>
                                <section>
                                <?php echo $myvar[$key]; ?>
                                </section>
                                <?php
                            }
                        }
                    }else
                    { 
                        if (strtolower($key) == 'payments') {
                            if ((int) $show_payment_tab > 0) {
                                ?>
                                <h2><?php echo $key; ?></h2>
                                <section>
                                <?php echo $myvar[$key]; ?>
                                </section>
                                <?php
                            }
                        } else {
                            ?>
                            <h2><?php echo $key; ?></h2>
                            <section>
                            <?php echo $myvar[$key]; ?>
                            </section>
                            <?php
                        }
                    }
                }
            }
            ?>
        </div><!--wizard-->
        <input type="hidden" name="editid" class="editid" value="<?php
        if (isset($_GET['id'])) {
            echo base64_decode($_GET['id']);
        } else {
            echo "0";
        }
        ?>" />
        <input type="hidden" name="program_id" value="<?php
        if (isset($program_id)) {
            echo $program_id;
        } else if (isset($_GET['program_id'])) {
            echo $_GET['program_id'];
        } else {
            echo "1";
        }
        ?>" id='program_id'>
    </form>
</div>
<!--container-->
<style>th,td{padding: 0.8em;}</style>
<div style="display: none;">
    <div style="padding: 10px;" id="app_certification_popup">
        <h1 class="entry-title post-title">
            Add Certificate Name
        </h1>
        <div>
            <table class="table-striped table-hover">
                <tbody>
                    <tr>
                        <td>Certificate Name <span class="color-red">*</span></td>
                        <td>
                            <input type="text" value="<?php
                                   if (isset($certificate_name)) {
                                       echo $certificate_name;
                                   }
                                   ?>" class="required form-control pop-up-innput" id="popup_certificate_name" <?php
                                   if (isset($certificate_name)) {
                                       echo "readonly";
                                   }
                                   ?>>
                        </td>
                    </tr>
<?php if ($program_id == 7 || $program_id == 10 || $program_id == 2) { ?>
                        <tr>
                            <td>IAS Accreditation number of the third-party inspection agency <span class="color-red">*</span></td>
                            <td>
                                <input type="text" value="" class="required form-control pop-up-innput" id="popup_third_party_number">
                            </td>
                        </tr>
<?php } ?>
                    <tr>
                        <td align="right" colspan="2">
                            <a onclick="saveCertificatePopUpName()" class="btn btn-primary" href="javascript:;" id="submit-button">Submit</a>
                            <input type="hidden" id="popup_app_id" value="<?php echo $app_id; ?>">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style type="text/css">
    .input-readonly{
        border: none!important;
        background: none!important;
    }
    .select-box-readonly{
        background: none!important;
        border: none!important;
    }
    .remove-cursor{
        cursor: default;
    }
    .renew_payment>thead>tr>th{
        font-family: 'open_sanslight';
        font-weight: bold;
    }
    .renew_payment th label{
        font-weight: bold;
    }
</style>
<script type="text/javascript">
    /*var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

     elems.forEach(function(html) {
     var switchery = new Switchery(html);
     });*/
    function validate_form() {
        temp = 1;
        jQuery('.required').each(function ( ) {
            if (jQuery(this).val() == "") {
                jQuery(this).css('border', '1px solid red');
                temp = 0;
            } else
            {
                jQuery(this).css('border', '1px solid #fff');
            }
        });
        if (temp) {
            return true;
        } else
        {
            return false;
        }

    }

    jQuery(document).ready(function ()
    {
        var settings = {
            url: "admin-post.php?action=file-upload",
            method: "POST",
            allowedTypes: "jpg,png,gif,doc,pdf,zip",
            fileName: "myfile",
            multiple: true,
            onSuccess: function (files, data, xhr)
            {
                jQuery("#status").html("<font color='green'>Upload is success</font>");

            },
            onError: function (files, status, errMsg)
            {
                jQuery("#status").html("<font color='red'>Upload is Failed</font>");
            }
        }
        jQuery("#mulitplefileuploader").uploadFile(settings);

<?php if (isset($_GET['view']) && $_GET['view'] && $_GET['view'] == 'true') { ?>
            //if (jQuery('form').data('mode') == 'read') {   //remove fields and add text
            // Make input read only
            jQuery('.add-more').hide();
            jQuery('.nextbutton').hide();
            jQuery('.prevbutton').hide();
            jQuery('.fil-upload-btn').hide();
            // jQuery('.btn-wizard-download').hide();
            //console.log(jQuery('.ez-checkbox').length)
            jQuery('form').find(':input').each(function () {
                var input_type = jQuery(this).attr('type');
                if (input_type == 'checkbox') {
                    jQuery(this).css('opacity', '0');
                    //console.log(jQuery(".ez-checkbox").length)
                    //jQuery('.ez-checkbox').css("background-position: -2px -64px !important;");
                    // jQuery(".ez-checkbox").css('background','none');
                } else if (input_type == 'radio') {
                    jQuery(this).css('opacity', '0');
                }
                jQuery(this).css('padding', '4px');
                jQuery(this).attr('title', '');
                jQuery(this).attr('disabled', 'disabled');
                jQuery(this).addClass('input-readonly');
                jQuery(this).addClass('remove-cursor');
            });
            // Make select box read only
            jQuery('form').find('.select-box-wp-2').each(function () {
                jQuery(this).attr('disabled', 'disabled');
                jQuery(this).find('.select-box').css('opacity', '0');
                jQuery(this).addClass('select-box-readonly');
                jQuery(this).addClass('remove-cursor');
            });
            //}
<?php } ?>


// Bind download link event
        jQuery("#download_associated_document").hover(
                function () {
                    jQuery('.program_docs').css('display', 'block');
                }, function () {
            jQuery('.program_docs').css('display', 'none');
        }
        );

        jQuery(".program_docs").hover(
                function () {
                    jQuery('.program_docs').css('display', 'block');
                }, function () {
            jQuery('.program_docs').css('display', 'none');
        }
        );

    });

    //Function is used to changed company type
    function changedApplicationType(key, value) {
        var uri = document.URL;

        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            uri = uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            uri = uri + separator + key + "=" + value;
        }
        if (value != "Select Form") {
            window.location.href = '' + uri + '';
        } else
        {
            alert('Please select application form type');
        }

    }
    //Function is used to save certificate name
    function saveCertificatePopUpName() {
        //alert(1)
        var app_id = parent.jQuery('#popup_app_id').val();
        var certificate_name = jQuery('#popup_certificate_name').val();
        var app_submit_url = redirecturl;
        if (jQuery.trim(app_id) && jQuery.trim(app_submit_url)) {
            if (jQuery.trim(certificate_name)) {

                var data = {
                    action: 'save_certificate_name',
                    app_id: app_id,
                    certificate_name: certificate_name
                };
                jQuery(".spinner-wp").css('display', 'block');// Add spinner
                jQuery.post(ajaxurl, data, function (response) {
                    var response_obj = jQuery.parseJSON(response);
                    if (response_obj.status == true) {
                        //window.location.href = app_submit_url;
                        application_push_to_crm(app_submit_url, app_redirect_url);
                        return true;
                    } else {
                        alert(response_obj.msg);
                    }
                });
            } else {
                alert('Please enter certificate name.');
                return false;
            }
        } else {
            alert('Invalid parameter.');
            return false;
        }

        return false;
    }
</script>
<script>

    alert_close('technical', '<?php
if (isset($application_data->new_application->_linked->contact->new_technicalcontactid) && $application_data->new_application->_linked->contact->new_technicalcontactid != "Select" && $application_data->new_application->_linked->contact->new_technicalcontactid != "") {
    echo $application_data->new_application->_linked->contact->new_technicalcontactid;
} else {
    echo 0;
}
?>');
    alert_close('legal', '<?php
if (isset($application_data->new_application->_linked->contact->new_legalcontactid) && $application_data->new_application->_linked->contact->new_legalcontactid != "Select") {
    echo $application_data->new_application->_linked->contact->new_legalcontactid;
} else {
    echo 0;
}
?>');
    alert_close('billing', '<?php
if (isset($application_data->new_application->_linked->contact->new_billingcontact) && $application_data->new_application->_linked->contact->new_billingcontact != "Select") {
    echo $application_data->new_application->_linked->contact->new_billingcontact;
} else {
    echo 0;
}
?>');
    alert_close('chief', '<?php
if (isset($application_data->new_application->_linked->contact->new_chiefadminofficerid) && $application_data->new_application->_linked->contact->new_chiefadminofficerid != "Select" && $application_data->new_application->_linked->contact->new_chiefadminofficerid != "") {
    echo $application_data->new_application->_linked->contact->new_chiefadminofficerid;
} else {
    echo 0;
}
?>');
    function alert_close(type, id)
    {
        switch (type) {
            case "technical" :
                change_content(id, 'content_ajax', 'tech');
                break;
            case "billing" :
                change_content(id, 'content_ajax', 'billing');
                break;
            case "legal" :
                change_content(id, 'content_ajax', 'legal');
                break;
            case "chief" : /*new user chief for fire prevention program*/
                change_content(id, 'content_ajax', 'chief');
                break;
        }
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        jQuery.post(ajaxurl, {'action': 'get_user_ajax', 'id': id, 'type': type}, function (result) {
            switch (type) {
                case "technical" :
                    jQuery("#applicanttechid").html(result);
                    jQuery('#applicanttechid').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#applicanttechid option:selected").text());
                    break;
                case "billing" :
                    jQuery("#applicantbillingid").html(result);
                    jQuery('#applicantbillingid').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#applicantbillingid option:selected").text());
                    break;
                case "legal" :
                    jQuery("#applicantlegalid").html(result);
                    jQuery('#applicantlegalid').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#applicantlegalid option:selected").text());
                    break;
                case "chief" :
                    jQuery("#applicantchiefid").html(result);
                    jQuery('#applicantchiefid').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#applicantchiefid option:selected").text());
                    break;
            }

        });
        //jQuery("#applicanttechname").html(result);});
    }
    function change_content(id, action, changeid) {
        jQuery(".spinner-wp").css('display', 'block');// show spinner
        jQuery.post(ajaxurl, {'action': action, 'id': id}, function (result) {
            obj = JSON.parse(result);
            jQuery(".spinner-wp").css('display', 'none');// hide spinner
            if (obj.length > 0) {
                jQuery('#applicant' + changeid + 'title').val(obj[0].title).prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'add').val(obj[0].address).prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'number').val(obj[0].phone).prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'fax').val(obj[0].fax).prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'email').val(obj[0].user_email).prop('readonly', 'true');
            } else
            {
                jQuery('#applicant' + changeid + 'title').val('').prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'add').val('').prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'number').val('').prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'fax').val('').prop('readonly', 'true');
                jQuery('#applicant' + changeid + 'email').val('').prop('readonly', 'true');
            }
        });
    }
</script>