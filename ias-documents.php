<?php
if (!is_user_logged_in()) {
    wp_redirect(site_url());
}
$get_current_user_role = false;
if (function_exists('get_current_user_role')) {
        $get_current_user_role = get_current_user_role();
}
if($get_current_user_role && $get_current_user_role != 'Assessor') {
    wp_redirect(get_permalink( get_page_by_path( 'user-dashboard' ) ));
}
global $wpdb;
$sql = 'SELECT * FROM ' . $wpdb->prefix . 'programs ORDER BY name ASC';
$result = $wpdb->get_results($sql);
?>
<div class="floating-line"></div>
<div class="divider-10"></div>
<!-- <div class="col-lg-12" style="  border: 1px solid #e6e6e6; color: #255a53;"><h4><b>Programs</b></h4></div> -->
    <link rel="stylesheet"href="<?php echo get_bloginfo('template_directory'); ?>/core/css/style_js_tree.css" />
<script src="<?php echo get_bloginfo('template_directory'); ?>/core/js/jstree.min.js"</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<div id="container">
	<?php foreach ($result as $val) { ?>

	<?php
		$sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $val->id . " ORDER BY ID DESC";
		$program_docs = $wpdb->get_results($sql);
	?>
	  <ul>
		<li data-jstree='{ "selected" : false, "opened" : false }'><b><?php echo $val->name; ?></b>

			<?php
            if ($program_docs) {
                foreach ($program_docs as $doc) {

				$filedata = wp_check_filetype(basename(wp_get_attachment_url($doc->doc_id)));
				$breakpath = wp_get_attachment_url($doc->doc_id);
				$breakpath = str_replace(site_url() . "/", '', $breakpath);
				if (file_exists($breakpath)) {

	                if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps")
	                    $fileImageName = "micro_word.png";
	                elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et")
	                    $fileImageName = "excel_ico.png";
	                elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png")
	                    $fileImageName = "image_ico.png";
	                elseif ($filedata["ext"] == "pdf")
	                    $fileImageName = "pdf_ico.png";
	                elseif ($filedata["ext"] == "zip")
	                    $fileImageName = "zip_ico.png";
	                elseif ($filedata["ext"] == "ppt")
	                    $fileImageName = "powerpoint_ico.png";
			?>
		      <ul>
		        <li data-jstree='{"icon":"<?php echo get_bloginfo('template_directory'); ?>/core/images/ib-icons/<?php echo $fileImageName; ?>"}'>
		        	<a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>"><u><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></u></a>
		        	</li>
		      </ul>
	    		<?php } ?>
	    	<?php } ?>
	    <?php } ?>
	    </li>
	  </ul>
  	<?php } ?>
</div>

<?php /*$old_table = '<table>
    <tbody>
        <tr>
            <?php
            $j = 0;
            foreach ($result as $val) {
                if ($j % 3 == 0) {
                    echo "</tr><tr>";
                }
                //retrieving documents from database
                $sql = "SELECT * FROM " . $wpdb->prefix . "program_docs WHERE program_id = " . $val->id . " ORDER BY ID DESC";
                $program_docs = $wpdb->get_results($sql);
//                echo "<pre>";
//                print_r($val);
//                print_r($program_docs);
                ?>

                <td style="vertical-align: top;">
                    <div class="divider-10"></div>

                   <?php if ($program_docs) { ?>
                         <div class="col-md-10"><b><?php echo $val->name; ?></b><a href="javascript:void(0)" style="float: right" id="open_programs-<?php echo $val->id; ?>" value="+" class="glyphicon glyphicon-chevron-down program-document"></a></div>
                    <?php } else { ?>
                        <div class="col-md-10"><b><?php echo $val->name; ?></b></div>
                    <?php } ?>
                    <div class="col-md-12">
                        <div id="loader-icon-<?php echo $val->id; ?>" class="loader-2 margin-right-10 pull-right"style="display:none;"></div>
                        <div id="progress-div-<?php echo $val->id; ?>"><div id="progress-bar-<?php echo $val->id; ?>"></div></div>
                        <div id="targetLayer-<?php echo $val->id; ?>"></div>

                    </div>

                        <?php
                        if ($program_docs) {
                            foreach ($program_docs as $doc) {
                                $filedata = wp_check_filetype(basename(wp_get_attachment_url($doc->doc_id)));

                                $breakpath = wp_get_attachment_url($doc->doc_id);
                                $breakpath = str_replace(site_url() . "/", '', $breakpath);
                                if (file_exists($breakpath)) {

                                    ?>
                                <div id="upload_program_filename_<?php echo $val->id; ?>" >

                                <div class="col-md-10 my-programs-div_<?php echo $doc->program_id?>"  style="padding-top:10px;display: none;">
                                        <div class="pull-left">
                                            <?php
                                            if ($filedata["ext"] == "doc" || $filedata["ext"] == "docx" || $filedata["ext"] == "odt" || $filedata["ext"] == "ods" || $filedata["ext"] == "wps")
                                                $fileclass = "file-icon-word";
                                            elseif ($filedata["ext"] == "xls" || $filedata["ext"] == "xlsx" || $filedata["ext"] == "et")
                                                $fileclass = "file-icon-excel";
                                            elseif ($filedata["ext"] == "jpeg" || $filedata["ext"] == "jpg" || $filedata["ext"] == "png")
                                                $fileclass = "file-icon-image";
                                            elseif ($filedata["ext"] == "pdf")
                                                $fileclass = "file-icon-pdf";
                                            elseif ($filedata["ext"] == "zip")
                                                $fileclass = "file-icon-zip";
                                            elseif ($filedata["ext"] == "ppt")
                                                $fileclass = "file-icon-ppt";
                                            ?>
                                            <span class="<?php echo $fileclass; ?>"></span>
                                            <span class="file-icon-label">
                                                <a href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" target="_blank" download><?php echo basename(wp_get_attachment_url($doc->doc_id)); ?></a>
                                            </span>
                                        </div>
                                        <div class="pull-right">
                                            <a class="btn-wizard-download btn-document" href="<?php echo wp_get_attachment_url($doc->doc_id); ?>" download> &nbsp; </a>

                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                            }
                        }
                        //retrieving documents from database
                        ?>

                    </div>
                    <div class="divider-10"></div>
                </td>


                <?php
                $j++;
            }
            ?>
        </tr>
    </tbody>
</table>';*/
?>

<style>
    /*.btn-document{text-decoration: none;}*/
    .demo { overflow:auto; border:1px solid silver; min-height:100px; }
</style>

<script>

$(function() {
	$.jstree.defaults.core.themes.variant = "large";
	$.jstree.defaults.core.themes.responsive = true;
	$('#container').jstree({
		"plugins" : ["themes","html_data","ui"]
	});
    $(document).on('click', '#container ul > li.jstree-open > ul > li.jstree-leaf a', function(){
	//jstree-open
    	//if( $('#container li.jstree-open') )
		  	window.open( this.href );
	});
});

    jQuery(document).on('click', '.program-document', function () {
        var element_id = this.id;
        var str = element_id.split('-');
        var str = str['1'];
    if ($(this).val() == "-")
        {
            $(this).val("+");
            $(this).removeClass("glyphicon-chevron-up");
            $(this).addClass("glyphicon-chevron-down");
        } else
        {
            $(this).val("-");
            $(this).removeClass("glyphicon-chevron-down");
            $(this).addClass("glyphicon-chevron-up");
        }
        jQuery('.my-programs-div_' +str).slideToggle(700, "linear", function () {
            // actions to do when animation finish
        });
    });
</script>