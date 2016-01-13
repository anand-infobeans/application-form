<?php
global $wpdb;
if(isset($_REQUEST["id"]) && $_REQUEST["id"]){
$sql = "SELECT doc_id FROM ".$wpdb->prefix ."application_docs WHERE application_id = ".base64_decode($_REQUEST["id"]);
$result = $wpdb->get_results($sql);
}
//Retrieve all Media categories
$sql = "SELECT * FROM ".$wpdb->prefix ."term_taxonomy tt,".$wpdb->prefix ."terms t WHERE t.term_id = tt.term_id AND taxonomy IN ('media_category')";
$media_categories = $wpdb->get_results($sql);
?>
<div class="col-md-12">
<p style="color: rgb(11, 89, 64); font-weight: bold;">Add documents in support of your application</p>
</div>
<div class="uploader">
	<input id="upload_app_doc" type="hidden" size="12" name="upload_app_doc" value="" />
	<input id="error_id" type="hidden" size="12" name="error_id" value="" />
	<input id="is_doc_change" type="hidden" size="12" name="is_doc_change" value="" />
	<div class='error-upload'></div>
	<?php 
	$i=1;
	if($media_categories){
		foreach($media_categories as $value){ 
			if($value->name!='Additional Option Document'){
			$category_id = $value->term_id;
			if(isset($_REQUEST["id"]) && $_REQUEST["id"]){
				$sql = "SELECT * FROM ".$wpdb->prefix ."application_docs WHERE application_id = ".base64_decode($_REQUEST["id"])." AND doc_category = ".$value->term_id." ORDER BY id DESC";
				$app_docs = $wpdb->get_results($sql);
			}
	?>
    <div class="divider-15"></div>
	<div class="col-md-12">
		<div class="col-lg-6 padding-left-0">
		<b>Upload <?php if(strtolower($value->name)=='legal document'){ echo "Management System Documents";}else if(strtolower($value->name)=='company document'){ echo "Standard Operation Documents";}else if(strtolower($value->name)=='scope of accreditation document'){echo "Scope Document";}?> : <span class="color-red">*</span></b>&nbsp;<span class="questiontags">?</span>
		</div>
		<div class="col-lg-6">
			<div class="addfiles-button fil-upload-btn">Add Files...<input type="file"  class="js-vld-upload btn btn-success" name="app_files_<?php echo $value->term_id;?>"  id="app_files_<?php echo $value->term_id;?>" multiple onchange="upload_application_document(<?php echo $value->term_id; ?>,this,<?php echo $i;?>,'<?php echo $value->slug;?>')" >
		</div>
		</div>
	</div>
    <div class="divider-5"></div>
	<div class="col-md-12">
		
		<div id="loader-icon-<?php echo $value->term_id;?>" class="loader-2 margin-left-10"style="display:none;"></div>
    </div>
	
	<div class="appdoc-<?php echo $i;?> errors" style="display: none;margin-left: 15px;"></div>
	<input id="error-text-<?php echo $i;?>" type="hidden" value="Please upload: <?php if(strtolower($value->name)=='legal document'){ echo "Management System Documents";}else if(strtolower($value->name)=='company document'){ echo "Standard Operation Documents";}else if(strtolower($value->name)=='scope of accreditation document'){echo "Scope Document";}?>" />
	
	<div id="progress-div-<?php echo $value->term_id;?>"><div id="progress-bar-<?php echo $value->term_id;?>"></div></div>
    <div id="targetLayer-<?php echo $value->term_id;?>"></div>
    <div id="upload_app_filename_<?php echo $value->term_id;?>" style="margin:15px 0px;">
   
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
	<input id="appdoc-<?php echo $i;?>" type="hidden" value="<?php if(!empty($app_docs)) echo 1; else echo 0 ;?>" name="appdoc-ctg-<?php echo $category_id;?>"/>
    <div class="divider-10"></div>
	<div class="gray-line-1"></div>
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
	<div class="col-lg-8">
		<div class="form-group">
		<b>External URL</b>
		<div class="divider-10"></div>
			<textarea class="externalurl" type="text" rows="5" id="externalurl" name="data[new_application][externalurl]"  placeholder="External URL" title="Please enter URL"><?php if(isset($application_data->new_application->externalurl)){ echo $application_data->new_application->externalurl;}?></textarea>
		<div class="errors"></div>
			<div class=""><?php echo __("If you want to upload file having filesize more than 2GB, then please enter file url(s) above separated by comma."); ?></div>
		</div>
	</div>
	
</div>
<script>
	jQuery(document).ready(function () {
	  jQuery("span.questiontags").hover(function () {
		jQuery(this).append('<div class="tooltip1" style="width:120px !important;">Upload document</div>');
	  }, function () {
		jQuery("div.tooltip1").remove();
	  });
	});
</script>


