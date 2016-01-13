<div class="clearfix"></div>
<div class="divider-10"></div>
<!--<div class="col-lg-12 form-group">
 <span class="defaultP">
 	<span class="radio-phone">
         <div class="ez-checkbox" style="width:100%">
            <input type="checkbox" id="terms_condition" name="terms_condition" class="js-vld-terms js-switch terms_condition" <?php if(isset($application_data->terms_condition) && $application_data->terms_condition=="Yes"){ echo "'checked' value='Yes'";}else { echo "value='No'";}?>> <a href="<?php echo get_admin_url();?>admin-post.php?action=terms_condition&KeepThis=true&TB_iframe=true&height=600&width=600&modal=true" class="thickbox">Terms & Conditions</a>
      </span>
    </span>
</div>-->
<?php
global $error;
$error = new WP_Error();
print_r($error->get_error_code());
if( is_wp_error( $error ) ) {
    echo $error->get_error_message();
}?>
<div class="col-lg-12">
 <span class="defaultP">
   <span class="radio-phone"> 
	 <input type="checkbox" id="terms_condition" name="data[new_application][terms_condition]" <?php if (isset($application_data->new_application->terms_condition) && $application_data->new_application->terms_condition == 'Yes') { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-vld-terms js-switch checktopopulatefield form-control" onclick="checkValidCheckbox(this.id)" style="opacity:0; position:absolute;"> &nbsp; <a href="<?php echo get_admin_url();?>admin-post.php?action=terms_condition&KeepThis=true&TB_iframe=true&height=600&width=600&modal=true&program_id=<?php if (isset($program_id)) {
            echo $program_id;
        } else if (isset($_GET['program_id'])) {
            echo $_GET['program_id'];
        } else {
            echo "1";
        } ?>" class="iframe-colorbox" id="terms-condition-popup" style="display: none;">Terms & Conditions</a> <!--<span class="color-red">*</span>-->
   </span>
 </span>
</div>
<!--<div class="error-terms errors" style="display: none;margin-left:15px;">Please read / check terms and conditions</div>-->
