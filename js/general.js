/*databable for pagination*/
if (jQuery('#example').length > 0) {
    jQuery(document).ready(function () {
        jQuery('#example').DataTable();

    });
}
jQuery(function () {
    jQuery('.hiderenewal_notification_additional').hide();
    jQuery('#recaptcha_privacy').hide();
});
function iconforAdditionReminder(obj) {

    jQuery("#salutaions").val('');
    jQuery("#noofdays").val('');
    jQuery('#renewal_notification_additional').toggle();
    if (obj.hasClass('glyphicon-minus-sign')) {
        obj.addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');
    } else {
        obj.addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');
    }
}
/*0787---8/5/2015-----Form submit on next button using ajax call*/
function nextbuttonsubmit() {
    var editid;
	document.frmapplication.action.value="application-form-on-nextbtn";
	//jQuery('#action').val("application-form-on-nextbtn");
    jQuery.ajax({
        type: "POST",
        url: admin_url + 'admin-post.php',
        data: jQuery('#application-form1').serialize(),
        success: function (result) {
            var appdoc_id;
            var result_array = result.split('_');
            if (result_array.length > 1) {
                jQuery(".editid").val(result_array[0].trim());
                //jQuery("#appdoc-"+result_array[1]).val(1);
                $("input[name=appdoc-ctg-" + result_array[1] + "]").val(1);

                editid = result_array[0];
            }
            else {
                jQuery(".editid").val((result).trim());
                editid = result;
            }
            if (result_array[1]) {
                appdoc_id = $("input[name=appdoc-ctg-" + result_array[1] + "]").attr('id');
                appdoc_id = appdoc_id.substr(appdoc_id.indexOf('-') + 1);
            }

            // Fill payment billing address with chosen billing contact from application
            update_billing_address();

            //showing scope document if any
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {"action": "retrieve_application_scope_documents", "app_id": editid, "category_id": result_array[1], "appdoc_id": appdoc_id},
                success: function (result) {
                    jQuery("#upload_app_filename_" + result_array[1]).html(result);
                }
            });

        }
    });
}

function validateTab(tab, validateRequired) {
    jQuery('.current').removeClass('error');
    var temp = true;
    var u = 0;
    var fromId = document.getElementById(tab);
    var app_id = jQuery(".editid").val();
    jQuery(fromId).find('[class^="js-vld-"]').each(function () {
        currentvalue = jQuery(this).val();
        isDisabled = jQuery(this).prop('disabled');
        /*input type text validation and required field*/

        if (validateRequired && jQuery(this).hasClass('js-vld-required')) {
            currentvalue = jQuery(this).val();
            currentid = this.id;
            var letters = /^[a-zA-Z0-9\s]*$/;
            if(currentid == 'option_content') {
                var letters = /[a-zA-Z._^%$#!~@,-]+/;
            }

            //if ((currentvalue) || (letters.test(currentvalue))) {
            //    jQuery(this).removeClass('error_msg_label');
            //    jQuery(this).removeAttr('title');
            //
            //}

            if (currentvalue == '' || !letters.test(currentvalue)) {
                //$("#"+$("#"+this.id).parents("section").attr('id').replace("p","t")).parent("li").removeClass("done current");
				if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                jQuery(this).addClass('pop-error');
                disp_err_message();
                temp = false;

            } else
            {
                jQuery(this).removeClass('pop-error');
            }
        }


        if (validateRequired && jQuery(this).hasClass('js-vld-add')) {
            currentvalue = jQuery(this).val();
            var letters = /^[a-zA-Z0-9\s,'-.]*$/;
            if ((currentvalue == '' || !letters.test(currentvalue)) && !isDisabled) {
                //$("#"+$("#"+this.id).parents("section").attr('id').replace("p","t")).parent("li").removeClass("done current");
                if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                jQuery(this).addClass('pop-error');
                disp_err_message();
                temp = false;
            } else
            {
                jQuery(this).removeClass('pop-error');
            }
        }
        /*select /drop down validation*/
        if (validateRequired && jQuery(this).hasClass('js-vld-select') && !isDisabled) {
            //currentvalue = jQuery(this).parent().find('.select-value').html();

            if (currentvalue.indexOf("Select") == 0 || currentvalue == '') {
                jQuery(this).parent().addClass('pop-error');
                disp_err_message();
                if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                temp = false;
            } else
            {
                jQuery(this).parent().removeClass('pop-error');
            }
        }
        /*select /drop down payment validation*/
        if (validateRequired && jQuery(this).hasClass('js-vld-pay-select') && !isDisabled &&  $('input[name=payment_mode]:radio:checked').val() == 'CRDT' && !$('#skip_payment').is(':checked')  ) {
            //currentvalue = jQuery(this).parent().find('.select-value').html();

            if (currentvalue.indexOf("Select") == 0 || currentvalue == '') {
                jQuery(this).parent().addClass('pop-error');
                disp_err_message();
                if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                temp = false;
            } else
            {
                jQuery(this).parent().removeClass('pop-error');
            }
        }
        /*radio/checkbox validation*/
        if (validateRequired && jQuery(this).hasClass('js-switch')) {
            //if (this.prop('checked')==false) {
            //    $(this).addClass('pop-error');
            //    tooltip_validation_message();
            //    temp = false;
            //}else
            //{
            //    $(this).removeClass('pop-error');
            //}
        }
        /*validation for email address*/
        if (validateRequired && jQuery(this).hasClass('js-vld-email')) {
            var email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            currentvalue = jQuery("#" + this.id).val();
            if (!email.test(currentvalue) || currentvalue == "")
            {
                jQuery(this).addClass('pop-error');
                if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                temp = false;
                disp_err_message();
            } else
            {
                jQuery(this).removeClass('pop-error');
            }

        }

        /*validation for year*/
        if (validateRequired && jQuery(this).hasClass('js-vld-year')) {
            var email = /^\d{4}$/;
            currentvalue = jQuery("#" + this.id).val();
            if (!email.test(currentvalue) || currentvalue == "")
            {
                jQuery(this).addClass('pop-error');
                if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                temp = false;
                disp_err_message();
            } else
            {
                jQuery(this).removeClass('pop-error');
            }

        }

        /*validation for nfip 6 digits*/
        if (validateRequired && jQuery(this).hasClass('js-vld-6-digits')) {
            var digits = /^\d{6}$/;
            currentvalue = jQuery("#" + this.id).val();
            if (!digits.test(currentvalue) && currentvalue != "")
            {
                jQuery(this).addClass('pop-error');
                if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                }
                temp = false;
                disp_err_message();
            } else
            {
                jQuery(this).removeClass('pop-error');
            }

        }

		/* validation for radio buttons */
		if (validateRequired && jQuery(this).hasClass('js-vld-radio') && !isDisabled) {
            if( $('#payment_mode_credit').is(':checked') == false && $('#payment_mode_offline').is(':checked') == false && !$('#skip_payment').is(':checked') ) {
            	$('.errors').css("display","block");
				$('#error_payment_mode').html("Please select payment option") ;
				temp = false;
            } else {
            	if ($('input[name=payment_mode]:radio:checked').val() == "offline"){

					if (!$('input[name=payment_method]:radio:checked').val() && !$('#skip_payment').is(':checked') ){
						$('.errors').css("display","block");
						$('#error_payment_method').html("Please select payment mode.") ;
						temp = false;
					} else {
						$('.errors').css("display","none");
					}
				}
            }
        }

        /*validation for telephone and fax address*/
        if (validateRequired && jQuery(this).hasClass('js-vld-number') && !isDisabled) {
        //Added by Anand

        //if (validateRequired && jQuery(this).hasClass('js-vld-terms')) {
        //    if (jQuery('#'+this.id).prop('checked') == false) {
        //
        //        //$("#"+$("#"+this.id).parents("section").attr('id').replace("p","t")).parent("li").removeClass("done current");
        //        //jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).parent("li").addClass("error");
        //        //alert('Please accept terms and condition');
        //        //tooltip_validation_message();
        //        $('.error-terms').show();
        //        temp = false;
        //    } else
        //    {
        //        $('.error-terms').hide();
        //    }
        //}value = jQuery("#" + this.id).val();
            if (this.id == 'telenumber' || this.id == 'jurisdiction_size' || this.id == 'billing_phone' || this.id == 'billing_zipcode') {
            	if( ( $("#billing_country option:selected").text().toLowerCase() == 'us' || $("#billing_country option:selected").text().toLowerCase() == 'united states' || $("#billing_country option:selected").text().toLowerCase() == 'united states of america' ) && this.id == 'billing_zipcode' ) {
            		var regexp = /^[0-9]{5}$/;
            	} else {
            		var regexp = (this.id=='telenumber') ? /^[\s()+-]*([0-9][\s()+-]*){6,20}$/: /^\d+$/;
            	}
                if (!regexp.test(currentvalue)) {
                    jQuery(this).addClass('pop-error');
                    disp_err_message();
                    if(jQuery("#" + this.id).parents("section").length > 0){
                    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                    }
                    temp = false;
                } else{
                    jQuery(this).removeClass('pop-error');
                }
            } else if( this.id == 'verification_card_number' && $('input[name=payment_mode]:radio:checked').val() == 'CRDT' && !$('#skip_payment').is(':checked') ) {
            	if( $('#card_type').val() == 'american_express' )
            		var regexp =/^[0-9]{4}$/;
            	else
            		var regexp =/^[0-9]{3}$/;
            	if ( currentvalue == '' || !regexp.test(currentvalue)) {
                    jQuery(this).addClass('pop-error');
                    disp_err_message();
                    $('.errors').css("display","block");
                    if(jQuery("#" + this.id).parents("section").length > 0){
                    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                    }
                    temp = false;
                } else
                {
                	//$('.errors').css("display","none");
                    jQuery(this).removeClass('pop-error');
                }
            } else if( this.id == 'card_number' && $('input[name=payment_mode]:radio:checked').val() == 'CRDT' && !$('#skip_payment').is(':checked') ) {
            	if( $('#card_type').val() == 'american_express' )
            		var card_number_pattern = /^[0-9]{14,15}$/;
            	else
					var card_number_pattern = /^[0-9]{16}$/;
				if( currentvalue == '' || !card_number_pattern.test(currentvalue) ) {
					jQuery(this).addClass('pop-error');
				    disp_err_message();
				    $('.errors').css("display","block");
				    if(jQuery("#" + this.id).parents("section").length > 0){
				    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
				    }
				    temp = false;
				} else {
					//$('.errors').css("display","none");
				    jQuery(this).removeClass('pop-error');
				}
          	}  else if( this.id == 'payment_billing_zipcode' && $('input[name=payment_mode]:radio:checked').val() == 'CRDT' && !$('#skip_payment').is(':checked') ) {
				var billing_zipcode_pattern = /^[0-9]{5}$/;

				if( currentvalue == '' || !billing_zipcode_pattern.test(currentvalue) ) {
					jQuery(this).addClass('pop-error');
				    disp_err_message();
				    $('.errors').css("display","block");
				    if(jQuery("#" + this.id).parents("section").length > 0){
				    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
				    }
				    temp = false;
				} else {
					//$('.errors').css("display","none");
				    jQuery(this).removeClass('pop-error');
				}
          	} else if( this.id == 'payment_billing_phone' && $('input[name=payment_mode]:radio:checked').val() == 'CRDT' && !$('#skip_payment').is(':checked') ) {
				var billing_phone_pattern = /^[0-9]{10,14}$/;

				if( currentvalue == '' || !billing_phone_pattern.test(currentvalue) ) {
					jQuery(this).addClass('pop-error');
				    disp_err_message();
				    $('.errors').css("display","block");
				    if(jQuery("#" + this.id).parents("section").length > 0){
				    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
				    }
				    temp = false;
				} else {
					//$('.errors').css("display","none");
				    jQuery(this).removeClass('pop-error');
				}
          	}
            else {
                if (isNaN(currentvalue) && currentvalue != "")
                {
                    jQuery(this).addClass('pop-error');
                    disp_err_message();
                    if(jQuery("#" + this.id).parents("section").length > 0){
                    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                    }
                    temp = false;
                } else
                {
                    jQuery(this).removeClass('pop-error');
                }
            }
            //if (this.id != "telenumber" || this.id != 'jurisdiction_size') {
            //    if (isNaN(currentvalue) && currentvalue == "")
            //    {
            //        jQuery(this).addClass('pop-error');
            //        jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
            //        temp = false;
            //        disp_err_message();
            //    } else
            //    {
            //        jQuery(this).removeClass('pop-error');
            //    }
            //} else
            //{alert(this.id);
            //    if (isNaN(currentvalue) || currentvalue == "")
            //    {
            //        jQuery(this).addClass('pop-error');
            //        jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
            //        temp = false;
            //    } else
            //    {
            //        jQuery(this).removeClass('pop-error');
            //    }
            //}

        }
        /*validationfor url */
        if (validateRequired && jQuery(this).hasClass('js-vld-url')) {
            var url = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.?]{0,1}/;
            if (currentvalue.indexOf(',') > -1) {
                valueArray = currentvalue.split(',');
            }

            if (typeof valueArray !== 'undefined' && valueArray.length > 0) {
                for(var r=0;r<valueArray.length;r++)
                {
                    if (!url.test(valueArray[r]) && valueArray[r] != "")
                    {

                        jQuery('#' + this.id).addClass('pop-error');
                        disp_err_message();
                        if(jQuery("#" + this.id).parents("section").length > 0){
                        	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                        }
                        temp = false;

                    } else
                    {
                        jQuery('#' + this.id).removeClass('pop-error');

                    }
                }

            }else{

                if (!url.test(currentvalue) && currentvalue != "")
                {
                    jQuery(this).addClass('pop-error');
                    if(jQuery("#" + this.id).parents("section").length > 0){
                    	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).closest("li").addClass("error");
                    }
                    temp = false;
                    disp_err_message();
                } else
                {
                    jQuery(this).removeClass('pop-error');
                }
            }

        }

        //Added by Nidhi
         if (validateRequired && jQuery(this).hasClass('js-vld-upload')) {
            if (jQuery('#appdoc-' + u).val() == '0')
            {
                jQuery('.appdoc-' + u).html(jQuery('#error-text-' + u).val());
                jQuery('.appdoc-' + u).show();

				if(jQuery("#" + this.id).parents("section").length > 0){
                	jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).parent("li").addClass("error");
                }
                temp = false;

            } else
            {
				jQuery('.appdoc-' + u).html();
                $('.appdoc-' + u).hide();
            }
            u++;

        }
        //Added by Anand

        //if (validateRequired && jQuery(this).hasClass('js-vld-terms')) {
        //    if (jQuery('#'+this.id).prop('checked') == false) {
        //
        //        //$("#"+$("#"+this.id).parents("section").attr('id').replace("p","t")).parent("li").removeClass("done current");
        //        //jQuery("#" + jQuery("#" + this.id).parents("section").attr('id').replace("p", "t")).parent("li").addClass("error");
        //        //alert('Please accept terms and condition');
        //        //tooltip_validation_message();
        //        $('.error-terms').show();
        //        temp = false;
        //    } else
        //    {
        //        $('.error-terms').hide();
        //    }
        //}
    });
    return temp;
}

/*function validatePaymentForm(){
	if(jQuery('#payment_mode_credit').prop('checked')){
		return validateTab('credit_details', true);
	}else{
		return validateTab('offline_details', true)
	}
}*/

function tooltip_validation_message() {
    disp_err_message();

}
function change_state(country_id, label, edit) {
    jQuery.post(ajaxurl, {'action': 'get_state', 'id': country_id, 'edit': edit}, function (result) {
        jQuery('#' + label).html(result);
    });
}
function change_city(state_id, label, edit) {
    jQuery.post(ajaxurl, {'action': 'get_city', 'id': state_id, 'edit': edit}, function (result) {
        jQuery('#' + label).html(result);
    });
}

function changeformview() {
    var currentId = jQuery('#selectid').val();
    //currentId = (currentId=='select')?'new':currentId;
    document.location.href = '?&page=application-form-register&view=' + currentId + '&paged=1';
}
function getcrmid() {
    var currentId = jQuery('#selectid').val();
    document.location.href = window.location + '&crmid=' + currentId + '&paged=1';
}


/*application form tab slider js*/

jQuery(function ()
{
    /*change button text on edit and view mode*/
    if (typeof view != 'undefined') {
        if (staff) {
            next_label = "Next";
            finish_label = "Push to CRM";
        } else if (view) {
            next_label = "Next";
            finish_label = "Submit";
        } else
        {
            if(typeof payment_detail == 'undefined')
            {
                payment_detail=0;
            }
            if(payment_detail)
            {
                finish_label = "Pay";
            }else
            {
                finish_label = "Submit";
            }
            next_label = "Save & Next";

        }

    } else
    {
        next_label = "Save & Next";
        finish_label = "Submit";
    }


    //if (customer || ) {
    //    showfinish = false;
    //}else
    //{
    //    showfinish = true;
    //}
    if (typeof wizard != 'undefined') {
        jQuery("#wizard").steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            stepsOrientation: "vertical",
            enableAllSteps: true,
            //showFinishButtonAlways: showfinish,
            labels: {
                next: next_label,
                finish: finish_label,
            },
            titleTemplate: '<span class="number">#index#</span> #title#',
            onStepChanging: function (event, currentIndex, newIndex) {
                temp = true;

                /*when staff login and it is open in edit mode*/
                if (staff && !view) {
                    nextbuttonsubmit();
                } else if (!view) { /*after each step save data*/
                    nextbuttonsubmit();
                }

                return temp;
            },
            onInit: function (event, currentIndex) {
                /*hide submit or approve to crm if login user is customer*/
                if (!staff && view) {
                    jQuery('.finish').hide();
                }
                /*if staff is login and status of application is either new or modified then push to crm button will shown*/

                if (staff && (jQuery('#status').val() == 'In Review')) {
                    jQuery('.finish').hide();
                }
                $('#tablist li a').each(function(){
                   //if($(this).text().replace(/(\d+)/g, '').trim()== 'payments' && $('#status').val() == 'Completed') {$(this).hide();}
                });

            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if($('#wizard').height()>$(window).height()){
                    $('#container-outer').css('min-height',$('#wizard').height());
                }else
                {
                    $('#container-outer').css('min-height',$(window).height());
                }
                /*change "save & next" to "save" when last tab is clicked*/
                if (currentIndex == (jQuery('#tablist li').length - 1) && !view)
                {
                    jQuery('#next').html('Save');
                } else {
                    jQuery('#next').html(next_label);
                }
                if (jQuery('#wizard-t-' + currentIndex).text().replace(/current step: (\d+)/g, '').trim() == 'payments') {

//                    if (typeof appid != 'undefined')
//                    {
//    get_invoice_detail_from_crm(appid);
//                    }
                    if ((typeof new_application_id != 'undefined') && (parseInt(new_application_id) > 0))
                    {
                        jQuery(".spinner-wp").css('display', 'block');
                        get_renewal_payment_option();
                    }
                    /*if ((typeof $("#another_billing_address")) && ($("#another_billing_address").is(':checked')))
                    {
                        jQuery(".spinner-wp").css('display', 'block');
                        update_billing_address();
                    }*/
                }
                innertemp = 0;
                $('#wizard-p-'+priorIndex+' .errors').each(function(){
                    if($(this).text()!='')
                    {
                        innertemp=1;
                    }
                });
                if (innertemp) {
                    $('#wizard-t-'+priorIndex).closest("li").addClass('error');
                }
                return true;
            },
            onFinishing: function (event, currentIndex) {
                temp = false;
				document.frmapplication.action.value="application-form";

                /*after finishing check all errors*/
                if (!view && !staff) {
                    temp = validateTab('application-form1', true);
                    if (temp == true && !staff) {
                        if(jQuery('#status').val()=='Draft' || jQuery('#status').val()==''){
                        jQuery.colorbox({iframe: true, href: jQuery('#terms-condition-popup').attr('href'), innerWidth: 700, innerHeight: 680, overlayClose: false, scrolling: false, speed: 200});
                        temp = false;
                        }
                    }
                }
                if (temp == true && !staff) {
					document.frmapplication.target="";
                    if(show_payment_tab){
                    jQuery(".spinner-wp").css('display', 'block');
                    jQuery.post(ajaxurl, $('#application-form1').serialize()+'&fromajax=1' , function (result) {
                        if(result.trim()!='' && result.trim()!=0)
                        {
                            temp=0;
                            try {
                                JSON.parse(result);
                                $('#chaseresponse').val(result);
                                temp=1;
                            } catch (e) {
                                result = result.replace(/\d+/g, '');
                                result = result.replace('checksum', '');
                                $('body').scrollTop(0);
                                jQuery(".spinner-wp").css('display', 'none');
                                $('.post-entry').show();
                                $('.post-entry').html('');
                                $('.post-entry').append('<div class="message error">'+result+'</div>');
                                $('.post-entry').delay(2000).fadeOut(500);
                                temp=0;
                            }
                            if(temp)
                            {
                                $('#application-form1').submit();
                            }
                        }else
                        {
                            $('#application-form1').submit();
                        }

                    });
                    }else
                    {
                    	jQuery(".spinner-wp").css('display', 'block');
                        $('#application-form1').submit();
                    }
                }
                /*put colorbox and certificate popup when application form status is new or modified*/
                if (staff && (jQuery('#status').val() == 'New' || jQuery('#status').val() == 'Modified')) {
                    if (jQuery('#popup_certificate_name').val() != "" && jQuery.inArray( parseInt($('#program_id').val()), [2, 7, 10] )==-1) {
                        change_insepection_agency();
                    } else
                    {
                        if ($('#inspection_agency_hidden').val() != '') {
                            jQuery('#popup_third_party_number').val($('#inspection_agency_hidden').val());
                        }
                        $('.finish').attr('class', 'colorbox-inline-application cboxElement finish');
                        $('.finish').attr('href', '#app_certification_popup');
                        jQuery('#submit-button').attr('onclick',"change_insepection_agency()");
                    }
                    //window.location.href = redirecturl;
                }
                return temp;
            },
        });
    }
});
function get_invoice_detail_from_crm(appid) {

    jQuery.post(ajaxurl, {'action': 'get_invoice_frm_crm', 'app_id': appid}, function (result) {
        if (result) {
            jQuery(".js-ajax-payment").html(result);
            jQuery(".spinner-wp").css('display', 'none');
        }

    });
}
function get_renewal_payment_option() {
    jQuery.post(ajaxurl, {'action': 'get_renewal_payment', 'newapplication_id': new_application_id}, function (result) {
        if (result) {
            jQuery(".js-ajax-payment").html(result);
            jQuery('.payment-total').show();
            jQuery(".spinner-wp").css('display', 'none');
            //For view mode set renewal payment details disabled
            if ((typeof view != 'undefined') && (view == true)) {
            	if(typeof jQuery("#one_year_total") != 'undefined') {
                	jQuery("#one_year_total").attr('disabled',true);
            	}
            	if(typeof jQuery("#two_year_total") != 'undefined') {
            		jQuery("#two_year_total").attr('disabled',true);
            	}
            	if(typeof jQuery("#three_year_total") != 'undefined') {
            		jQuery("#three_year_total").attr('disabled',true);
            	}
            }
        }

    });
}
jQuery(document).on('click', '.js-switch-payment', function () {
    if (jQuery(this).is(':checked')) {
        jQuery('.js-switch-payment_value').hide();
        jQuery('#' + this.id + '_value').show();

        if( jQuery('#chasePaymentAmount') ){
        	var application_payment_value = jQuery('#' + this.id + '_value').html();
        	jQuery('#chasePaymentAmount').val( application_payment_value.substr(2, application_payment_value.length  ) );
        }
    } else {
        jQuery('#' + this.id + '_value').hide();
    }
});
jQuery(document).on('click', '.js-quantity_option', function () {
        if (jQuery(this).is(':checked')) {
            jQuery("#" + this.id).val('Yes');
        } else {
            jQuery("#" + this.id).val('No');
        }
    });

/*application form tab slider js*/
jQuery(document).ready(function () {
    jQuery('.contact-number a').addClass('contact-number');
    jQuery('.news-icon a').attr('target','_blank');
    jQuery('#wizard ul li').each(function () {
        if (jQuery(this).attr('role') == 'tab') {
            if (!jQuery(this).hasClass("current")) {
                jQuery(this).addClass('done');
            }
        }

    });
    jQuery('.js-switch').click(function () {
        if (jQuery(this).is(':checked')) {
            jQuery("#" + this.id).addClass(this.id);
            jQuery("." + this.id).val('Yes');
        } else {
            jQuery("." + this.id).val('No');
        }
    });
    jQuery('.js-switch-mailing').click(function () {
        if (jQuery(this).is(':checked')) {
            jQuery("#" + this.id).addClass(this.id);
            jQuery("." + this.id).val('1');
             if(this.id == 'invoice-doc'){
            var invoice_doc = $('#invoice-doc').val();
            $('#invoice_checkbox').val(invoice_doc);
            }
        } else {
            jQuery("." + this.id).val('0');
             if(this.id == 'invoice-doc'){
            var invoice_doc = $('#invoice-doc').val();
            $('#invoice_checkbox').val(invoice_doc);
            }
        }
    });
    jQuery('#checktopopulatefield').click(function () {

        if (jQuery(this).is(':checked')) {

//             alert(jQuery("#facilitystreetadd").val());
            jQuery("#mailadd").val(jQuery("#facilitystreetadd").val());
            checkValidAdd(jQuery("#facilitystreetadd").val(), 'mailadd');
            jQuery("#mailcountry").val(jQuery("#facilitycountry").val());
            checkValidSelect(jQuery("#facilitycountry").val(), 'mailcountry');
            jQuery("#mailcountry").parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#mailcountry option:selected").text());
            jQuery("#mailstate").val(jQuery("#facilitystate").val());
            checkValidSelect(jQuery("#facilitystate").val(), 'mailstate');
            jQuery("#mailstate").parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#mailstate option:selected").text());
            jQuery("#mailcity").val(jQuery("#facilitycity").val());
            checkValidInput(jQuery("#facilitycity").val(), 'mailcity');
            jQuery("#mailzip").val(jQuery("#facilityzip").val());
            checkValidInput(jQuery("#facilityzip").val(), 'mailzip');
        } else {
            jQuery("#mailadd").val("");
            jQuery("#mailcountry").val("");
            jQuery("#mailcountry").parent().find('.select-value').html(jQuery("#mailcountry option:selected").text());
            jQuery("#mailstate").val("");
            jQuery("#mailstate").parent().find('.select-value').html(jQuery("#mailstate option:selected").text());
            jQuery("#mailcity").val("");
            jQuery("#mailzip").val("");
        }
    });
    $("#refresh_captcha").click(function () {

		jQuery.post(ajaxurl, {'action': 'refreshCaptcha'}, function (result) {
			refreshCaptcha( result );
	    });
    });
});

/*copy facility add to mailing*/
function copyFacilityToMailing()
{
    if (jQuery('#checktopopulatefield').is(':checked')) {

        jQuery("#mailadd").val(jQuery("#facilitystreetadd").val());
        checkValidInput(jQuery("#facilitystreetadd").val(), 'mailadd');
        jQuery("#mailcountry").val(jQuery("#facilitycountry").val());
        checkValidSelect(jQuery("#facilitycountry").val(), 'mailcountry');
        jQuery("#mailcountry").parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#mailcountry option:selected").text());
        jQuery("#mailstate").val(jQuery("#facilitystate").val());
        checkValidSelect(jQuery("#facilitystate").val(), 'mailstate');
        jQuery("#mailstate").parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#mailstate option:selected").text());
        jQuery("#mailcity").val(jQuery("#facilitycity").val());
        checkValidInput(jQuery("#facilitycity").val(), 'mailcity');
        jQuery("#mailzip").val(jQuery("#facilityzip").val());
        checkValidInput(jQuery("#facilityzip").val(), 'mailzip');
    }
}

/*remove class on blur*/
function checkValidInput(currentValue, currentId) {
    var letters = /^[a-zA-Z0-9\s]*$/;
    if(currentId=='option_content' && currentValue != '')
    {
        remove_err_message(jQuery("#" + currentId));
        jQuery("#" + currentId).removeClass('pop-error');
        return true;
    }else
    {
        if (currentValue == '' || !letters.test(currentValue)) {
            jQuery("#" + currentId).addClass('pop-error');
            disp_err_message();
            return false;
        } else
        {
            jQuery("#" + currentId).removeClass('pop-error');
            //jQuery('#'+currentId).parent().find('.errors').html('')
            remove_err_message(jQuery("#" + currentId));
            return true;
        }
    }

}

/*remove class on blur*/
function checkallowallcharacterInput(currentValue, currentId) {
    var letters = /[a-zA-Z._^%$#!~@,-]+/;

    if (currentValue == '' || !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        //jQuery('#'+currentId).parent().find('.errors').html('')
        remove_err_message(jQuery("#" + currentId));
        return true;
    }

}


//remove class on blur
function checkValidCvv(currentValue, currentId, card_type) {
	if(card_type == 'american_express')
		var letters =/^[0-9]{4}$/;
	else
    	var letters =/^[0-9]{3}$/;

    if (currentValue == '' || !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        //jQuery('#'+currentId).parent().find('.errors').html('')
        remove_err_message(jQuery("#" + currentId));
        return true;
    }

}

// remove class on blur event
function checkValidBillingPhone(currentValue, currentId) {
	var letters =/^[\s()+-]*([0-9][\s()+-]*){10,14}$/;

    if (currentValue == '' || !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        //jQuery('#'+currentId).parent().find('.errors').html('')
        remove_err_message(jQuery("#" + currentId));
        return true;
    }

}


// remove class on blur event
function checkValidBillingZip(currentValue, currentId, country_selected) {
	if( country_selected.toLowerCase() == 'us' || country_selected.toLowerCase() == 'united states' || country_selected.toLowerCase() == 'united states of america' ) {
		var letters =/^[0-9]{5}$/;
	} else {
		var letters = /^\d+$/;
	}

    if (currentValue == '' || !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        //jQuery('#'+currentId).parent().find('.errors').html('')
        remove_err_message(jQuery("#" + currentId));
        return true;
    }

}

function checkValidYear(currentValue, currentId) {
    var letters = /^\d{4}$/;

    if (currentValue == '' || !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        //jQuery('#'+currentId).parent().find('.errors').html('')
        remove_err_message(jQuery("#" + currentId));
        return true;
    }

}

function checkValidAdd(currentValue, currentId) {
    var letters = /^[a-zA-Z0-9\s,'-.]*$/;
    if (currentValue == '' || !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        remove_err_message(jQuery("#" + currentId));
        return true;

    }

}

function checkValid6Digit(currentValue, currentId) {
    var letters = /^\d{6}$/;
    if (currentValue != '' && !letters.test(currentValue)) {
        jQuery("#" + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery("#" + currentId).removeClass('pop-error');
        remove_err_message(jQuery("#" + currentId));
        return true;

    }

}

function checkValidSelect(currentValue, currentId) {
    if (currentValue != null) {
        if (!currentValue.indexOf("Select") || currentValue == '') {
            jQuery('#' + currentId).parent().addClass('pop-error');
            jQuery('#' + currentId).closest('.form-group').find('.errors').html(jQuery('#' + currentId).attr('title'));
            return false;
        } else
        {
            jQuery('#' + currentId).closest('.form-group').find('.errors').html('');
            jQuery('#' + currentId).parent().removeClass('pop-error');
            return true;
        }
    } else
    {
        jQuery('#' + currentId).parent().addClass('pop-error');
        return false;
    }
}

function checkValidCheckbox(currentId) {
    if (jQuery('#' + currentId).prop('checked') == false) {
        jQuery('.error-terms').show();
        return false;
    } else
    {
        jQuery('.error-terms').hide();
        return true;
    }
}

function checkValidEmail(currentValue, currentId) {
    var email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (!email.test(currentValue) || currentValue == "")
    {
        jQuery('#' + currentId).addClass('pop-error');
        disp_err_message();
        return false;
    } else
    {
        jQuery('#' + currentId).removeClass('pop-error');
        remove_err_message(jQuery("#" + currentId));
        return true;
    }
}

function checkValidNumber(currentvalue, currentId)
{
    if (currentId == 'telenumber' || currentId == 'jurisdiction_size') {
        var regexp = (currentId=='telenumber') ? /^[\s()+-]*([0-9][\s()+-]*){6,20}$/: /^\d+$/;
        if (!regexp.test(currentvalue)) {
            jQuery('#' + currentId).addClass('pop-error');
            disp_err_message();
            return false;
        } else
        {
            jQuery('#' + currentId).removeClass('pop-error');
            remove_err_message(jQuery('#' + currentId));
            return true;
        }
    }  else
    {
        if (isNaN(currentvalue) && currentvalue != "")
        {
            jQuery('#' + currentId).addClass('pop-error');
            disp_err_message();
            return false;
        } else
        {
            jQuery('#' + currentId).removeClass('pop-error');
            remove_err_message(jQuery('#' + currentId));
            return true;
        }
    }
}

function checkValidRadio(currentValue, currentId) {
    if (field_value != '') {
        jQuery("#" + field_id).removeClass('pop-error');
    }
}

function checkValidUrl(currentValue, currentId) {
    //var url = /^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$/i;
    var url = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.?]{0,1}/;
    if (currentValue.indexOf(',') > -1) {
        valueArray = currentValue.split(',');
    }

    if (typeof valueArray !== 'undefined' && valueArray.length > 0) {
        for(var u=0;u<valueArray.length;u++)
        {
            if (!url.test(valueArray[u]) && valueArray[u] != "")
            {

                jQuery('#' + currentId).addClass('pop-error');
                disp_err_message();
                return false;
            } else
            {
                jQuery('#' + currentId).removeClass('pop-error');
                remove_err_message(jQuery('#' + currentId));
                return true;
            }
        }

    }else
    {
        if (!url.test(currentValue) && currentValue != "")
        {
            jQuery('#' + currentId).addClass('pop-error');
            disp_err_message();
            return false;
        } else
        {
            jQuery('#' + currentId).removeClass('pop-error');
            remove_err_message(jQuery('#' + currentId));
            return true;
        }
    }
}

//Save Document
function upload_application_document(category_id, event, k,slug) {

    var temp;
	var app_files = document.getElementById("app_files_" + category_id);
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf('MSIE ');
	$(".error-upload").html(' ');
	var app_id = jQuery(".editid").val();
	  var css_id = k;

	if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) < 10) {

			var fileName = app_files.value;
			var iFileSize = app_files.size;
			var iConvert = (app_files.size / 1024).toFixed(2);
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			var ext = ext.toLowerCase();
			if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
				var appfileName = fileName.substr(fileName.lastIndexOf("\\")+1, fileName.length);
				$(".error-upload").append('<br>' + appfileName + ': File type is not supported');
				document.getElementById("app_files_" + category_id).parentNode.innerHTML = document.getElementById("app_files_" + category_id).parentNode.innerHTML;
			}
			else if (iConvert > 1024000) {
				$(".error-upload").append("<br>" + fileName + ':File size exceeded');
			}
			else {
				temp = 1;
			}

		if (temp == 1) {
			$('#loader-icon-' + category_id).show();
			//document.getElementById("application-form1").action = "admin-post.php";
			theForm = event.form;

			theForm.action.value = "save_application_document";
			theForm.category_id.value = category_id;
			theForm.appdoc_id.value = css_id;
			theForm.applid.value = app_id;

			$("#application_docs").unbind('load');
			$("#application_docs").bind('load', function() {
				// Show returned data using the function.
				jQuery("#upload_app_filename_" + category_id).html(jQuery("#application_docs").contents().find("body").html());
				$('#loader-icon-' + category_id).hide();
				jQuery("#is_doc_change").val(1);
                jQuery("#appdoc-" + css_id).val(1);
                jQuery(".appdoc-" + css_id).css("display", "none");
				jQuery(".appdoc-" + css_id).html("");
				document.getElementById("app_files_" + category_id).parentNode.innerHTML = document.getElementById("app_files_" + category_id).parentNode.innerHTML;
			});
			theForm.submit();
		}
	}
	else{
    var files = app_files.files;
    var data = new FormData();

    for (i = 0; i < files.length; i++) {

        var fileobject = files[i];
        var fileName = fileobject.name;
        var iFileSize = fileobject.size;
        var iConvert = (fileobject.size / 1024).toFixed(2);
        var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
        var ext = ext.toLowerCase();
        if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
            $(".error-upload").append("<br>" + fileName + ': File type is not supported');
        }
        else if (iConvert > 1024000) {
            $(".error-upload").append("<br>" + fileName + ':File size exceeded');
        }
        else {
            data.append('file' + i, files[i]);
            temp = 1;
        }

    }

    if (temp == 1) {

        data.append("action", "save_application_document");
        data.append("app_id", app_id);
        data.append("category_id", category_id);
		data.append("appdoc_id", css_id);

        $('#loader-icon-' + category_id).show();
        //save document
        jQuery.ajax({
            type: 'POST', // Adding Post method
            url: ajaxurl, // Including ajax file
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            target: '#targetLayer-' + category_id,
            beforeSubmit: function () {
                $("#progress-bar-" + category_id).width('0%');
            },
            uploadProgress: function (event, position, total, percentComplete) {
                $("#progress-bar-" + category_id).width(percentComplete + '%');
                $("#progress-bar-" + category_id).html('<div id="progress-status-' + category_id + '">' + percentComplete + ' %</div>')
            },
            success: function (result) {
                // Show returned data using the function.
                jQuery("#upload_app_filename_" + category_id).html(result);
                jQuery("#is_doc_change").val(1);
                $('#loader-icon-' + category_id).hide();
                jQuery("#appdoc-" + css_id).val(1);
                jQuery(".appdoc-" + css_id).css("display", "none");
				jQuery(".appdoc-" + css_id).html("");
                if(slug=='additional-option-document'){
                    $('#option_content').closest('.errors').html('');
                    $('#option_content').removeClass('js-vld-required');
                    $('.additional_content').hide();
                    $('.or_class').hide();
                    if($('#option_content').next('.errors').html()!=''){
                        $('#option_content').next('.errors').html('');$('.or_class').hide();
                    }
                    $('#option_content').prop("readonly",true);
                }
				if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) >= 10) {
					document.getElementById("app_files_" + category_id).parentNode.innerHTML = document.getElementById("app_files_" + category_id).parentNode.innerHTML;
				}
				else{
					event.value = null;
				}
            }
        });
    }
}
}
//Save Document
function upload_program_document(program_id, event) {
   var ua = window.navigator.userAgent;
	var msie = ua.indexOf('MSIE ');
	$(".error-upload").html(' ');
	var temp;
	var program_file = document.getElementById("app_files_" + program_id);

	if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) < 10) {

			var fileName = program_file.value;
			var iFileSize = program_file.size;
			var iConvert = (program_file.size / 1024).toFixed(2);
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			var ext = ext.toLowerCase();
			if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
				var programfileName = fileName.substr(fileName.lastIndexOf("\\")+1, fileName.length);
				$(".error-upload").append('<br>' + programfileName + ': File type is not supported');
				document.getElementById("app_files_" + program_id).parentNode.innerHTML = document.getElementById("app_files_" + program_id).parentNode.innerHTML;
			}
			else if (iConvert > 1024000) {
				$(".error-upload").append("<br>" + fileName + ':File size exceeded');
			}
			else {
				temp = 1;
			}

		if (temp == 1) {
			$('#loader-icon-' + program_id).show();
			document.frmviewprograms.action = "admin-post.php";
			document.frmviewprograms.program_id.value=program_id;

			$("#program_docs").unbind('load');
			$("#program_docs").bind('load', function() {
				jQuery("#upload_program_filename_" + program_id).html(jQuery("#program_docs").contents().find("body").html());
				$('#loader-icon-' + program_id).hide();
				document.getElementById("app_files_" + program_id).parentNode.innerHTML = document.getElementById("app_files_" + program_id).parentNode.innerHTML;
			});

			document.frmviewprograms.submit();
		}
	}
	else{

		var files = program_file.files;
		var data = new FormData();

		for (i = 0; i < files.length; i++) {

			var fileobject = files[i];
			var fileName = fileobject.name;
			var iFileSize = fileobject.size;
			var iConvert = (fileobject.size / 1024).toFixed(2);
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			var ext = ext.toLowerCase();
			if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
				$(".error-upload").append('<br>' + fileName + ': File type is not supported');
			}
			else if (iConvert > 1024000) {
				$(".error-upload").append("<br>" + fileName + ':File size exceeded');
			}
			else {
				data.append('file' + i, files[i]);
				temp = 1;
			}
		}
		if (temp == 1) {

			data.append("action", "save_program_document");
			data.append("program_id", program_id);

			$('#loader-icon-' + program_id).show();
			//save document
			jQuery.ajax({
				type: 'POST', // Adding Post method
				url: ajaxurl, // Including ajax file
				contentType: false,
				data: data,
				processData: false,
				cache: false,
				target: '#targetLayer-' + program_id,
				beforeSubmit: function () {
					$("#progress-bar-" + program_id).width('0%');
				},
				uploadProgress: function (event, position, total, percentComplete) {
					$("#progress-bar-" + program_id).width(percentComplete + '%');
					$("#progress-bar-" + program_id).html('<div id="progress-status-' + program_id + '">' + percentComplete + ' %</div>')
				},
				success: function (result) { // Show returned data using the function.
					jQuery("#upload_program_filename_" + program_id).html(result);
					$('#loader-icon-' + program_id).hide();
					if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) >= 10) {
						document.getElementById("app_files_" + program_id).parentNode.innerHTML = document.getElementById("app_files_" + program_id).parentNode.innerHTML;
					}
					else{
						event.value = null;
					}
				}
			});
		}
	}
}

//Save additonal Document
function upload_additional_program_document(program_id, event) {
   var ua = window.navigator.userAgent;
	var msie = ua.indexOf('MSIE ');
	$(".error-upload").html(' ');
	var temp;
	var program_file = document.getElementById("app_additional_files_" + program_id);

	if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) < 10) {

			var fileName = program_file.value;
			var iFileSize = program_file.size;
			var iConvert = (program_file.size / 1024).toFixed(2);
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			var ext = ext.toLowerCase();
			if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
				var programfileName = fileName.substr(fileName.lastIndexOf("\\")+1, fileName.length);
				$(".error-upload").append('<br>' + programfileName + ': File type is not supported');
				document.getElementById("app_additional_files_" + program_id).parentNode.innerHTML = document.getElementById("app_additional_files_" + program_id).parentNode.innerHTML;
			}
			else if (iConvert > 1024000) {
				$(".error-upload").append("<br>" + fileName + ':File size exceeded');
			}
			else {
				temp = 1;
			}

		if (temp == 1) {
			$('#additional-loader-icon-' + program_id).show();
			document.frmviewprograms.action = "admin-post.php";
			document.frmviewprograms.program_id.value=program_id;

			$("#program_docs").unbind('load');
			$("#program_docs").bind('load', function() {
				jQuery("#upload_additional_program_filename_" + program_id).html(jQuery("#program_docs").contents().find("body").html());
				$('#additional-loader-icon-' + program_id).hide();
				document.getElementById("app_additional_files_" + program_id).parentNode.innerHTML = document.getElementById("app_additional_files_" + program_id).parentNode.innerHTML;
			});

			document.frmviewprograms.submit();
		}
	}
	else{

		var files = program_file.files;
		var data = new FormData();

		for (i = 0; i < files.length; i++) {

			var fileobject = files[i];
			var fileName = fileobject.name;
			var iFileSize = fileobject.size;
			var iConvert = (fileobject.size / 1024).toFixed(2);
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			var ext = ext.toLowerCase();
			if (ext != "pdf" && ext != "wps" && ext != "et" && ext != "doc" && ext != "odt" && ext != "ods" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "docx" && ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "zip") {
				$(".error-upload").append('<br>' + fileName + ': File type is not supported');
			}
			else if (iConvert > 1024000) {
				$(".error-upload").append("<br>" + fileName + ':File size exceeded');
			}
			else {
				data.append('file' + i, files[i]);
				temp = 1;
			}
		}
		if (temp == 1) {

			data.append("action", "save_additional_program_document");
			data.append("program_id", program_id);

			$('#additional-loader-icon-' + program_id).show();
			//save document
			jQuery.ajax({
				type: 'POST', // Adding Post method
				url: ajaxurl, // Including ajax file
				contentType: false,
				data: data,
				processData: false,
				cache: false,
				target: '#targetLayer-' + program_id,
				beforeSubmit: function () {
					$("#progress-bar-" + program_id).width('0%');
				},
				uploadProgress: function (event, position, total, percentComplete) {
					$("#progress-bar-" + program_id).width(percentComplete + '%');
					$("#progress-bar-" + program_id).html('<div id="progress-status-' + program_id + '">' + percentComplete + ' %</div>')
				},
				success: function (result) { // Show returned data using the function.
					jQuery("#upload_additional_program_filename_" + program_id).html(result);
					$('#additional-loader-icon-' + program_id).hide();
					if (parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10) >= 10) {
						document.getElementById("app_files_" + program_id).parentNode.innerHTML = document.getElementById("app_additional_files_" + program_id).parentNode.innerHTML;
					}
					else{
						event.value = null;
					}
				}
			});
		}
	}
}
//Remove document
$(document).on('click', '.removefile', function (event) {

    if (confirm("Are you sure you want to delete?")) {
        var doc_row_id = event.target.id;
        var doc_array = doc_row_id.split('_');
//alert(doc_row_id);
        //remove document
        $.ajax({
            type: 'POST', // Adding Post method
            url: ajaxurl, // Including ajax file
            data: {"action": "remove_application_documents", "doc_row_id": doc_array[0], "app_id": doc_array[1], "category_id": doc_array[2], "appdoc_id": doc_array[3]},
            success: function (result) { // Show returned data using the function.

                $("#upload_app_filename_" + doc_array[2]).html(result);

                if($("#upload_app_filename_" + doc_array[2]).attr('class')=='additional-option-document')
                {
                    if($(".additional-option-document").html().trim()==''){
                        $('.additional_content').show();
                        $('.or_class').show();
                        if($('#option_content').next('.errors').length==0)
                        {
                            $('#option_content').after('<div class="errors"></div>');
                        }
                        jQuery('#option_content').removeClass('pop-error');
                        prependClass('#option_content','js-vld-required');
                        $('.additional_content').show();
                        $('.or_class').show();
                        $('#option_content').prop('readonly',false);

                    }

                }
                if (result == 0) {
                    jQuery('#appdoc-' + doc_array[3]).val(0);
                }
                $("#is_doc_change").val(1);
                if (result == 0)
                    jQuery(".appdoc-" + doc_array[3]).val(0);
            }
        });
    }
    return false;
});
//Remove document

jQuery(function () {
    jQuery('[class^="js-vld-"]').each(function () {
        if (jQuery(this).parent().find('div.errors').length == 0) {
            if (!jQuery(this).is('select')) {
                jQuery(this).parent().append('<div class="errors"></div>');
            } else {
                jQuery(this).closest('.form-group').append('<div class="errors"></div>');
            }
        }
    });
});
function disp_err_message() {
    jQuery('.pop-error').each(function () {
        if (jQuery(this).is('div')) {
            jQuery(this).closest('.form-group').find('div.errors').html(jQuery(this).find('select').attr('title'));
        } else {
            jQuery(this).parent().find('div.errors').html(jQuery(this).attr('title'));
        }
    });
}


function remove_err_message(element) {
    jQuery(element).parent().find('div.errors').html('');
}

//Remove program document
$(document).on('click', '.remove_programfile', function (event) {
    if (confirm("Are you sure you want to delete?")) {
        var doc_row_id = event.target.id;
        var doc_array = doc_row_id.split('_');

        //remove document
        $.ajax({
            type: 'POST', // Adding Post method
            url: ajaxurl, // Including ajax file
            data: {"action": "remove_program_documents", "doc_row_id": doc_array[0], "program_id": doc_array[1]},
            success: function (result) { // Show returned data using the function.

                $("#upload_program_filename_" + doc_array[1]).html(result);
            }
        });
    }
    return false;
});
//Remove program document

//Remove addtional program document
$(document).on('click', '.remove_additional_programfile', function (event) {
    if (confirm("Are you sure you want to delete?")) {
        var doc_row_id = event.target.id;
        var doc_array = doc_row_id.split('_');

        //remove document
        $.ajax({
            type: 'POST', // Adding Post method
            url: ajaxurl, // Including ajax file
            data: {"action": "remove_additional_program_documents", "doc_row_id": doc_array[0], "program_id": doc_array[1]},
            success: function (result) { // Show returned data using the function.

                $("#upload_additional_program_filename_" + doc_array[1]).html(result);
            }
        });
    }
    return false;
});
//Remove addtional program document

function application_push_to_crm(request_url, redirect_url) {
    $.ajax({
        type: 'GET',
        url: request_url,
        success: function (result) {
            window.location.href = redirect_url;
            return false;
        },
        failure: function () {
            alert("There was some error, please try again in some time.");
            return false;
        }
    });
    return false;
}

/*function to change inspection agency*/
function change_insepection_agency()
{
    var app_id = parent.jQuery('#popup_app_id').val();
    var certificate_name = $('#popup_certificate_name').val();
    var app_submit_url = redirecturl;

    if(certificate_name=='')
    {
        alert('Please enter certificate name.');
        return false;
    }else
    {

        if(jQuery.inArray( parseInt($('#program_id').val()), [2, 7, 10] )==-1){
            jQuery(".spinner-wp").css('display', 'block');
            if(jQuery('#certificate_name').val()=='')
            {
                var data = {
                    action: 'save_certificate_name',
                    app_id: app_id,
                    certificate_name: certificate_name
                };
                jQuery.post(ajaxurl, data, function (response) {
                    var response_obj = jQuery.parseJSON(response);

                    if (response_obj.status == true) {
                        application_push_to_crm(app_submit_url, app_redirect_url);
                    } else {
                        jQuery(".spinner-wp").css('display', 'none');
                        alert(response_obj.msg);
                    }
                });
            }else
            {
                application_push_to_crm(app_submit_url, app_redirect_url);
            }
        }else
        {
            var third_party_number = jQuery('#popup_third_party_number').val();
            //var app_submit_url = jQuery('#approve-link-' + app_id).attr('data-href');
            if (jQuery.trim(third_party_number)) {
                jQuery(".spinner-wp").css('display', 'block');// Add spinner
                jQuery('#inspection_agency_hidden').val(third_party_number);

                if(jQuery('#certificate_name').val()=='')
                {
                    var data = {
                        action: 'save_certificate_name',
                        app_id: app_id,
                        certificate_name: certificate_name
                    };
                    jQuery.post(ajaxurl, data, function (response) {
                        var response_obj = jQuery.parseJSON(response);

                        if (response_obj.status == true) {
                            jQuery.post(ajaxurl, {'action': 'change_third_party_number', 'inspection_agency_hidden': third_party_number, 'id': app_id}, function (result){
                                application_push_to_crm(app_submit_url, app_redirect_url);
                            });
                        } else {
                            jQuery(".spinner-wp").css('display', 'none');
                            alert(response_obj.msg);
                        }
                    });
                }else{
                    jQuery.post(ajaxurl, {'action': 'change_third_party_number', 'inspection_agency_hidden': third_party_number, 'id': app_id}, function (result){
                            application_push_to_crm(app_submit_url, app_redirect_url);
                        });
                }


            } else
            {
                alert('Please enter third party number.');
                return false;
            }
        }
    }

}

jQuery(document).ready(function () {
    /*techincal tooltip*/
    jQuery("span.technical").hover(function () {
    jQuery(this).append('<div class="tooltip1">The technical contact is the individual listed on accreditation certificates and acts as the liaison between the agency and IAS to schedule assessments, make certificate revisions, and/or request scope extensions.</div>');
    }, function () {
    jQuery("div.tooltip1").remove();
    });

    /*billing tooltip*/
    jQuery("span.billing").hover(function () {
    jQuery(this).append('<div class="tooltip1">The billing contact is the individual who handles all payment/accounting related matters (i.e.assessment invoices and renewals).</div>');
    }, function () {
    jQuery("div.tooltip1").remove();
    });

    /*legal tooltip*/
    jQuery("span.legal").hover(function () {
    jQuery(this).append('<div class="tooltip1">The legal contact is the authorized signatory of the organization that is accredited. This contact must be an employee of the organization.</div>');
    }, function () {
    jQuery("div.tooltip1").remove();
    });

    /*chief administrator tooltip*/
    jQuery(document).ready(function () {
      jQuery("span.chief").hover(function () {
        jQuery(this).append('<div class="tooltip1">(Such as: Mayor, City Manager)</div>');
      }, function () {
        jQuery("div.tooltip1").remove();
      });
    });

    jQuery('.option').click(function(){
        jQuery('.showoption').show();
        if(this.value=="Yes")
        {
            jQuery('.showoption').show();
            //$('#app_files_20').addClass('js-vld-upload');
            //$('#option_content').addClass('js-vld-required');
            prependClass('#option_content','js-vld-required');
            $('#appdoc-0').val(0);

        }else
        {
            jQuery('.showoption').hide();
            $('.additional_upload .errors').html('');
            $('#option_content').next('.errors').html('');
            $('#option_content').removeClass('pop-error');
            $('#appdoc-0').val(1);
            $('#option_content').removeClass('js-vld-required');
        }
    });
    jQuery('#option_content').on('keyup',function(){
        if(this.value=='')
        {

            jQuery('.additional_upload').show();
            $('.or_class').show();
            $('#appdoc-0').val(0);
        }else
        {

            jQuery('.additional_upload').hide();
            $('.additional_upload .errors').html('');
            $('.or_class').hide();
            $('#appdoc-0').val(1);
        }
    });
    jQuery('#skip_payment').click(function(){
        if($(this).prop('checked'))
        {
            show_payment_tab=0;
        }else
        {
            show_payment_tab=1;
        }
    });
    jQuery('#payment_mode_offline').click(function(){
        if($(this).prop('checked'))
        {
            show_payment_tab=0;
        }else
        {
            show_payment_tab=1;
        }
    });
    jQuery('#payment_mode_credit').click(function(){
        if($(this).prop('checked'))
        {
            show_payment_tab=1;
        }else
        {
            show_payment_tab=0;
        }
    });
    jQuery('.logintab').on('click',function(){
    jQuery('#user_login').focus();
    });

});
function prependClass(sel, strClass) {
    var $el = jQuery(sel);

    /* prepend class */
    var classes = $el.attr('class');
    classes = strClass +' ' +classes;
    $el.attr('class', classes);
}
function update_billing_address() {
	var application_id = jQuery(".editid").val();
    jQuery.post(ajaxurl, {'action': 'update_billing_address', 'application_id': application_id}, function (result) {
    	if( result != 0) {
        	var response_obj = jQuery.parseJSON(result);
        	if( (typeof response_obj != 'undefined') && response_obj != '0' ){

	        	if( (typeof $('#billing_name') != 'undefined') && (typeof response_obj.contact.data.display_name != 'undefined')) {
	                jQuery("#billing_name").removeClass('pop-error');
	                remove_err_message(jQuery("#billing_name"));
	        		$('#billing_name').val(response_obj.contact.data.display_name);
	        	}
	        	if((typeof $('#billing_address') != 'undefined') && (typeof response_obj.contact.data.address != 'undefined')) {
	                jQuery("#billing_address").removeClass('pop-error');
	                remove_err_message(jQuery("#billing_address"));
	        		$('#billing_address').val(response_obj.contact.data.address);
	        	}
	        	if((typeof $('#billing_city') != 'undefined') && (typeof response_obj.contact.data.city != 'undefined')) {
	                jQuery("#billing_city").removeClass('pop-error');
	                remove_err_message(jQuery("#billing_city"));
	        		$('#billing_city').val(response_obj.contact.data.city);
	        	}
	        	if((typeof $('#payment_billing_zipcode') != 'undefined') && (typeof response_obj.contact.data.zipcode != 'undefined')) {
	                jQuery("#payment_billing_zipcode").removeClass('pop-error');
	                remove_err_message(jQuery("#payment_billing_zipcode"));
	        		$('#payment_billing_zipcode').val(response_obj.contact.data.zipcode);
	        	}
	        	if((typeof $('#payment_billing_phone') != 'undefined') && (typeof response_obj.contact.data.phone != 'undefined')) {
	                jQuery("#payment_billing_phone").removeClass('pop-error');
	                remove_err_message(jQuery("#payment_billing_phone"));
	        		$('#payment_billing_phone').val(response_obj.contact.data.phone);
	        	}
	        	if((typeof $('#billing_country') != 'undefined') && (typeof response_obj.contact.data.country != 'undefined')) {
	                jQuery("#billing_country").removeClass('pop-error');
	                remove_err_message(jQuery("#billing_country"));
	                jQuery("#billing_country").parent().removeClass('pop-error');
	        		$('#billing_country').val(response_obj.contact.data.country);
	        	}
	        	if((typeof $('#billing_state') != 'undefined') && (typeof response_obj.contact.data.state != 'undefined')) {
	                jQuery("#billing_state").removeClass('pop-error');
	                remove_err_message(jQuery("#billing_state"));
	                jQuery("#billing_state").parent().removeClass('pop-error');
	        		$('#billing_state').val(response_obj.contact.data.state);
	        	}
        	}
    	}
    	jQuery(".spinner-wp").css('display', 'none');
    });
}
function sleepFor( sleepDuration ){
    var now = new Date().getTime();
    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ }
}

function refreshCaptcha( result ) {
	jQuery('#generate_captcha [rel="captcha-placeholder"]').html(result);
}

jQuery(document).ready(function () {

jQuery('#quotation_captcha').after('<div style="width:100%">Enter the characters you see.</span>');
jQuery('#contact_captcha').after('<div style="width:100%">Enter the characters you see.</span>');
  jQuery("span.captchatags").hover(function () {
    jQuery(this).append('<div class="tooltip1" style="width:400px !important;">Please note that the code is case sensitive. If you are not sure what the characters are, either enter your best guess or click the reload button next to the distorted characters.</div>');
  }, function () {
    jQuery("div.tooltip1").remove();
  });
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

