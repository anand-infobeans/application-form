<?php
global $wpdb, $per_page, $resulttotal;
global $current_user; // Use global
get_currentuserinfo(); // Make sure global is set, if not set it.
//include(WP_PLUGIN_DIR.'crm-connector/CrmOperations.php');
$CrmOperationsobj = new CrmOperations();
$resultsfromcrm = $CrmOperationsobj->getCrmEntityDetails('new_application', array('type' => 'and', 'conditions' => array(array('entityname' => 'new_certificate', 'operator' => 'null', 'attribute' => "new_certificateid", 'value' => ''))), 'list', '', $page, $per_page,false);
//echo "<pre>";print_r($resultsfromcrm);
$resulttotal = $resultsfromcrm->TotalRecordCount;
$roles = get_user_meta(get_current_user_id(), "wp_capabilities");
$company_admin= is_user_company_admin();

 if ( user_can($current_user, "customer") &&  ($company_admin == 1)) { 
$sql = "select `id`, `name`, `doc_id` from " . $wpdb->prefix . "applications ";
 $new_accreditation = $wpdb->get_results($sql);

?>
<script>
$(document).ready(function() {
    $('#apply_for_new_accreditation_table').DataTable( {
        "searching": false,
		"processing": true,
		"sDom": '<"head-controls"l<"app-filter">f>t<"foot-controls"ip>',
		"bPaginate":false,
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 1,2 ] }
       ]
    } );
	jQuery(".colorbox-inline-70").colorbox({
			 overlayClose: false, 
			 inline:true, 
			 speed:200, 
			 scrolling:false, 
			 width:"60%", 
			 //href: jQuery(this).attr('href')
		});
} );
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
		    <?php foreach($new_accreditation as $new_accreditation){?>
            <tr>
                <td><?php echo $new_accreditation->name; ?></td>
                 <td><?php if(!empty($new_accreditation->doc_id)){?><a href="<?php echo wp_get_attachment_url( $new_accreditation->doc_id );?>"  download>Download</a><?php }else{ echo "Not exist"; }?></td>
                <td><a class="btn btn-success btn-xs" href="<?php echo get_permalink( get_page_by_path( 'listings' )).'?page=create-form-register&program_id='.$new_accreditation->id; ?>" >Apply</a></td>
            </tr>
			<?php } ?>
        </tbody>
    </table>
			</div>
</div>
 <?php } ?>
<table  class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">

    <thead>
        <tr>
            <th>Company</th>
            <th>State</th>
            <th>City</th>
            <th>Certificate status</th>

            <th>Renewal Due Date</th>
            <th>Apply For Scope Extension</th>


        </tr>
    </thead>

    <tbody>

        <?php
        foreach ($resultsfromcrm->Entities as $result) {
            //      echo "<pre>";print_r($result->account);
            $sql = "select * from " . $wpdb->prefix . "application_data where  crm_id='" . $result->new_applicationid . "' && deleted_on is null ";
            $resultfromdb = $wpdb->get_results($sql);
            ?>
            <tr>
                <td><?php print_r(isset($result->account->new_countryidname) ? $result->account->new_countryidname : 'NA'); ?></td>
                <td><?php print_r(isset($result->account->new_stateidname) ? $result->account->new_stateidname : 'NA'); ?></td>
                <td><?php print_r(isset($result->account->address1_city) ? $result->account->address1_city : 'NA'); ?></td>
                <td><?php print_r(isset($result->new_certificateexpirationdate) ? $result->new_certificateexpirationdate : 'NA'); ?></td>

                <td><?php print_r(isset($result->new_certificateexpirationdate) ? $result->new_certificateexpirationdate : 'NA'); ?></td>
                <td><?php print_r(isset($result->scope) ? $result->scope : 'NA'); ?></td>
                <td><?php print_r(isset($result->renew) ? $result->renew : 'NA'); ?></td>

                <?php
//                foreach ($resultfromdb as $val) 
//                {
                if (user_can($current_user, "staff")) {
                    ?>
                    <td><a href="<?php echo site_url(); ?>/index.php/listings/?page=create-form-register&id=<?php
                        if ($resultfromdb[0]->id != "") {
                            echo base64_encode($resultfromdb[0]->id);
                        }
                        ?>&&crmid=<?php echo base64_encode($result->new_applicationid); ?>&view=true">View</td>
                        <?php
                    } else {
                        ?>
                    <td><?php if (isset($resultfromdb[0]->id)) { ?>
                            <a href="<?php echo site_url(); ?>/index.php/listings/?page=create-form-register&id=<?php
                               if ($resultfromdb[0]->id != "") {
                                   echo base64_encode($resultfromdb[0]->id);
                               }
                               ?>&&crmid=<?php echo base64_encode($result->new_applicationid); ?>">Edit</a>
                    <?php } ?>
                        <a href="<?php echo site_url(); ?>/index.php/listings/?page=create-form-register&appid=<?php echo base64_encode($result->new_applicationid); ?>&view=true">View
                        </a>
                    </td>  

                <?php
                ?> </tr> <?php
    }
//                }
}
        ?>
    </tbody>
</table>   