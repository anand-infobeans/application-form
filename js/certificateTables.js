// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
if(getCookie('totalCertificateRecord')=='')
{
    setCookie('totalCertificateRecord',10,1);
}
$.fn.dataTable.pipeline = function (opts) {
    // Configuration options
    var conf = $.extend({
        pages: 5, // number of pages to cache
        url: '', // script url
        data: null, // function or object with parameters to send to the server
        // matching how `ajax.data` works in DataTables
        method: 'POST' // Ajax HTTP method
    }, opts);

    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;

    return function (request, drawCallback, settings) {
        var ajax = false;
        var requestStart = request.start;
        var drawStart = request.start;
        var requestLength = request.length;
        var requestEnd = requestStart + requestLength;

        if (settings.clearCache) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }

        // Store the request for checking next time around
        cacheLastRequest = $.extend(true, {}, request);

        if (ajax) {
            // Need data from the server
            if (requestStart < cacheLower) {
                requestStart = requestStart - (requestLength * (conf.pages - 1));

                if (requestStart < 0) {
                    requestStart = 0;
                }
            }

            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);

            request.start = requestStart;
            request.length = requestLength * conf.pages;

            // Provide the same `data` options as DataTables.
            if ($.isFunction(conf.data)) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data(request);
                if (d) {
                    $.extend(request, d);
                }
            }
            else if ($.isPlainObject(conf.data)) {
                // As an object, the data given extends the default
                $.extend(request, conf.data);
            }

            settings.jqXHR = $.ajax({
                "type": conf.method,
                "url": conf.url,
                "data": request,
                "dataType": "json",
                "cache": false,
                "success": function (json) {
                    cacheLastJson = $.extend(true, {}, json);

                    selectstr = '<option value="">Select Scope Certificate</option>';
                    for(i=0;i<json.data.length;i++)
                    {
                        selectstr += "<option value='"+json.data[i]["id"]+"'>"+json.data[i][1]+"</option>";
                    }
                    jQuery("#scopecertificate").html(selectstr);
                    jQuery(".spinner-wp").css('display', 'none');
                    if (cacheLower != drawStart) {
                        json.data.splice(0, drawStart - cacheLower);
                    }
                    json.data.splice(requestLength, json.data.length);

                    drawCallback(json);
                },
                "async": false
            });
        }
        else {
            json = $.extend(true, {}, cacheLastJson);
            json.draw = request.draw; // Update the echo for each response
            json.data.splice(0, requestStart - cacheLower);
            json.data.splice(requestLength, json.data.length);

            drawCallback(json);
        }
    }
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register('clearPipeline()', function () {
    return this.iterator('table', function (settings) {
        settings.clearCache = true;
    });
});


//
// DataTables initialisation
//
$(document).ready(function () {

    $(".download_progfiles").hover(function(){
        $('.dropdown-menu').hide();
        $('#file_links'+$(this).attr('prog-id')).show();
    });
    function format(d) {
        var certificate_crm_id = d.certificate_crm_id;
        var output = '<table cellpadding="3" cellspacing="3" border="0" class="certificate-app-list wp-list-table  table table-striped table-hover dataTable no-footer dtr-inline" style="padding-left:10px;margin:0;">';
        output += '<tr>';
        output += '<th>Application Id</th>';
        output += '<th>Application Name</th>';
        output += '<th>Program Name</th>';
        output += '<th>Company Name</th>';
        output += '<th>Status</th>';
        output += '<th>Action</th>';
        output += '</tr>';
        if (certificate_crm_id) {
            var data = {
                action: 'get_application_by_certificate_crm_id',
                id: certificate_crm_id
            };

            jQuery.ajax({
                url: ajax_object.ajax_url,
                async: false,
                data: data,
                type: 'post',
                success: function (response) {
                    var application_data = jQuery.parseJSON(response);
                    //console.log(application_data )
                    $.each(application_data, function (key, value) {
                        //alert(value['app_id']);
                        output += '<tr>';
                        output += '<td>' + value['app_id'] + '</td>';
                        var app_name = value['app_name'];
                        if (app_name == undefined) {
                            app_name = 'Not Available';
                        }
                        output += '<td>' + app_name + '</td>';
                        output += '<td>' + value['program_name'] + '</td>';
                        output += '<td>' + value['company'] + '</td>';
                        output += '<td>' + value['status'] + '</td>';
                        output += '<td>' + value['act'] + '</td>';
                        output += '</tr>';

                    });
                }
            });
            output += '</table>';
            return output;

        }


    }
    // var sel_box = '<div class="select-box-wp-2"><span class="select-value">All Application</span><select class="form-control input-xm" id="new_applicationstatus" name="new_applicationstatus" >' + ajax_object.opt + '</select></div>';
    var oTable = $('#applicationTableFlow').dataTable({
        "processing": true,
        "serverSide": true,
        "columns": [
            {"className": 'details-control', "width": "5%"},
            //{"width": "11%"},
            {"width": "10%"},
            {"width": "8%"},
            {"width": "8%"},
            {"width": "7%"},
            {"width": "7%"},
        ],
        "iDisplayLength": getCookie('totalCertificateRecord'),
        //"scrollX": true,
        //"searching": false,
        "bSort": false,
        //"bLengthChange": false,
        //"sDom": '<"H"lfr><"#status_container">t<"F"ip>',
        "sDom": '<"head-controls"l<"#status_container"><"#my-programs">f>t<"foot-controls"ip>',
        "ajax": $.fn.dataTable.pipeline({
            url: ajax_object.ajax_url,
            data: {'action': 'certificate_callback', 'role': ajax_object.uesr_role},
            pages: 10 // number of pages to cache
        }),
                /*,
                 "fnInitComplete": function(oSettings, json) {
                 var select = '';
                 var table = $('#applicationTableFlow').DataTable();
                 $("#applicationTableFlow tfoot th").each( function ( i ) {
                 select = $('<select name="my_programs_filter" class="my_programs_filter"><option value="">All Programs:</option></select>')
                 .appendTo( this )
                 .on( 'change', function () {
                 //jQuery(".spinner-wp").css('display', 'block');
                 table.column( i )
                 .search( $(this).val() )
                 .draw();
                 } );

                 //table.column( i ).data().unique().sort().each( function ( d, j ) {
                 select.append( filter );
                 //} );
                 } );
                 $('#my-programs').html("<div class='select-box-wp-2'><span class='select-value' style='color: rgb(85, 85, 85)'>All Programs</span><select id='changeprogram'><option value=''>All Programs:</option>"+filter+"</select></div>");
                 $("#applicationTableFlow tfoot th").attr('colspan',"2");
                 $("#applicationTableFlow tfoot th").css('float',"left");
                 $('#my-programs').css('margin-right','5px;');
                 }*/

			"fnInitComplete": function(oSettings, json) {
				if( json.recordsTotal > 0 ) {
					$('div.dataTables_filter input.form-control.input-sm').attr("placeholder", "Enter certificate name");
				} else {
					$('div.dataTables_filter').hide();
				}
            }
    });

    $('#applicationTableFlow tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            var app_data = format(row.data());

            row.child(app_data).show();
            tr.addClass('shown');
        }
    });


    var table = $('#applicationTableFlow').DataTable();

    $('#changeprogram').on('change', function () {
        jQuery(".spinner-wp").css('display', 'block');
        table.column(4)
                .search(this.value)
                .draw();
        $('#my-programs .select-value').text($("#my-programs option:selected").text());
        //$('#my-programs').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html($("#my-programs option:selected").text());
    });

    /* Add event listener to the dropdown input */
    // $("div#status_container").html(sel_box);
    $('#new_applicationstatus').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html($("#new_applicationstatus option:selected").text());
    $('select#new_applicationstatus').change(function () {
        jQuery('#new_applicationstatus').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#new_applicationstatus option:selected").text());
        a = $(this).val();
        oTable.fnFilter($(this).val(), 1);
    });
    //$('div.dataTables_filter input.form-control.input-sm').attr("placeholder", "Enter certificate name");
    var ty = ajax_object.type;
    if (typeof ajax_object.type == 'string') {
        $('select#new_applicationstatus').val(ajax_object.type).change();
    }
    $('#applicationTableFlow_length select').change(function(){
        setCookie('totalCertificateRecord',this.value,1);
    });
    //alert(ajax_object.type);
});



function showLinks(program_id) {
    // var program_id = $('#profiles' ).attr( 'prog-id' );
    //alert(program_id);
    jQuery('#file_links' + program_id).css('display', 'block');
}

function removeLinksOther() {
    jQuery('.dropdown-menu').hide();
}

$(".tdCls").hover(function () {
    //alert(this.id);
    jQuery('.dropdown-menu').hide();
});

/*.mousemove (function(){
 var program_id = $('.download_progfiles' ).attr( 'prog-id' );
 jQuery("#file_links"+program_id).css('display','block');
 } )*/
// $(".download_progfiles").hover(function () {
//     var program_id = $(this).attr('prog-id');
//     // var program_id =  $('#program_id').val();
//     var program_name = $('#program_name' + program_id).text();
//     var ajax_url = $('.download_progfiles').attr('ajaxUrl');
//     var uploads_url = $('.download_progfiles').attr('uploadsUrl');
//     if (program_id != '') {
//         var data = {
//             'action': "countprogramfiles",
//             'programId': program_id
//         };
//         // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
//         $.post(ajax_url, data, function (response) {
//             var filescount = jQuery.parseJSON(response);
//             var total = filescount['total'];
//             if (total != 0 && total != '' && typeof total !== 'undefined') {
//                 var data = {
//                     'action': 'filesdetails',
//                     'programId': program_id
//                             //'programName': program_name.trim()
//                 };

//                 // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
//                 $.post(ajax_url, data, function (response) {
//                     var filesdetails = jQuery.parseJSON(response);
//                     var siteUrl = $('.download_progfiles').attr('siteUrl');
//                     if (program_id != '' && program_name != '') {

//                         // jQuery('#download_now').attr('href',siteUrl+'/download-zip.php?programId='+program_id+"&programName="+program_name.trim());
//                         if (total != '1') {
//                             var downAsZip = siteUrl + '/download-zip.php?programId=' + program_id + "&programName=" + program_name.trim();
//                             var file_path = '<li><a class="link" onclick="removeLinksOther()" style="margin-top:-5px;" download href="' + downAsZip + '">Download all files</a></li>';
//                         } else {
//                             file_path = '';
//                         }

//                         var files;
//                         $("#file_links" + program_id).html(file_path);
//                         for (var i = 0; i < total; i++) {

//                             files = uploads_url + '/' + filesdetails[i].meta_value;
//                             file = '<li><a class="link" onclick="removeLinksOther()" download href="' + files + '">' + filesdetails[i].post_title + '</a></li>';
//                             $("#file_links" + program_id).append(file);
//                             //alert(file);
//                         }
//                         ;
//                         jQuery("#file_links" + program_id).css('display', 'block');
//                         jQuery('.dropdown-menu').not('#file_links' + program_id).hide();
//                         //jQuery(this).siblings().hide();
//                     } else {
//                         jQuery('.download_progfiles').attr('href', 'javascript:void(0);');
//                         jQuery('.download_progfiles').attr("title", "");
//                         $("#file_links" + program_id).html('');
//                         jQuery("#file_links" + program_id).css('display', 'none');
//                     }
//                 });
//             } else {
//                 jQuery('.download_progfiles').attr('href', 'javascript:void(0);');
//                 jQuery('.download_progfiles').attr("title", "");
//                 $("#file_links" + program_id).html('');
//                 jQuery("#file_links" + program_id).css('display', 'none');

//             }
//         });
//     } else {
//         jQuery('.download_progfiles').attr('href', 'javascript:void(0);');
//         jQuery('.download_progfiles').attr("title", "");
//         $("#file_links" + program_id).html('');
//         jQuery("#file_links" + program_id).css('display', 'none');

//     }

// }/*,function(){
//  var program_id = $(this ).attr( 'prog-id' );
//  jQuery('#file_links'+program_id).css('display','none');
//  }*/
// );




