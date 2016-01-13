<?php
global $wpdb, $per_page, $resulttotal;

$total = 'select count(*) as total from ' . $wpdb->prefix . 'application_data where status = "new_application" and deleted_on is null;';
$resulttotal = $wpdb->get_results($total);
$resulttotal = $resulttotal[0]->total;

if (isset($_GET['paged'])) {
    $sql = "select * from " . $wpdb->prefix . "application_data where status = 'new_application' and deleted_on is null limit " . $start_from . "," . $per_page;
} else {
    $sql = "select * from " . $wpdb->prefix . "application_data where status = 'new_application' and deleted_on is null limit " . $start_from . "," . $per_page;
}
$resultfromdb = $wpdb->get_results($sql);

$roles = get_user_meta(get_current_user_id(), "wp_capabilities");
$company_admin = is_user_company_admin();

if (user_can($current_user, "customer") && ($company_admin == 1)) {
    $sql = "select `id`, `name`, `doc_id` from " . $wpdb->prefix . "applications ";
    $new_accreditation = $wpdb->get_results($sql);
    ?>
    <script>
        $(document).ready(function () {
            $('#apply_for_new_accreditation_table').DataTable({
                "searching": false,
				"processing": true,
                "bPaginate": false,
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [1, 2]}
                ]
            });
            jQuery(".colorbox-inline-70").colorbox({
                overlayClose: false,
                inline: true,
                speed: 200,
                scrolling: false,
                width: "60%",
                //href: jQuery(this).attr('href')
            });
        });
    </script>
    
    <a style="float:right; font-size: 17px; padding:10px; border-radius: 8px; font-weight: bold; margin-bottom: 20px;" class="colorbox-inline-70 btn-primary cboxElement" href="#apply_for_new_accreditation">Apply for New Accreditation</a>

    <div style='display:none'>
        <div id='apply_for_new_accreditation' style='padding:10px; background:#fff; width:100%; height: 500px; overflow-y:auto; overflow-x: hidden'>

            <h4 style="font-weight:bold; color: rgb(44,105,100);"> IAS Program </h4>
            <table id="apply_for_new_accreditation_table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Program Name</th>
                        <th>Read more</th>
                        <th>Apply for Accreditation</th>
                    </tr>
                </thead>


                <tbody>
                    <?php foreach ($new_accreditation as $new_accreditation) { ?>
                        <tr>
                            <td><?php echo $new_accreditation->name; ?></td>
                            <td><?php if(!empty($new_accreditation->doc_id)){?><a href="<?php echo wp_get_attachment_url( $new_accreditation->doc_id );?>"  download>Download</a><?php }else{ echo "Not exist"; }?></td>
                            <td><a class="btn btn-success btn-xs" href="<?php echo get_permalink(get_page_by_path('listings')) . '?page=create-form-register&program_id=' . $new_accreditation->id; ?>" >Apply</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
    
     <?php
    if (isset($_SESSION['approve_error_message']) && !empty($_SESSION['approve_error_message'])) {
        echo "<div class='error'><p>" . $_SESSION['approve_error_message'] . "</p></div>";
      //  unset($_SESSION['approve_error_message']);
    } else if (isset($_SESSION['approve_success_message']) && !empty($_SESSION['approve_success_message'])) {
        echo "<div class='success'><p>" . $_SESSION['approve_success_message'] . "</p></div>";
    //    unset($_SESSION['approve_success_message']);
    }
    ?>  
 
<table id="" class=" table table-striped table-hover dt-responsive " cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Company name of principal</th>
            <th>Company name of branch</th>
            <th>New Accreditation</th>


            <th>Action</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($resultfromdb as $val) {
            $application_data = $val->application_data;
            $application_data = json_decode($application_data);
// echo "<pre>";print_r($application_data->Account->numberofemployees);die();
//echo "<pre>";print_r($application_data);die();
            ?>




            <tr>
                <td><?php
                    $company_name = '';
                    if (isset($application_data->new_application->_linked->account->new_customerid) && $application_data->new_application->_linked->account->new_customerid != "") {
                        $company_name = $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "company where id=" . $application_data->new_application->_linked->account->new_customerid);
                        echo $company_name;
                    }
                    ?></td>

                <td><?php echo (isset($application_data->new_application->desiredscopeofaccred) ? $application_data->new_application->desiredscopeofaccred : ''); ?></td>
                <td><?php echo (isset($application_data->new_application->newaccreditation) ? $application_data->new_application->newaccreditation : ''); ?></td>

                <td>
                    <?php
                    foreach ($roles as $role) {


                        if (array_key_exists("staff", $role)) {
                            ?>
                            <a href="<?php echo site_url(); ?>/index.php/listings/?page=create-form-register&id=<?php echo base64_encode($val->id); ?>&view=true">View</a>&nbsp;&nbsp;
                            <?php if (!empty($val->certificate_name)) { ?>
                                <a href="<?php echo get_admin_url(); ?>admin-post.php?action=approve-application-form&id=<?php echo base64_encode($val->id); ?>&is_ajax=true" class="" value="">Approve </a>
                            <?php } else { ?>
                                <a href="#app_certification_popup" id="approve-link-<?php echo $val->id; ?>" onclick="return openCertificatePopUp('<?php echo $val->id; ?>')" data-href="<?php echo get_admin_url(); ?>admin-post.php?action=approve-application-form&id=<?php echo base64_encode($val->id); ?>&is_ajax=true" class="colorbox-inline" value="">Approve </a>
                            <?php } ?>
                            <a href="#app_diff" class="colorbox-inline" onclick="getAppDiff('<?php echo $val->id; ?>', 'app', '<?php echo $company_name; ?>', '<?php echo get_admin_url(); ?>admin-post.php?action=approve-application-form&id=<?php echo base64_encode($val->id); ?>', '<?php echo $val->certificate_name; ?>');
                                                getAppDocDiff('<?php echo $val->id; ?>', 'doc', '<?php echo $company_name; ?>')">Diff</a>&nbsp;&nbsp;
                           <?php } else { ?>
                            <a href="<?php echo site_url(); ?>/index.php/listings/?page=create-form-register&id=<?php echo base64_encode($val->id); ?>">Edit</a>&nbsp;&nbsp;
                            <a href="<?php echo site_url(); ?>/index.php/listings/?page=create-form-register&id=<?php echo base64_encode($val->id); ?>&view=true">View</a>&nbsp;&nbsp;                          
                            <a href="<?php echo get_admin_url(); ?>admin-post.php?action=delete-application-form&id=<?php echo base64_encode($val->id); ?>" onclick="return confirm('Are you sure you want to delete application ?');">Delete</a>&nbsp;&nbsp;

                            <?php
                        }
                    }
                    ?></td>
                <td><?php echo $val->status; ?></td>
            </tr>
            <?php
        }
        ?>
    <tbody>
</table>
<?php require_once 'app-diff.php'; ?>