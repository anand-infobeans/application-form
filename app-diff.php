<div style="display: none;">
    <div id="app_certification_popup" style="padding: 10px;">
        <h1  class="entry-title post-title">
            Add Certificate Name
        </h1>
        <div>
        <form method="post">
            <table class="table-striped table-hover">
                <tr id="certificate_name_tr">
                    <td>Certificate Name<span class="color-red">*</span></td>
                    <td>
                        <input type="text" id="popup_certificate_name" class="required form-control pop-up-innput" value="">
                    </td>
                </tr>
                <tr id="third_party_tr" style='display:none;'>
                    <td>IAS Accreditation number of the third-party inspection agency <span class="color-red">*</span></td>
                    <td>
                        <input type="text" value="" class="required form-control pop-up-innput" id="popup_third_party_number">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <input type="submit" value="Submit" class="btn btn-primary" onClick="return saveCertificatePopUpName()" id="submit-button" />
                        <input type="hidden" id="popup_app_id"/>
                        <input type="hidden" id="popup_program_id" value=""/>
                    </td>
                </tr>
            </table>
            </form>
        </div>

    </div>
</div>
<div style="display: none;">
    <div id="app_diff">
        <h1  class="entry-title post-title">
            Log for Company:&nbsp;&nbsp;<span id="company_name"></span>
        </h1>

        <div class="col-md-12 padding-left-0 padding-right-0" style="margin-top:10px;margin-bottom: 10px; ">
            <div class="col-md-3 padding-left-0"> 
                <label>Certificate Name<span class="color-red">*</span></label>

            </div>
            <div class="col-md-8 padding-right-0">
                <span class="pull-left">
                    <input type="text" id="certificate_name" class="required form-control pop-up-innput" value="" name="certificate_name">
                </span>
                <span class="pull-left" style="margin-left: 5px;margin-top:-2px;display:none;">
                    <a href="#" id="approve_url" redirect-href="#" onclick="return validateCertificateName()" class="btn btn-primary">Push To CRM</a>
                </span>
                <span class="clearfix"></span>

            </div>

        </div>
        <div class="diff-data">

            <table id="tbl-app-data" class="table-striped table-hover">
                <thead>
                    <tr style="font-weight:bold">
                        <th class="nosort" style="text-align:center;">Changed Field</th>
                        <th class="nosort" style="text-align:center;">Old Value</th>
                        <th class="nosort" style="text-align:center;">New Value</th>
                    </tr>
                </thead>
                <tbody id="data">

                </tbody>
            </table>
        </div>
        <div class="diff-doc" style="margin-top: 20px;">
            <!--<h1 class="entry-title post-title">Application Document Log</h1>-->
            <table id="tbl-app-doc" class="table-striped table-hover">
                <thead>
                    <tr style="font-weight:bold">   
                        <th class="nosort" style="text-align:center;">Document Category</th>
                        <th class="nosort" style="text-align:center;">Added</th>
                        <th class="nosort" style="text-align:center;">Removed</th>
                    </tr>
                </thead>
                <tbody id="doc">

                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;text-align:right;">
            <a href="#" id="approve_url" redirect-href="#" onclick="return validateCertificateName()" class="btn btn-primary">Push To CRM</a>        
        </div>
        <br/>
    </div>
    <input type="hidden" id="app_id" value=""/>
</div>
<style type="text/css">
    #app_diff{
        max-height: 600;
        height: 600px;
        overflow-y: auto;
        padding: 10px;
        overflow-x: hidden;


    }
    #app_diff table tr { height: 0px } 
    .disable-link{
        pointer-events: none;
        cursor: default;
    }
    th,td{padding: 0.8em !important;}
</style>
<script type="text/javascript">
    // Declare crm keys

    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

    jQuery(document).ready(function () {

        jQuery('#tbl-app-data').dataTable({
            "bPaginate": false,
            "processing": true,
            "pageLength": 1000,
            "bFilter": false,
            "rowHeight": '5',
            "bInfo": false,
            retrieve: true,
            "bSort": false
                    // "aoColumns": [{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
        });

        jQuery('#tbl-app-doc').dataTable({
            "bPaginate": false,
            "pageLength": 1000,
            "processing": true,
            "bFilter": false,
            "rowHeight": '5',
            "bInfo": false,
            retrieve: true,
            "bSort": false
                    //"aoColumns": [{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
        });

        jQuery('body').on("click", ".approveLink", function (e) {
            showCertificateSpinner();// Add spinner
        });

        jQuery('#submit-button').click(function () {
        	//showCertificateSpinner();// Add spinner
        });
        $(window).resize(function() {
        	jQuery(".spinner-wp").width($('body').width());
            jQuery(".spinner-wp").height($('body').height());
            $(".spinner-wp > .spinner-bg").width($('.spinner-wp').width());
            $(".spinner-wp > .spinner-bg").height($('.spinner-wp').height());
        });
    });



    var is_certificate_found = false; //Used to check if certificate name already exists or not
//Function is used to get application log differ
    function getAppDiff(app_id, req_type, company_name, approve_link, certificate_name, redirect_link) {
        var output = '';
        var data = {
            action: 'get_application_diff',
            app_id: app_id,
            req_type: req_type
        };
        jQuery('#app_id').val(app_id);
        jQuery('#company_name').html(company_name);
        jQuery('#approve_url').attr('href', approve_link);
        jQuery('#approve_url').attr('redirect-href', redirect_link);
        // Check for certification name
        if (certificate_name == '') {
            is_certificate_found = false;
            jQuery('#certificate_name').val('');
            jQuery('#certificate_name').removeAttr('readOnly');
        } else {
            is_certificate_found = true;
            jQuery('#certificate_name').val(certificate_name);
            jQuery('#certificate_name').attr('readOnly', 'readOnly');
        }

        jQuery.post(ajaxurl, data, function (response) {
            console.log(response)
            var log_obj = jQuery.parseJSON(response);
            // Set company name if save but page not refresh 
            if (jQuery.type(log_obj.certificate_name) != 'object') {
                jQuery('#certificate_name').val(log_obj.certificate_name);
            }
            if (log_obj.status == true && log_obj.diff_result != null) {

                var diff_result = log_obj.diff_result;
                //console.log(diff_result);
                jQuery.each(diff_result, function (key, value) {
                    var field;
                    var old_value;
                    var new_value;
                    if (value.field) {
                        field = value.field;
                        old_value = value.old_val;
                        new_value = value.new_val;
                    }
                    output += '<tr class="data_row">';

                    output += '<td>' + getCRMValueByKey(field) + '</td>';

                    if (jQuery.type(old_value) === 'object') {
                        output += '<td>';

                        jQuery.each(old_value, function (key1, value1) {
                            output += '<div>';
                            output += getCRMValueByKey(key1) + ' => ' + value1;
                            output += '</div>';
                        });
                        output += '</td>';
                    } else {
                        output += '<td>' + old_value + '</td>';
                    }
                    if (jQuery.type(new_value) === 'object') {
                        output += '<td>';
                        jQuery.each(new_value, function (key1, value1) {
                            output += '<div>';
                            output += getCRMValueByKey(key1) + ' => ' + value1;
                            output += '</div>';
                        });
                        output += '</td>';

                    } else {
                        output += '<td>' + new_value + '</td>';
                    }

                    output += '</tr>';
                });

                jQuery('#app_diff #data').html(output)
            } else {
                if (log_obj.diff_result == null) {
                    output += '<tr>';
                    output += '<td colspan="3">There is no change log to compare.</td>';
                    output += '</tr>';
                    //alert('No diffrence found.')
                } else {
                    output += '<tr>';
                    output += '<td colspan="3">' + log_obj.diff_result + '</td>';
                    output += '</tr>';

                }

                jQuery('#app_diff #data').html(output)

            }

        });

        return false;
    }
//Function is used to get application document log
    function getAppDocDiff(app_id, req_type) {
        var output = '';
        var data = {
            action: 'get_application_diff',
            app_id: app_id,
            req_type: req_type
        };

        jQuery.post(ajaxurl, data, function (response) {
            console.log(response)
            //  return ;
            var log_obj = jQuery.parseJSON(response);

            if (log_obj.status == true && log_obj.diff_result != null && log_obj.diff_result != '') {

                var diff_result = log_obj.diff_result;
                //console.log(diff_result);
                jQuery.each(diff_result, function (key, value) {
                    var category;
                    var doc_added = '';
                    var doc_removed = '';

                    category = key;
                    if (value.docs_added != undefined) {
                        doc_added = value.docs_added;
                    }
                    if (value.docs_remove != undefined) {
                        doc_removed = value.docs_remove;
                    }

                    output += '<tr class="data_row">';

                    output += '<td>' + category + '</td>';


                    if (jQuery.type(doc_added) === 'object') {
                        output += '<td>' + JSON.stringify(doc_added) + '</td>';
                    } else {
                        output += '<td>';
                        doc_added = doc_added.toString().split(',')
                        output += '<ul style="margin-left:10px;">';
                        jQuery.each(doc_added, function (key, value) {
                            output += '<li>';
                            output += value;
                            output += '</li>';
                        })
                        output += '</ul>';
                        // output += doc_added;
                        output += '</td>';
                    }
                    if (jQuery.type(doc_removed) === 'object') {
                        output += '<td>' + JSON.stringify(doc_removed) + '</td>';
                    } else {
                        output += '<td>';
                        doc_removed = doc_removed.toString().split(',')
                        output += '<ul style="margin-left:10px;">';
                        jQuery.each(doc_removed, function (key, value) {
                            output += '<li>';
                            output += value;
                            output += '</li>';
                        })
                        output += '</ul>';

                        output += '</td>';

                    }

                    output += '</tr>';
                });

                jQuery('#app_diff #doc').html(output)

            } else {
                if (log_obj.diff_result == null || log_obj.diff_result == '') {
                    output += '<tr>';
                    output += '<td colspan="3">There is no change log to compare</td>';
                    output += '</tr>';
                    //alert('No diffrence found.')
                } else {
                    output += '<tr>';
                    output += '<td colspan="3">' + log_obj.diff_result + '</td>';
                    output += '</tr>';

                }

                jQuery('#app_diff #doc').html(output)
            }

        });

        return false;
    }

    function validateCertificateName() {
        var app_data_count = jQuery('#app_diff #tbl-app-data .data_row').length;
        var app_doc_count = jQuery('#app_diff #tbl-app-doc_wrapper .data_row').length;
        var program_id = jQuery('#app_program_id').val();
        var app_id = jQuery('#app_id').val();
        var app_submit_url = jQuery('#approve_url').attr('href');
        var app_redirect_url = jQuery('#approve_url').attr('redirect-href');
        if (app_id) {
            var certificate_name = jQuery('#certificate_name').val();
            if (jQuery.trim(certificate_name)) {
                if (is_certificate_found) { // If certificate name is already exists no need to fire save request
                    if (app_data_count == 0 && app_doc_count == 0) {
                        alert("There is no log data to push.")
                        return false;
                    } else {
                    	showCertificateSpinner();// Add spinner
                        if ((typeof app_redirect_url != 'undefined') && (app_redirect_url != 'undefined')) {
                            application_push_to_crm(app_submit_url, app_redirect_url);
                        } else {
                            window.location.href = app_submit_url;
                        }
                        return true;
                    }
                } else {
                	showCertificateSpinner();// Add spinner
                    var data = {
                        action: 'save_certificate_name',
                        app_id: app_id,
                        certificate_name: certificate_name
                    };
                    jQuery.post(ajaxurl, data, function (response) {
                        var response_obj = jQuery.parseJSON(response);

                        if (response_obj.status == true) {
                            if (app_data_count == 0 && app_doc_count == 0) {
                                alert("There is no log data to push.")
                                return false;
                            } else {
                                if ((typeof app_redirect_url != 'undefined') && (app_redirect_url != 'undefined')) {
                                    application_push_to_crm(app_submit_url, app_redirect_url);
                                } else {
                                    window.location.href = app_submit_url;
                                }
                                return true;
                            }
                        } else {
                            alert(response_obj.msg);
                        }
                    })
                    return false;
                }

                return false;
            } else {
                alert('Please enter certificate name.');
                return false;
            }
            return false;
        } else {
            alert('Application id not found.')
            return false;
        }


    }

// Function is used to open save certification pop up box and set app id in hidde field
    function openCertificatePopUp(app_id, program_id, third_party_value, certificate_name) {
        var app_submit_url = jQuery('#approve-link-' + app_id).attr('data-href');
        var app_redirect_url = jQuery('#approve-link-' + app_id).attr('redirect-href');
        jQuery('#popup_certificate_name').val('');
        if (program_id == 7 || program_id == 10 || program_id == 2) {
            jQuery('#third_party_tr').show();
        } else {
            jQuery('#third_party_tr').hide();
        }
        if (certificate_name.trim() != '') {
            jQuery('#certificate_name_tr').hide();
            jQuery('#popup_certificate_name').val(certificate_name);
            jQuery('#submit-button').attr('onclick', "change_insepection_agency()");
            jQuery('#submit-button').attr('type', "button");
        } else
        {
            jQuery('#certificate_name_tr').show();

        }
        jQuery('#popup_app_id').val(app_id);
        jQuery('#popup_program_id').val(program_id);
        if (third_party_value != '') {
            jQuery('#popup_third_party_number').val(third_party_value);
        } else
        {
            jQuery('#popup_third_party_number').val('');
        }
    }
    /*function to change inspection agency*/
    function change_insepection_agency()
    {
        var third_party_number = jQuery('#popup_third_party_number').val();
        app_id = jQuery('#popup_app_id').val();
        var app_submit_url = jQuery('#approve-link-' + app_id).attr('data-href');
        var app_redirect_url = jQuery('#approve-link-' + app_id).attr('redirect-href');
        if (jQuery.trim(third_party_number)) {
        	showCertificateSpinner();// Add spinner
            jQuery('#inspection_agency_hidden').val(third_party_number);
            jQuery.post(ajaxurl, {'action': 'change_third_party_number', 'inspection_agency_hidden': third_party_number, 'id': app_id}, function (result) {
                    application_push_to_crm(app_submit_url, app_redirect_url);
                    return false;
            });
        } else
        {
            alert('Please enter third party number.');
            return false;
        }

    }
    //Function is used to save certificate name
    function saveCertificatePopUpName(app_id) {
        //alert(1)
        app_id = jQuery('#popup_app_id').val();
        var certificate_name = jQuery('#popup_certificate_name').val();
        var app_submit_url = jQuery('#approve-link-' + app_id).attr('data-href');
        var app_redirect_url = jQuery('#approve-link-' + app_id).attr('redirect-href');
        var program_id = jQuery('#popup_program_id').val();
        if (jQuery.trim(app_id) && jQuery.trim(app_submit_url)) {
            if (jQuery.trim(certificate_name)) {
                var data = {
                    action: 'save_certificate_name',
                    app_id: app_id,
                    certificate_name: certificate_name
                };
                	showCertificateSpinner();// Add spinner
                    jQuery.post(ajaxurl, data, function (response) {
                        var response_obj = jQuery.parseJSON(response);

                        if (response_obj.status == true) {
                            if (program_id == 7 || program_id == 10 || program_id == 2) {
                            	hideCertificateSpinner();// Add spinner
                                change_insepection_agency();
                            }else{
	                            application_push_to_crm(app_submit_url, app_redirect_url);
	                            //window.location.href = app_submit_url;
                        	}
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
    function showCertificateSpinner() {
    	jQuery(".spinner-wp > .spinner-bg").width(jQuery('.spinner-wp').width());
        jQuery(".spinner-wp > .spinner-bg").height(jQuery('.spinner-wp').height());
        jQuery(".spinner-wp > .spinner-bg").css('display', 'block');// Add spinner
        jQuery(".spinner-wp").css('display', 'block');// Add spinner
    }
    function hideCertificateSpinner() {
    	jQuery(".spinner-wp > .spinner-bg").width('0px');
        jQuery(".spinner-wp > .spinner-bg").height('0px');
    	jQuery(".spinner-wp > .spinner-bg").css('display', 'none');
        jQuery(".spinner-wp").css('display', 'none');// Hide spinner
    }
</script>
