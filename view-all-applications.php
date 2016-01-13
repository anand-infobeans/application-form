<?php
global $wpdb;
$sql = 'SELECT * FROM '.$wpdb->prefix .'programs ORDER BY name ASC';
$result = $wpdb->get_results($sql);
?>
<?php if( isset($_GET['settings-updated']) ) { ?>
	<div id="message" class="updated">
	<p><strong><?php _e('Settings saved.') ?></strong></p>
</div>
<?php } ?>
<style type="text/css">body{background:none !important;}</style>
<div class="wrap">
    <div class="col-lg-3">
        <h2>Program Documents</h2>
    </div>
</div>
<div class='error-upload'></div>
<iframe name="program_docs" id="program_docs" height="0" width="0"></iframe>
<form action="admin-post.php" name="frmviewprograms" id="frmviewprograms" method="post" target="program_docs" enctype="multipart/form-data">
<input type="hidden" name="action" value="save_program_document">
<input type="hidden" name="program_id" value="">
<table id="example" class="wp-list-table widefat fixed striped pages apptable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th scope="col" width="15%"><b class="pull-left">Programs</b></th>
			<th scope="col" width="13%"><b class="pull-left">Configure</b></th>
			<th scope="col"><b class="pull-right">Upload Document</b></th>
			<th scope="col" width="17%"><b class="pull-left">Upload Additional Document</b></th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($result as $val) {
    ?>
	<tr>
		<td>
		
		<div ><b><?php echo $val->name; ?></b></div>
		</td>
		<td>
		
       <div ><a href="admin.php?page=templates&app_id=<?php echo $val->id; ?>"><?php echo "Manage application fields"; ?></a></div>
        </td>
		

		
		<td>
		<div ><div class="addfiles-button fil-upload-btn  pull-right">Add files...<input type="file"  class="js-vld-upload btn btn-success" name="app_files_<?php echo $val->id;?>"  id="app_files_<?php echo $val->id;?>" onchange="upload_program_document(<?php echo $val->id; ?>,this)" multiple>
		
		
		</div> 
		<div id="loader-icon-<?php echo $val->id;?>" class="loader-2 margin-right-10 pull-right"style="display:none;"></div>
		<div id="progress-div-<?php echo $val->id;?>"><div id="progress-bar-<?php echo $val->id;?>"></div></div>
		<div id="targetLayer-<?php echo $val->id;?>"></div>
		
        </div>
        
        <div id="upload_program_filename_<?php echo $val->id;?>">
        
       <?php
		//retrieving documents from database 
    $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $val->id. " AND additional!=1 ORDER BY ID DESC";
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
                        <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                    </span>
                </div>
                <div class="pull-right">
                    <a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" download> &nbsp; </a>
                    <input class="btn-wizard-upload margin-left-10 remove_programfile" type='button' value='Remove' id='<?php echo $doc->ID . "_" . $doc->program_id; ?>' style="display:inline;">
                </div>
            </div>
          
            <?php
        }

    }
    //retrieving documents from database
		?>
		<div class="divider-10"></div>
		</div>
		</td>
        		<td>
			<?php if($val->id==11 || $val->id==2){?>
			<div ><div class="addfiles-button fil-upload-btn  pull-right">Add files...<input type="file"  class="js-vld-upload btn btn-success" name="app_files_<?php echo $val->id;?>"  id="app_additional_files_<?php echo $val->id;?>" onchange="upload_additional_program_document(<?php echo $val->id; ?>,this)" multiple></div></div>
			<?php }?>
			<div id="additional-loader-icon-<?php echo $val->id;?>" class="loader-2 margin-right-10 pull-right"style="display:none;"></div>
			<div id="upload_additional_program_filename_<?php echo $val->id;?>">
			
			<?php
				//retrieving documents from database 
			$sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $val->id. " AND additional=1 ORDER BY ID DESC";
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
								<a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
							</span>
						</div>
						<div class="pull-right">
							<a class="btn-wizard-download" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" download> &nbsp; </a>
							<input class="btn-wizard-upload margin-left-10 remove_additional_programfile" type='button' value='Remove' id='<?php echo $doc->ID . "_" . $doc->program_id; ?>' style="display:inline;">
						</div>
					</div>
				  
					<?php
				}
		
			}
			//retrieving documents from database
				?>
        </td>
	</tr>
<?php } ?>
    </tbody>
</table>
</form>
