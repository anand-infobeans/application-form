//
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
if(getCookie('application_filter')=='')
{
    setCookie('application_filter','all',1);
}
// }else
// {
//     ajax_object.status = getCookie('application_filter');
// }

if(getCookie('program_filter')=='')
{
    setCookie('program_filter','all',1);
}else
{
    programFilter = getCookie('program_filter');
}
if(getCookie('totalApplicationRecord')=='')
{
    setCookie('totalApplicationRecord',10,1);
}
$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'POST' // Ajax HTTP method
    }, opts );

    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;

    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;

        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }

        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );

        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));

                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }

            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);

            request.start = requestStart;
            request.length = requestLength*conf.pages;

            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }

            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    json.data.splice( requestLength, json.data.length );

                    drawCallback( json );
                    hideApplicationSpinner();
                },
                "async":false
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );

            drawCallback(json);
            hideApplicationSpinner();
        }
    }
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );


//
// DataTables initialisation
//
$(document).ready(function() {
 //var sel_box = '<div class="select-box-wp-2"><span class="select-value">All Application</span><select class="form-control input-xm" id="new_applicationstatus" name="new_applicationstatus" >'+ajax_object.opt+'</select></div>';


    var oTable = $('#applicationTable').dataTable( {
        "responsive": false,
        "processing": true,
        "serverSide": true,
        "columns":[
       {"width": "8%"},
       {"width": "9%"},
       //{"width": "11%"},
       {"width": "10%"},
       {"width": "8%"},
       {"width": "8%"},
       {"width": "10%"},
       {"width": "8%"},
       //{"width": "9%"},
       //{"width": "14%"},
       //{"width": "8%"},
       {"width": "7%"},
        ],
        "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 3,7,5 ] }
        ],
        "order": [[ 0, "desc" ]],
        //"scrollX": true,
        //"searching": false,
        "bSort": true,
        "iDisplayLength": getCookie('totalApplicationRecord'),
        //"bLengthChange": false,
        //"sDom": '<"H"lfr><"#status_container">t<"F"ip>',
        "sDom": '<"head-controls"l<"#status_container"><"#my-programs">f>t<"foot-controls"ip>',
        "ajax": $.fn.dataTable.pipeline( {
            url: ajax_object.ajax_url,
            data:{'action': 'crm_application','role': ajax_object.uesr_role,'type':getCookie('application_filter'),'program_name':getCookie('program_filter')},
            pages: 10 // number of pages to cache
        } )
        ,
        "fnInitComplete": function(oSettings, json) {
            //var test = JSON.parse(json);
            var select = '';
            var table = $('#applicationTable').DataTable();
            $( "#applicationTable" ).before('<div class="spinner-bg" style="display:none;"><img class="ajax-loader" src="'+loaderurl+'"></div>' );
            $("#applicationTable_wrapper > .spinner-bg").width(jQuery('#applicationTable').width());
            $("#applicationTable_wrapper > .spinner-bg").height(jQuery('#applicationTable').height());
            $("#applicationTable tfoot th").each( function ( i ) {
                select = $('<select name="my_programs_filter" class="my_programs_filter"></select>')
                .appendTo( this )
                .on( 'change', function () {
                	showApplicationSpinner();
                    table.column( i )
                        .search( $(this).val() )
                        .draw();
                    hideApplicationSpinner();
                } );
                //table.column( i ).data().unique().sort().each( function ( d, j ) {
                    select.append( filter );
                //} );
            } );
            if( json.recordsTotal == 0 ) {
            	$('#applicationTable_filter').hide();
            }
            hideApplicationSpinner();
            // if( json.recordsTotal > 0 ) {
            	sel_box = '<select class="select-filter input-xm" id="new_applicationstatus" name="new_applicationstatus" >'+ajax_object.opt+'</select></div>';
            	$('#my-programs').html("<select id='changeprogram' class='select-filter input-xm'><option value='all'>All Programs</option>"+filter+"</select>");
            // }
            
            $("#applicationTable tfoot th").attr('colspan',"2");
            $("#applicationTable tfoot th").css('float',"left");
            $('#status_container select').val(ajax_object.status);
        },
        "fnDrawCallback" : function(oSettings)
        {
            jQuery(".certificate_url").hover(function () {
                jQuery(this).append('<div class="tooltip-url">Click to download</div>');
            }, function () {
                jQuery("div.tooltip-url").remove();
            });
        }
    } );
    
    $('#applicationTable_length select').change(function(){
        setCookie('totalApplicationRecord',this.value,1);
    });
    var table = $('#applicationTable').DataTable();
    $(window).resize(function() {
        $("#applicationTable_wrapper > .spinner-bg").width($('#applicationTable').width());
        $("#applicationTable_wrapper > .spinner-bg").height($('#applicationTable').height());
    });
    $('#changeprogram').val(getCookie('program_filter'));
	 if($('#changeprogram').length > 0) {
    $('#changeprogram').on( 'change', function () {
      setCookie('program_filter',this.value,1);
    	showApplicationSpinner();
        table.column(4)
            .search( getCookie('program_filter') )
            .draw();
        hideApplicationSpinner();
            $('#my-programs .select-value').text($("#my-programs option:selected").text());
        //$('#my-programs').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html($("#my-programs option:selected").text());
    } );
   
    /* Add event listener to the dropdown input */
    $("div#status_container").html(sel_box);
    //$('#new_applicationstatus').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html($("#new_applicationstatus option:selected").text());
    
    
    $('div.dataTables_filter input.form-control.input-sm').attr("placeholder", "Enter application name");
    var ty = ajax_object.type;
    if (typeof ajax_object.type == 'string'){
        $('select#new_applicationstatus').val(ajax_object.type).change();
    }
   }
   $('#new_applicationstatus').val(getCookie('application_filter'));
   $('select#new_applicationstatus').change( function() {
      setCookie('application_filter',this.value,1);
      showApplicationSpinner();

        table.column(1)
            .search( getCookie('application_filter') )
            .draw();
        hideApplicationSpinner();
    });
    //alert(ajax_object.type);
} );

        function showLinks(program_id) {
           // var program_id = $('#profiles' ).attr( 'prog-id' );
            //alert(program_id);
            jQuery('#file_links'+program_id).css('display','block');
        }

        function removeLinksOther() {
            jQuery('.dropdown-menu').hide();
        }

    $(".tdCls").hover(function(){
        //alert(this.id);
        jQuery('.dropdown-menu').hide();
    });

    $(".download_progfiles").hover(function(){
        $('.dropdown-menu').hide();
        $('#file_links'+$(this).attr('prog-id')).show();
    });
    function showApplicationSpinner() {
    	jQuery("#applicationTable_wrapper > .spinner-bg").css('display', 'block');// Add spinner
        jQuery("#applicationTable_wrapper > .spinner-bg").width(jQuery('#applicationTable').width());
        jQuery("#applicationTable_wrapper > .spinner-bg").height(jQuery('#applicationTable').height());
    }
    function hideApplicationSpinner() {
    	jQuery("#applicationTable_wrapper > .spinner-bg").css('display', 'none');
    	jQuery("#applicationTable_wrapper > .spinner-bg").width('0px');
        jQuery("#applicationTable_wrapper > .spinner-bg").height('0px');
    }
    /*.mousemove (function(){
        var program_id = $('.download_progfiles' ).attr( 'prog-id' );
        jQuery("#file_links"+program_id).css('display','block');
    } )*/
   //  $(".download_progfiles").hover(function(){
   //          var program_id = $(this ).attr( 'prog-id' );
   //         // var program_id =  $('#program_id').val();
   //          var program_name =  $('#program_name'+program_id).text();
   //          var ajax_url = $('.download_progfiles').attr('ajaxUrl');
   //          var uploads_url = $('.download_progfiles').attr('uploadsUrl');
   //          jQuery("#file_links"+program_id).css('display','block');
   //          if(program_id!=''){
   //           var data = {

   //          'action': "countprogramfiles",
   //          'programId': program_id
   //          };
   //          // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
   //          $.post(ajax_url , data, function(response) {
   //              var filescount = jQuery.parseJSON(response);
   //              var total =  filescount['total'];
   //              if(total!=0 && total!='' && typeof total !== 'undefined'){
   //                          var data = {
   //                          'action': 'filesdetails',
   //                          'programId': program_id
   //                          //'programName': program_name.trim()
   //                          };

   //                      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
   //                      $.post(ajax_url , data, function(response) {
   //                          var filesdetails = jQuery.parseJSON(response);
   //                              var siteUrl = $('.download_progfiles').attr('siteUrl');
   //                              if(program_id!='' && program_name!=''){

   //                             // jQuery('#download_now').attr('href',siteUrl+'/download-zip.php?programId='+program_id+"&programName="+program_name.trim());
   //                             if(total!='1'){
   //                              var downAsZip = siteUrl+'/download-zip.php?programId='+program_id+"&programName="+program_name.trim();
   //                              var file_path = '<li><a class="link" onclick="removeLinksOther()" style="margin-top:-5px;" download href="'+downAsZip+'">Download all files</a></li>';
   //                              }else{
   //                                  file_path='';
   //                              }

   //                              var files;
   //                              $("#file_links"+program_id).html(file_path);
   //                               for (var i = 0; i < total; i++) {

   //                                  files =  uploads_url+'/'+filesdetails[i].meta_value;
   //                                  file = '<li><a class="link" onclick="removeLinksOther()" download href="'+files+'">'+filesdetails[i].post_title+'</a></li>';
   //                                  $("#file_links"+program_id).append(file);
   //                                  //alert(file);
   //                              };
   //                              jQuery("#file_links"+program_id).css('display','block');
   //                              jQuery('.dropdown-menu').not('#file_links'+program_id).hide();
   //                              //jQuery(this).siblings().hide();
   //                              }else{
   //                                  jQuery('.download_progfiles').attr('href','javascript:void(0);');
   //                                  jQuery('.download_progfiles').attr("title","");
   //                                  $("#file_links"+program_id).html('');
   //                                  jQuery("#file_links"+program_id).css('display','none');
   //                              }
   //                      });
   //                  }else{
   //                              jQuery('.download_progfiles').attr('href','javascript:void(0);');
   //                              jQuery('.download_progfiles').attr("title","");
   //                             $("#file_links"+program_id).html('');
   //                               jQuery("#file_links"+program_id).css('display','none');

   //                          }
   //          });
   //     }else{
   //                              jQuery('.download_progfiles').attr('href','javascript:void(0);');
   //                              jQuery('.download_progfiles').attr("title","");
   //                              $("#file_links"+program_id).html('');
   //                              jQuery("#file_links"+program_id).css('display','none');

   //                          }

   // }/*,function(){
   //      var program_id = $(this ).attr( 'prog-id' );
   //      jQuery('#file_links'+program_id).css('display','none');
   // }*/
   // );


