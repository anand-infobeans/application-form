<?php
global $wpdb;

$user_id = get_current_user_id();
$user = get_user_by('id', $user_id);
$company_id = $user->company_id;
$country_result = get_countries_list();

$state_sql = 'select * from ' . $wpdb->prefix . 'state ';
$state_result = $wpdb->get_results($state_sql);
?>
<script type='text/javascript' src='<?php echo plugin_dir_url(__FILE__); ?>/js/jquery-1.11.1.min.js?ver=1.0.0'></script>
<link rel='stylesheet' id='bootstrap-css-css'  href='<?php echo plugin_dir_url(__FILE__); ?>/css/bootstrap.min.css?ver=4.2.2' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/core/css/ib-custom.css' type='text/css' media='all' />
<div class="container" style="padding:10px; background:#fff;">
    <div class="pop-heading-wp">Add Contact</div>
    <div class="row">
        <div class="col-lg-4">
            <form action="<?php echo get_admin_url(); ?>admin-post.php"  method="post" id="user_form">
                <div class="divider-20"></div>
                <div class="col-md-12">
                    <div class="custom-lable pull-left">Name<span class="color-red">*</span></div>
                    <div class="select-box-wp-2 pull-left custom-field" id="salutaions-div">

                        <span class="select-value">Salutation</span>
                        <select name="salutaions" class="select-box width-75 select-box-selected" id="salutation">
                            <option value="">Salutation</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Miss.">Miss.</option>
                            <option value="Ms.">Ms.</option>
                        </select>

                    </div>

                    <div class="pull-left custom-field margin-left">
                        <input type="text" name="fname" value="" class="required pop-up-innput" id="fname" placeholder="First Name">
                    </div>


                </div>
                <div class="clearfix"></div>




                <div class="divider-20"></div>
                <div class="col-md-12">
                    <div class="custom-lable pull-left"></div>
                    <div class="pull-left custom-field">
                        <input type="text" name="lname" value="" class="required pop-up-innput" id="lname" placeholder="Last Name">
                        <input type="hidden" name="id" value="0"  id="id">
                        <input type="hidden" name="label" value="<?php echo (isset($_GET['label']) && (!empty($_GET['label']))) ? $_GET['label'] : ""; ?>"  id="label">
                    </div>
                    <div class="pull-left custom-field margin-left">
                        <input type="text" name="title" value="" class="required pop-up-innput" id="title" placeholder="Job Title">
                    </div>
                </div>

                <div class="divider-20"></div>
                <div class="col-md-12">
                    <div class="custom-lable pull-left">Contact<span class="color-red">*</span></div>
                    <div class="pull-left custom-field">
                        <input type="text" name="email" value="" class="required pop-up-innput" id="email" placeholder="Email">
                    </div>
                    <div class="pull-left custom-field margin-left width-17" >
                        <input type="text" name="phone" value="" class="required pop-up-innput" id="phone" placeholder="Phone">
                    </div>
                    <div class="pull-left custom-field margin-left width-17" >
                        <input type="text" name="fax" value="" class="required pop-up-innput" id="fax" placeholder="Fax">
                    </div>
                </div>

                <div class="divider-20"></div>
                <div class="col-md-12">
                    <div class="custom-lable pull-left">Address<span class="color-red">*</span></div>
                    <div class="pull-left custom-field">
                        <input type="text" name="address" value="" class="required pop-up-innput" id="address" placeholder="Street">
                    </div>
                    <div class="pull-left custom-field margin-left width-17" >
                        <input type="text" name="city" value="" class="pop-up-innput" id="city" placeholder="City">
                    </div>
                    <div class="pull-left custom-field margin-left width-17" >
                        <input type="text" name="zipcode" value="" class="pop-up-innput" id="zipcode" placeholder="Zipcode" maxlength="5">
                    </div>
                </div>

                <div class="divider-20"></div>
                <div class="col-md-12">
                    <div class="custom-lable pull-left"></div>

                    <div id="state-div" class="select-box-wp-2 pull-left custom-field" > <span class="select-value">Select State</span>
                        <select class="select-box width-100 select-box-selected" id="state" name="state">
                            <option value="">Select State</option>
                            <?php foreach ($state_result as $val) { ?>
                                <option value="<?php echo $val->id; ?>"><?php echo $val->state; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div id="country-div" class="select-box-wp-2 pull-left custom-field margin-left"> <span class="select-value">Select Country</span>
                        <select class="select-box width-100 select-box-selected" id="country" name="country">
                            <option value="">Select Country</option>
                            <?php foreach ($country_result as $val) { ?>
                                <option value="<?php echo $val->id; ?>"><?php echo $val->country; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <span class="pull-left" style="width:25%;">&nbsp;</span>

                </div>
                <div class="col-md-12">
                    <span style="margin-bottom:15px;" id="state-error" class="errors pull-left custom-field margin-left"></span>
                    <span style="margin-bottom:15px;" id="country-error" class="errors pull-left custom-field"></span>
                </div>


                <input type="hidden" name="website_url" value="" class=" form-control" id="website_url" value="<?php
                if (isset($user->website_url)) {
                    echo $user->website_url;
                }
                ?>">
                <input type="hidden" name="preferred_form" value="" class=" form-control" id="preferred_form" value="<?php
                if (isset($user->preferred_form)) {
                    echo $user->preferred_form;
                }
                ?>">
                <input type="hidden" name="company_name" value="" class=" form-control" id="company_name" value="<?php
                if (isset($user->company_name)) {
                    echo $user->company_name;
                }
                ?>">
                <input type="hidden" name="application_data_id" id="application_data_id" value=""/>
                <input type="hidden" name="company_id" class=" form-control" id="company_id" value="<?php
                if (isset($company_id)) {
                    echo $company_id;
                } else {
                    echo 0;
                }
                ?>">
                <input type="hidden" name="mailing_address" value="" class=" form-control" id="mailing_address" value="<?php
                if (isset($user->mailing_address)) {
                    echo $user->mailing_address;
                }
                ?>">
                <input type="hidden" name="application_id" value="" class=" form-control" id="application_id">
                <input type="hidden" name="technical_id" value="0" class=" form-control" id="technical_id">
                <input type="hidden" name="billing_id" value="0" class=" form-control" id="billing_id">
                <input type="hidden" name="legal_id" value="0" class=" form-control" id="legal_id">
                <input type="hidden" name="chief_id" value="0" class=" form-control" id="chief_id">
                <input type="hidden" name="user_login" value="0" class=" form-control" id="user_login">
                <div class="divider-15"></div>


                <div class="col-md-12">
                    <div class="custom-lable pull-left"></div>
                    <input style="width:70% !important;" type="submit" name="submit" value="Submit" class="btn-primary btn btn-large-custom" onclick="return validate_user_form()">
                    <input type="hidden" name="action" value="user-form" />
                    <input type="hidden" name="hide" value="" />
                </div>

            </form>
            <div class="row">

            </div>
        </div>
    </div>
</div>
<style>
    .errors{
        color: red;
        /*padding: 3px;*/

    }
    .custom-lable{
        margin-bottom: 3px;
        font-weight:bold;
    }
    .custom-lable{
        width: 25%;
        text-align: right;
        padding-right: 10px;
        padding-top: 6px;
    }
    .custom-field{
        /*width: 75%;*/
        width: 35%;
    }
    .border-red{
        border: 1px solid red !important;
    }
    .border-normal{
        border: 1px solid #ccc !important;
    }
    .margin-left
    {
        margin-left:5px;
    }
    .width-17{
        width:17%;
    }
</style>
<script>
    jQuery(document).ready(function () {
        jQuery('#application_id').val(parent.jQuery(".editid").val());
        if(parent.jQuery("#applicanttechid").val()=='' || typeof parent.jQuery("#applicanttechid").val() === 'undefined'){
            jQuery('#technical_id').val(0);
        }else
        {
            jQuery('#technical_id').val(parent.jQuery("#applicanttechid").val());
        }
        if(parent.jQuery("#applicantbillingid").val()=='' || typeof parent.jQuery("#applicantbillingid").val() === 'undefined'){
            jQuery('#billing_id').val(0);
        }else
        {
            jQuery('#billing_id').val(parent.jQuery("#applicantbillingid").val());
        }
        if(parent.jQuery("#applicantlegalid").val()=='' || typeof parent.jQuery("#applicantlegalid").val() === 'undefined'){
            jQuery('#legal_id').val(0);
        }else
        {
            jQuery('#legal_id').val(parent.jQuery("#applicantlegalid").val());
        }
        if(parent.jQuery("#applicantchiefid").val()=='' || typeof parent.jQuery("#applicantchiefid").val() === 'undefined'){
            jQuery('#chief_id').val(0);
        }else
        {
            jQuery('#chief_id').val(parent.jQuery("#applicantchiefid").val());
        }
        window.parent.jQuery(".spinner-wp").css('display', 'none');
        jQuery('.select-box-selected').each(function () {
            var vl = jQuery("#" + this.id + " option:selected").text();
            jQuery(this).siblings().text(vl);
            jQuery(this).siblings().css('color', '#555555');
        });

        jQuery('.select-box').change(function () {
            var vl = jQuery("#" + this.id + " option:selected").text();
            jQuery(this).siblings().text(vl);
            jQuery(this).siblings().css('color', '#555555');

        });

        jQuery('.select-box').focus(function () {
            jQuery(this).parent().addClass('custom-focus');

        });

        jQuery('.select-box').focusout(function () {
            jQuery(this).parent().removeClass('custom-focus');

        });

        jQuery(".select-box").keyup(function () {
            jQuery(this).trigger('change');
        });
        if (jQuery("#companyname option:selected", window.parent.document).val() != '') {
            jQuery('#hiddencompanyname').val(jQuery("#companyname option:selected", window.parent.document).text());
        } else
        {
            alert('Please select company first');
            parent.jQuery.fn.colorbox.close();
        }
        if (jQuery(".editid", window.parent.document).val() != '') {
            jQuery('#application_data_id').val(jQuery(".editid", window.parent.document).val());
        }
    });
    //$('#company_id').val(($("#companyname", window.parent.document).val()));
    function validate_user_form() {
        var frameWidth = jQuery(document).width();
        var frameHeight = jQuery("#user_form").height();
        parent.$.fn.colorbox.resize({height:580});

        var regexp = /^[\s()+-]*([0-9][\s()+-]*){10,14}$/;
        temp = 1;
        salutaions = jQuery('#salutation').val();
        fname = jQuery('#fname').val();
        lname = jQuery('#lname').val();
        phone = jQuery('#phone').val();

        email = jQuery('#email').val();
        //third_party = jQuery('#third_party').val();
        title = jQuery('#title').val();
        fax = jQuery('#fax').val();
        address = jQuery('#address').val();
        country = jQuery('#country').val();
        state = jQuery('#state').val();
        city = jQuery('#city').val();
        zipcode = jQuery('#zipcode').val();


        if (fname == '')
        {
            jQuery('#fname').addClass('border-red');
            if (jQuery('#fname').next('.errors').length == 0) {
                jQuery('#fname').after("<div class='errors'>First name is required</div>");
            }
            temp = 0;
        } else {
            jQuery('#fname').removeClass('border-red');
            jQuery('#fname').next('.errors').remove();
        }
        if (lname == '')
        {
            jQuery('#lname').addClass('border-red');

            if (jQuery('#lname').next('.errors').length == 0) {
                jQuery('#lname').after("<div class='errors'>Last name is required</div>");
            }

            temp = 0;
        } else {
            jQuery('#lname').removeClass('border-red');
            jQuery('#lname').next('.errors').remove();
        }
        if (!regexp.test(phone) && phone != '')
        {

            jQuery('#phone').addClass('border-red');
            if (jQuery('#phone').next('.errors').length == 0) {
                jQuery('#phone').after("<div class='errors'>Invalid Phone number</div>");
            }
            temp = 0;
        }
        else {
            jQuery('#phone').removeClass('border-red');
            jQuery('#phone').next('.errors').remove();
        }
        if (email == '')
        {
            jQuery('#email').addClass('border-red');
            if (jQuery('#email').next('.errors').length == 0) {
                jQuery('#email').after("<div class='errors'>Email is required</div>");
            }
            temp = 0;
        } else {
            if (!validateEmail(email))
            {
                jQuery('#email').next('.errors').remove();
                jQuery('#email').addClass('border-red');
                if (jQuery('#email').next('.errors').length == 0) {
                    jQuery('#email').after("<div class='errors'>Invalid email address</div>");
                }
                temp = 0;
            } else {
                jQuery('#user_login').val(email);
                jQuery('#email').removeClass('border-red');
                jQuery('#email').next('.errors').remove();
            }
            // jQuery('#email').css('border', '1px solid #ccc');
        }



        /*if (title == '')
         {
         jQuery('#title').css('border', '1px solid red');
         temp = 0;
         } else {
         jQuery('#title').css('border', '1px solid #ccc');
         }*/

        if (!regexp.test(fax) && fax != '')
        {

            jQuery('#fax').addClass('border-red');
            if (jQuery('#fax').next('.errors').length == 0) {
                jQuery('#fax').after("<div class='errors'>Invalid fax number</div>");
            }
            temp = 0;

        } else {
            jQuery('#fax').removeClass('border-red');
            jQuery('#fax').next('.errors').remove();
        }

        if (address == '')
        {
            jQuery('#address').addClass('border-red');
            if (jQuery('#address').next('.errors').length == 0) {
                jQuery('#address').after("<div class='errors'>Street is required</div>");
            }
            temp = 0;
        } else {
            jQuery('#address').removeClass('border-red');
            jQuery('#address').next('.errors').remove();
        }

        if (country == '')
        {
            jQuery('#country-div').addClass('border-red');


            jQuery('#country-error').html("Country is required");


            temp = 0;
        } else {
            jQuery('#country-div').removeClass('border-red');
            jQuery('#country-error').html('');
        }


        if (state == '')
        {
            jQuery('#state-div').addClass('border-red');


            jQuery('#state-error').html("State is required");


            temp = 0;
        } else {
            jQuery('#state-div').removeClass('border-red');
            jQuery('#state-error').html('');
        }
        if (city == '')
        {
            jQuery('#city').addClass('border-red');

            if (jQuery('#city').next('.errors').length == 0) {
                jQuery('#city').after("<div class='errors'>City is required</div>");
            }

            temp = 0;
        } else {
            jQuery('#city').removeClass('border-red');
            jQuery('#city').next('.errors').remove();
        }


        if (zipcode == '')
        {
            jQuery('#zipcode').addClass('border-red');
            if (jQuery('#zipcode').next('.errors').length == 0) {
                jQuery('#zipcode').after("<div class='errors'>Zipcode is required</div>");
            }

            temp = 0;
        } else {
            jQuery('#zipcode').next('.errors').remove();
			var regexpZip =/^[0-9]{5}$/;
            if (isNaN(zipcode) && !regexpZip.test( zipcode ) )
            {
                jQuery('#zipcode').addClass('border-red');
                if (jQuery('#zipcode').next('.errors').length == 0) {
                    jQuery('#zipcode').after("<div class='errors'>Invalid zipcode</div>");
                }
                temp = 0;
            } else {
                jQuery('#zipcode').removeClass('border-red');
                jQuery('#zipcode').next('.errors').remove();
            }

        }

        if (temp) {
            parent.$.fn.colorbox.resize({height:480});
            window.parent.jQuery(".spinner-wp").css('display', 'block');
            return true;
        } else
        {
            return false;
        }

    }
    function validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }
    if(jQuery('.message')){
        jQuery('.message').delay(5000).fadeOut(500);
        //jQuery('.message').parent().remove();
    }
</script>