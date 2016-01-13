<div style="display:none">
    <div id="application__form_data_popup" style="padding: 10px;">
        <h1  class="entry-title post-title">
            Application Data
        </h1>
        <div class="application-portal-data">
            <table id="tbl-application-portal-data" class="table-striped table-hover">
                <thead>
                    <tr style="font-weight:bold">
                        <th style="text-align:center;">Field</th>
                        <th style="text-align:center;">Value</th>

                    </tr>
                </thead>
                <tbody id="data">
                    
                </tbody>
            </table>
           
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {

        

       // getApplicationFormData('MQ==');
    })
    function getApplicationFormData(app_id) {
        var output = '';
        var data = {
            action: 'get_application_portal_data',
            app_id: app_id,
            //req_type: req_type
        };

        jQuery.post(ajaxurl, data, function (response) {
            var log_obj = jQuery.parseJSON(response);

            if (log_obj.status == true) {

                var portal_data = log_obj.portal_data;
                portal_data = jQuery.parseJSON(portal_data)
                console.log(portal_data)
                jQuery.each(portal_data, function (key, value) {

                    output += '<tr class="data_row">';
                    output += '<td>';
                    output += getCRMValueByKey(key);
                    output += '</td>';
                    output += '<td>';
                    output += value;
                    output += '</td>';
                    output += '</tr>';

                });

               // jQuery('#tbl-application-portal-data #data').html(output)
                // get portal doc data
                var portal_doc = log_obj.portal_doc;
                portal_doc = jQuery.parseJSON(portal_doc)
                console.log(portal_doc)
                jQuery.each(portal_doc, function (key, value) {

                    output += '<tr class="data_row">';
                    output += '<td>';
                    output += getCRMValueByKey(key);
                    output += '</td>';
                    output += '<td>';
                    output += "<a download href='<?php echo wp_upload_dir()['baseurl']; ?>/"+value+"'>"+value+"</a>";
                    output += '</td>';
                    output += '</tr>';

                });

                jQuery('#tbl-application-portal-data #data').html(output);
                
                jQuery('#tbl-application-portal-data').dataTable({
            "bPaginate": true,
            "pagingType": "full",
            "pageLength": 10,
            "bFilter": false,
                });

            }
        });

        return false;
    }
</script>