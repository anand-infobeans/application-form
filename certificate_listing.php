<?php
// session_start();
// echo "<pre>";print_r($_SESSION);die;
function mres($value)
{
    $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

    return str_replace($search, $replace, $value);
}

if (isset($_POST['submit'])) {
        set_site_message('my-certificates', 'success', "The request for scope extension has been successfully sent.");
                        global $wpdb;
                        $bnfw = BNFW::factory();
                        if ($bnfw->notifier->notification_exists('scope-ext-notifier')) {
                            $notifications = $bnfw->notifier->get_notifications('scope-ext-notifier');
                                foreach ($notifications as $notification) {
                                    $sql = "select `first_name`,`last_name` from " . $wpdb->prefix . "users where `ID` = '" . get_current_user_id() . "'";
                                    $customer_user = $wpdb->get_row($sql);
                                    $firstname = $customer_user->first_name;
                                    $lastname = $customer_user->last_name;
                                    $certificate_name = $_POST['scope_certificate_name'];
                                    $list_of_standards = $_POST['standards'];
                                    $scopefilename = $_POST['scope_filename'];
                                    $scopefilename = explode('~IASfilename~', $scopefilename);
                                    $scopefilename = array_filter($scopefilename);
                                    $scopefileurl = $_POST['scope_fileurl'];
                                    $scopefileurl = explode('~IASurl~', stripslashes($scopefileurl));
                                    $scopefileurl = array_filter($scopefileurl);
                                    $date = date('Y-m-d');
                                    $setting = $bnfw->notifier->read_settings($notification->ID);
                                    $scopeusermail = get_option( 'scope_notification' );
                                    $scopeusermail = explode(',', $scopeusermail);
                                    $subjectstaff = $setting['subject'];
                                    $messagecustomer = $setting['message'];
                                    $messagecustomer = str_replace('[firstname]', $firstname, $messagecustomer);
                                    $messagecustomer = str_replace('[lastname]', $lastname, $messagecustomer);
                                    $messagecustomer = str_replace('[date]', $date, $messagecustomer);
                                    $messagecustomer = str_replace('[certificate_name]', $certificate_name, $messagecustomer);
                                    $messagecustomer = str_replace('[list_of_standards]', $list_of_standards, $messagecustomer);
                                    $scopefilename= implode(' <br />-', $scopefilename);
                                    $scopefileurl= implode(' <br />-', $scopefileurl);
                                    $messagecustomer = str_replace('[doc_name]', "-".$scopefileurl, $messagecustomer);
                                    $attachment = $_POST['scopefile'];
                                    $attachment_ = explode('~IAS~', $attachment);
                                    $attachment = array();
                                    add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
                                    $dirs = wp_upload_dir();
                                    remove_filter( 'upload_dir', 'wpse_183245_upload_dir' );
                                    $base_dir = $dirs['path'] . '/';
                                    foreach($attachment_ as $file){
                                    $attachment[] = $base_dir.$file;
                                    }
                                    wp_mail($scopeusermail, $subjectstaff, wpautop($messagecustomer));
                             }
                        }
		if( (isset($_POST['scope_app_id'])) && ($_POST['scope_app_id'] > 0)) {
			$update_result = update_certificate_status_by_app_id($_POST['scope_app_id'], "new");
			if($update_result instanceof WP_Error) {
				site_messages($update_result->get_error_message());
			}
		}
    }
    if(isset($_GET['page']) && !empty($_GET['page'])) {
    $pageCode=$_GET['page'];
    site_messages($pageCode);
}   
?>
    <div style="display:none;">

        <div class="col-lg-12" id="scope_ext">
            <div class="pop-heading-wp">

                Apply For Scope Extension
            </div><!--pop-heading-wp-->
            <form method="post" id="scope_form" enctype="multipart/form-data">
            <input type="hidden" id="scope_app_id" name="scope_app_id" value="" />
            <input type="hidden" id="scopefile" name="scopefile" value="" >
            <input type="hidden" id="scope_certificate_name" name="scope_certificate_name" value="" >
            <input type="hidden" id="scope_filename" name="scope_filename" value="" >
            <input type="hidden" id="scope_fileurl" name="scope_fileurl" value="" >
                <div class="col-md-12">
                    <div class="col-md-3 padding-left-0 "> <span class="color-red-right">*</span> <span class="inner-frm-lable">Certificate Name</span></div>
                    <div class="col-md-9 padding-right-0" >

                        <div class="form-group" >
                            <div class="" >
                                <span class="select-value">Select Certificate</span>
                                <select name="scopecertificate" style="display:none;" class="select-box width-100" id="scopecertificate" onchange="selectcertificate(this.value);">

                                </select>
                            </div>
                            <div class="errors" id="scopecertificate-error"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-3 padding-left-0 "> <span class="color-red-right">*</span> <span class="inner-frm-lable">List of Standards</span></div>
                    <div class="col-md-9 padding-right-0">
                        <div class="form-group">
                            <input type="text" placeholder="List of standards" name="standards" value="" class="required pop-up-innput standards" id="standards" onblur="standards_value_session(this.value,$('#scope_app_id').val());">
                        <div class="errors" id="standard-errors"></div>
                        </div>

                    </div>
                    
                </div>


                <div class="col-md-12">
                    <div class="col-md-3 padding-left-0 "> <span class="color-red-right">*</span> <span class="inner-frm-lable">Documents</span></div>
                    <div class="col-md-7 padding-right-0">
                        <div class="form-group">
                            <div class="addfiles-button fil-upload-btn">Add Files...<input type="file"  class="js-vld-upload btn btn-success" name="file_to_upload"  id="file_to_upload" multiple onchange="upload_document(this);">
                            </div>
                        <div class="errors" id="scopedoc-errors"></div>
                        </div>

                    </div>
                    <div class="col-md-2"><span id="loader-icon-certficates" class="loader-2 margin-right-10 pull-right" style="display:none;"></span></div>
                    </div>
      <div class="col-md-12">
           <div class="col-md-3 padding-left-0 "></div>
           <div class="col-md-9 padding-right-0" id="uploaded_files">
                <div id="js_file_list" class="addscroll">                        

                </div>
                <div id="js_file_template" style="display: none;">
                        <div class="pull-left">
                        
                            <span class="file-icon-label"><span class="file-icon"></span><a href="" target="_blank" download=""></a></span>
                        </div>
                        <div class="pull-right">
                            <a class="btn-wizard-download" target="_blank" href="" download="" title="Download"> &nbsp; </a>
                            <input class="btn-wizard-upload margin-left-10 removefileforscopedocument" data-id="" type="button" value="Remove" style="display:inline;" title="Delete" onclick="delete_scope_document($(this))">
                        </div>
                    </div>
                    <div class="divider-10"></div>
            </div>
        </div>
             <div class="col-md-12 ">
                        <div class="col-md-3 padding-left-0 ">  </div>
                        <div class="col-md-9 padding-right-0">

                            <input style="min-width:100%" type="submit" name="submit" value="Submit" class="btn-primary btn btn-large-custom width-100" onclick="return validate_scope_form();">
                            
                         </div>
                    </div>


                    <div class="divider-5"></div>

                 
         
                   </form>
    </div></div>
    <div style="display: none;">
        <div id="check_renewal_popup" style="padding: 10px;margin-bottom:50px;">
           <div class="col-md-12" style=" font-size: 17px;font-weight:bold ">
                Payment details for renewal of this accreditation is pending. Please contact IAS support for more details.
            </div>
            
        </div>
    </div>
<div class="col-md-12 padding-left-0 padding-right-0 inner-main-heading">
                <div class="col-md-8 padding-left-0 padding-right-0">
                <h1 class="entry-title post-title"><span class="heading-line-height">Certificates</span><div class="clearfix"></div></h1>
            </div>
            <?php if(user_can($current_user, "customer")){?>
             <a class="colorbox-inline-70 btn btn-primary color-blue pull-right cboxElement" href="#apply_for_new_accreditation"> Apply for New Accreditation</a>
           <?php }?>
           </div>
    <table  class="wp-list-table  table table-striped table-hover dataTable no-footer" cellspacing="0" width="100%" id = "applicationTableFlow">
        <thead>
            <tr>
    			<th>View Applications</th>
    			<th>Certificate Name</th>
                <!--<th>Certificate URL</th>-->
    			 <th>Certificate URL</th>
                 <th>Certificate Due Date</th>
                <th>Certificate status</th> 
                 <th class="text-center">Actions</th>
            </tr>
        </thead>
    </table>

    <script>

    var oldFiles = '';
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        function upload_document(event) {
        jQuery('#loader-icon-certficates').show();
        var temp;
        var file_to_upload = $("#file_to_upload")[0];
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf('MSIE ');
        $("#errors").html(' ');
            
            if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) < 10) {
                
            var fileName = file_to_upload.value;
            var iFileSize = file_to_upload.size;
            var iConvert = (file_to_upload.size / 1024).toFixed(2);
            var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
            var ext = ext.toLowerCase();
                   
            if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
                var appfileName = fileName.substr(fileName.lastIndexOf("\\")+1, fileName.length);
                $("#scopedoc-errors").append(appfileName + ': File type is not supported'+'<br />');
                jQuery('#loader-icon-certficates').hide();
            }
            else if (iConvert > 18038862643.2) {
                $("#scopedoc-errors").append(fileName + ':File size exceeded');
                jQuery('#loader-icon-certficates').hide();
            }else if(oldFiles == file_to_upload.value){
                $("#scopedoc-errors").append(appfileName + ': This file is already uploaded.');
                jQuery('#loader-icon-certficates').hide();
            }
            else {
                temp = 1;
            }
            if (temp == 1) {
                theForm = event.form;
                theForm.action.value = "file_upload_for_scope_ext";
                theForm.submit();
                oldFiles = file_to_upload.value;
                scope_form.reset()
            }
        } else { 
            var files = file_to_upload.files;
            var data = new FormData();
            for (i = 0; i < files.length; i++) {
                var fileobject = files[i];
                var fileName = fileobject.name;
                var iFileSize = fileobject.size;
                var iConvert = (fileobject.size / 1024).toFixed(2);
                var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                var ext = ext.toLowerCase();
                
                if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
                    $("#scopedoc-errors").append(fileName + ': File type is not supported'+'<br />');
                    jQuery('#loader-icon-certficates').hide();
                    $.colorbox.resize();
                }
                else if (iConvert > 18038862643.2) {
                    $("#scopedoc-errors").append(fileName + ':File size exceeded');
                    jQuery('#loader-icon-certficates').hide();
                }else if(oldFiles == file_to_upload.value){
                $("#scopedoc-errors").append(appfileName + ': This file is already uploaded.');
                jQuery('#loader-icon-certficates').hide();
            }
                else {
                    data.append('file_to_upload' + i, files[i]);
                    temp = 1;
                }
            }
            if (temp == 1) {
                
                ajaxurl_post = ajaxurl+"?action=file_upload_for_scope_ext";
                data.append("action", "file_upload_for_scope_ext");
                data.append('scope_app_id', $('#scope_app_id').val());
                data.append('standards', $('#standards').val());
                //save document
                jQuery.ajax({
                    type: 'POST', // Adding Post method
                    url: ajaxurl_post, // Including ajax file
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (result) {
                        var data = JSON.parse(result);
         
                        // Show returned data using the function.
                        if(data.result == "error") {
                            $("#uploaded_file_operations").append(data.file);
                        } else {
                            $('#js_file_list').html('');
                         set_data_on_html(data);
                        }
                        jQuery('#loader-icon-certficates').hide();
                    }
                });
            }
        }
    }

    function delete_scope_document(filename) {
        filename = filename.attr('data-id');
if (confirm("Are you sure you want to delete?")) {
        jQuery.post(
            ajaxurl, 
            {
                'action': 'remove_scope_documents', 
                'file_name': filename,
                'scope_app_id': $('#scope_app_id').val()
            }, function (response) {
                var data = JSON.parse(response);
                if(data.result == "success") {
                    $('#js_file_list').html('');
                    
                   set_data_on_html(data);
                
                    docdiv = $('#js_file_list').is(':empty');
                if(docdiv){
                    $('.addscroll').css('display','none');
                    $.colorbox.resize();
                }else {
                    $('.addscroll').css('display','block');
                }
                } else {
                    alert(data.message);
                }
            }
        );
    }
    return false;
}

    function scope_ext(id,standardsession){
    jQuery.post(ajaxurl, {'action': 'get_session_for_standards', 'appid': id}, function (result) {
        if(result){
        jQuery('.standards').val(result);   
    }
    });
     
    jQuery('#scopecertificate').parent().removeAttr("style");
    jQuery('#scopecertificate-error').html("");
    jQuery('#standards').removeAttr("style");
    // jQuery('#standards').val("");
    jQuery('#standard-errors').html("");
    jQuery('#scopedoc-errors').html("");
    $('#scope_app_id').val(id);
    $('#scopecertificate').val(id);
    $('#scope_certificate_name').val($('#scopecertificate option:selected').text());
    jQuery('#scopecertificate').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#scopecertificate option:selected").text());
       
      
        jQuery.post(
            ajaxurl, 
            {
                action: 'get_uploaded_scope_documents', 
                'scope_app_id': id
            }, function (response) {
               $('#js_file_list').html('');
                var data = JSON.parse(response);
               set_data_on_html(data);
                docdiv = $('#js_file_list').is(':empty');
                if(docdiv){
                    $('.addscroll').css('display','none');
                }else {
                    $('.addscroll').css('display','block');
                }
                
            }
        );
       
    }

    function set_data_on_html (data) {
        
            data = data.files;
            filestr = '';
            scopefilenamestr = '';
            scope_fileurl = '';
            for(var i in data){
            var ext =data[i].ext; 
            if (ext == ".doc" || ext == ".docx" || ext == ".odt" || ext == ".ods" || ext == ".wps") {var fileclass = 'file-icon-word';}
            else if (ext == ".xls" || ext == ".xlsx" || ext == ".et") {var fileclass ='file-icon-excel';}
            else if (ext == ".jpeg" || ext == ".jpg" || ext == ".png") {var fileclass ='file-icon-image';}
            else if (ext == ".pdf") {var fileclass ='file-icon-pdf';}
            else if (ext == ".zip")  {var fileclass ='file-icon-zip';}
            else if (ext == ".ppt") {var fileclass ='file-icon-ppt';}
            
                if(typeof data[i].filename != 'undefined'){
                    filestr += '~IAS~'+data[i].filename;
                    scope_fileurl += '~IASurl~'+data[i].filename+' <a class="btn btn-xs btn-success" href="'+data[i].url+'" download title="Download">Download</a>';
                    scopefilenamestr +='~IASfilename~'+data[i].filename;
                        $('#js_file_template .file-icon-label a').each(function(){ this.href = data[i].url;
                    });
                    $('#js_file_template .file-icon-label span').html("<span class='"+fileclass+"'></span>");
                    $('#js_file_template .file-icon-label a').html(data[i].filename);
                    $('#js_file_template a.btn-wizard-download').prop('href', data[i].url);
                    $('#js_file_template .removefileforscopedocument').attr('data-id', data[i].filename);
                    var file_row = $('#js_file_template').html();
                    $('#js_file_list').append('<div id="js_file_' + data[i].filename + '">' + file_row + '</div><div style="clear: both;"></div>');
                    
                }
            }
            $('.addscroll').show();
            $('#scope_filename').val(scopefilenamestr);
            $('#scope_fileurl').val(scope_fileurl);
            jQuery('#scopedoc-errors').html("");
            $('#scopefile').val(filestr);
            $.colorbox.resize();
    }

    function validate_scope_form() {
            
            temp = 1;
            scopecertificate = jQuery('#scopecertificate').val();
            standards = jQuery('#standards').val();
            scopedoc = jQuery('#file_to_upload').val();
            docdiv = $('#js_file_list').is(':empty');
            
            if (scopecertificate == '')
            {
                jQuery('#scopecertificate').parent().attr('style', 'border: 1px solid red !important');

                jQuery('#scopecertificate-error').html("Certificate is required");

                temp = 0;
            } else {
                jQuery('#scopecertificate').parent().removeAttr("style");
               jQuery('#scopecertificate-error').html("");
            }
            

            if (standards == '')
            {
                jQuery('#standards').attr('style', 'border: 1px solid red !important');
                jQuery('#standard-errors').html("Please fill the standards");
                temp = 0;
            } else {
                jQuery('#standards').removeAttr("style");
                jQuery('#standard-errors').html("");
            }
            if (docdiv)
            {

                jQuery('#scopedoc-errors').html("Please upload the documents" + "<br />");
                temp = 0;
            } else {
                jQuery('#scopedoc-errors').html("");
            }
           

           
            if (temp) {
                
        jQuery(".spinner-wp").css('display', 'block');// Add spinner       
        return true;

            } else
            {
                $.colorbox.resize();
                return false;
            }

        }

        function selectcertificate(id){
            $('#scopecertificate').val(id);
           $('#scope_certificate_name').val($('#scopecertificate option:selected').text());
        }

        function standards_value_session(value,appid){
          jQuery.post(ajaxurl, {'action': 'session_for_standards', 'value': value, 'appid': appid}, function (result) {
        // alert(result);
    });


        }

    </script>
    <?php require_once 'app-diff.php'; ?>
