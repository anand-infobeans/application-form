
<?php //Retrieve all Media categories
$sql = "SELECT * FROM ".$wpdb->prefix ."term_taxonomy tt,".$wpdb->prefix ."terms t WHERE t.term_id = tt.term_id AND taxonomy IN ('media_category')";
$media_categories = $wpdb->get_results($sql);
isset($application_data->new_application->option_content)?$application_data->new_application->option_content:'';?>
<div class="col-lg-12">
 <label>
 <span class="defaultP">
 	<span class="radio-phone">
         <!-- <input type="checkbox" id="withinpastfiveyear" name="data[new_application][withinpastfiveyear]" <?php if (isset($application_data->new_application->withinpastfiveyear) && $application_data->new_application->withinpastfiveyear == 'Yes') { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-switch withinpastfiveyear form-control"> --> Within the past five years have any of your accreditations been revoked, withdrawn, placed on suspension, and/or removed from listing? If “yes” please explain on a separate page.
      </span>
    </span>
  </label>
</div>

<div class="divider-10"></div>
<?php if(isset($is_renewal) && $is_renewal){?>
<div class="revnewal_fields">
<div class="col-lg-12">
<b>
        If this is a renewal, please answer the three questions below. If you answer “yes” to any of the questions, please explain on a separate

        sheet and/or include appropriate supporting documentation.</b>

</div>

<div class="divider-10"></div>

<div class="col-lg-12">
 <label>
 <span class="defaultP">
 	<span class="radio-phone">
         <input type="checkbox" id="changesinownershiporkey" name="data[new_application][changesinownershiporkey]" <?php if (isset($application_data->new_application->changesinownershiporkey) && $application_data->new_application->changesinownershiporkey == 'Yes') { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-switch changesinownershiporkey form-control"> a.Since the last time your company applied for IAS accreditation, have there been any changes in ownership or in key management,

        technical, or quality assurance personnel?
    </span>
  </span>
 </label>
</div>

<div class="divider-10"></div>


<div class="col-lg-12">
 <label>
 <span class="defaultP">
 	<span class="radio-phone">
         <input type="checkbox" id="changesinqualitymanagement" name="data[new_application][changesinqualitymanagement]" <?php if (isset($application_data->new_application->changesinqualitymanagement) && $application_data->new_application->changesinqualitymanagement == 'Yes') { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-switch changesinqualitymanagement form-control"> b.Since the last time your company applied for IAS accreditation, have there been any changes in the documented quality system?
    </span>
 </span>
 </label>
</div>

<div class="divider-10"></div>


<div class="col-lg-12">
 <label>
 <span class="defaultP">
 	<span class="radio-phone">
         <input type="checkbox" id="awareofanycomplaint" name="data[new_application][awareofanycomplaint]" <?php if (isset($application_data->new_application->awareofanycomplaint) && $application_data->new_application->awareofanycomplaint == 'Yes') { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-switch awareofanycomplaint form-control"> c.Are you aware of any complaints, from your company’s clients or others, about the services covered by this application?
    </span>
  </span>
 <label>
</div>
</div>
<?php }?>
  
<div class="col-lg-2">
 <label> 
 <span class="defaultP">
  <span class="radio-phone">
         <input type="radio" name="data[new_application][option]" <?php if (isset($application_data->new_application->option) && $application_data->new_application->option == 'Yes') { ?> checked="checked" <?php }else if (!isset($application_data->new_application->option)) { echo "checked";} ?> value="Yes" class=" option form-control"> Yes
    </span>
  </span>
 <label>
</div>

<div class="col-lg-2">
 <label>
 <span class="defaultP">
  <span class="radio-phone">
         <input type="radio" name="data[new_application][option]" <?php if (isset($application_data->new_application->option) && $application_data->new_application->option == 'No') { ?> checked="checked" <?php } ?> value="No" class=" option form-control"> No
    </span>
  </span>
 <label>
</div>

<div class="col-md-12 showoption" <?php if (isset($application_data->new_application->option) && $application_data->new_application->option == 'No') {$style='display:none;';?>style="display:none"<?php }?>>
    <div class="clearfix" style="margin:10px;"></div>
    
    <div class="uploader">
    <input id="upload_app_doc" type="hidden" size="12" name="upload_app_doc" value="" />
    <input id="error_id" type="hidden" size="12" name="error_id" value="" />
    <input id="is_doc_change" type="hidden" size="12" name="is_doc_change" value="" />
    <div class='error-upload'></div>
    <?php 
    
    $i=0;
    if($media_categories){
      foreach($media_categories as $value){ 
        if($value->name =='Additional Option Document'){
        $category_id = $value->term_id;
        if(isset($_REQUEST["id"]) && $_REQUEST["id"]){
          $sql = "SELECT * FROM ".$wpdb->prefix ."application_docs WHERE application_id = ".base64_decode($_REQUEST["id"])." AND doc_category = ".$value->term_id." ORDER BY id DESC";
          $app_docs = $wpdb->get_results($sql);
        }
        if(isset($app_docs) && !empty($app_docs)){
          $style='display:none';
        }else if(isset($application_data->new_application->option_content) && $application_data->new_application->option_content!='')
        {
          $style='display:none';
        }else
        {
          $style='';
        }
    ?>
      <div class="divider-15"></div>
      <div class="col-md-12">
        <div class="col-lg-5 padding-left-0 additional_upload" <?php if(isset($application_data->new_application->option_content) && $application_data->new_application->option_content!=''){ ?>style='display:none;'<?php }?>>
          <b>Upload Additional Documents<?php if(strtolower($value->name)=='legal document'){ echo "Management System Documents";}else if(strtolower($value->name)=='company document'){ echo "Standard Operation Documents";}else if(strtolower($value->name)=='scope of accreditation document'){echo "Scope Document";}?> : <span class="color-red">*</span></b>&nbsp;<span class="questiontags">?</span>
        
          <div class="addfiles-button fil-upload-btn">Add Files...<input type="file"  class="js-vld-upload btn btn-success <?php echo $value->slug;?>-file" name="app_files_<?php echo $value->term_id;?>"  id="app_files_<?php echo $value->term_id;?>" multiple onchange="upload_application_document(<?php echo $value->term_id; ?>,this,<?php echo $i;?>,'<?php echo $value->slug;?>')" >
          </div>
          <div class="appdoc-<?php echo $i;?> errors" style="display: none;margin-top:5px;"></div>
          <div class="col-md-12">
            <div id="loader-icon-<?php echo $value->term_id;?>" class="loader-2"style="display:none;margin:5px 0px  ;"></div>
          </div>
          </div>
          <div class="col-lg-2 or_class" style='<?php echo $style;?>'><h3>OR</h3></div>
          <div class="col-lg-5 additional_content" <?php if(isset($app_docs) && !empty($app_docs)){?>style='display:none'<?php }?>>
            <div class="form-group">
              <label>Additional Content</label>
              <textarea class="<?php if(empty($app_docs)){?>js-vld-required<?php }?>" type="text" id="option_content" name="data[new_application][option_content]" placeholder="" onblur="checkallowallcharacterInput(this.value,this.id)" title="Please enter content"><?php if(isset($application_data->new_application->option_content)){ echo $application_data->new_application->option_content;}?></textarea>
            </div>
          </div>
      </div>
    </div>
      <div class="divider-5"></div>
    
    <input id="error-text-<?php echo $i;?>" type="hidden" value="Please upload: <?php if(strtolower($value->name)=='legal document'){ echo "Management System Documents";}else if(strtolower($value->name)=='company document'){ echo "Standard Operation Documents";}else if(strtolower($value->name)=='scope of accreditation document'){echo "Scope Document";}?>" />
    
    <div id="progress-div-<?php echo $value->term_id;?>"><div id="progress-bar-<?php echo $value->term_id;?>"></div></div>
      <div id="targetLayer-<?php echo $value->term_id;?>"></div>
      <div id="upload_app_filename_<?php echo $value->term_id;?>" class="<?php echo $value->slug;?>" style="margin:15px 0px;">
     
    <?php 

    if(isset($app_docs)){
      foreach($app_docs as $value){
        ?>
              <div class="col-md-8">
        <div class="pull-left">
        <?php 
        $filedata = wp_check_filetype( basename(wp_get_attachment_url( $value->doc_id )));
        if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps") $fileclass = "file-icon-word";
        elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et") $fileclass = "file-icon-excel";
        elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png") $fileclass = "file-icon-image";
        elseif ($filedata["ext"] == "pdf") $fileclass = "file-icon-pdf";
        elseif ($filedata["ext"] == "zip") $fileclass = "file-icon-zip";
        elseif ($filedata["ext"] == "ppt") $fileclass = "file-icon-ppt";
        ?>
              <span class="<?php echo $fileclass;?>"></span>
        <span class="file-icon-label"><a href="<?php echo wp_get_attachment_url($value->doc_id); ?>" target="_blank" download><?php
        echo basename(wp_get_attachment_url( $value->doc_id ));?>
              </a></span>
        </div>
        <div class="pull-right">
                              <a class="btn-wizard-download" target="_blank" href="<?php echo wp_get_attachment_url( $value->doc_id );?>" download title="Download"> &nbsp; </a>
        <input class="btn-wizard-upload margin-left-10 removefile" type='button' value='Remove' id='<?php echo $value->id."_".$value->application_id."_".$value->doc_category."_".$i;?>' style="display:inline;" title="Remove"></div></div>
              
              <div class="divider-10"></div>
        <?php
      
      }
    
    } 
    ?></div>
    <input id="appdoc-<?php echo $i;?>" type="hidden" value="<?php if(!empty($app_docs) || (isset($application_data->new_application->option_content))) echo 1; else echo 0 ;?>" name="appdoc-ctg-<?php echo $category_id;?>" />
      <div class="divider-10"></div>
    
    <?php
    $i++;
      }/*foreach condition brackets*/
    }/*if condition brackets*/
      ?>
      
      <?php
      
    }
    ?>
    <div class="divider-10"></div>
    <div class="clearfix"></div>
    
  </div>
  
  
<?php if (isset($application_data->new_application->option) && $application_data->new_application->option == 'No') { ?>
<script type="text/javascript">
  $('#app_files_20').removeClass('js-vld-upload');
  $('#option_content').removeClass('js-vld-required');
  $('#appdoc-0').val(1);
</script>
<?php }?>
<style type="text/css">.additional_content{margin-top: -15px}</style>