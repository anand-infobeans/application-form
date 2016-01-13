<?php
$pageCode = 'change-password';
$current_page_url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

$current_page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
$current_page_url = base64_encode($current_page_url);
//wp_enqueue_script( 'zxcvbn-async' );
wp_enqueue_script('user-profile');
wp_enqueue_script('password-strength-meter');
wp_enqueue_script('user-suggest');
site_messages($pageCode);
?>
<style>form{padding:20px;}</style>
    <div>
        <div class="container inner-main-container">
            <div class="col-md-12 padding-left-0 padding-right-0 inner-main-heading">
                <div class="col-md-8 padding-left-0 padding-right-0">
                    <h1 class="post-title">Change Password</h1>
                </div>
                <div class="divider-10"></div>
                <div class="col-md-4 padding-left-0 padding-right-0"></div>                     
            </div>
            <div class="floating-line"></div>        
            <div class="clearfix"></div>
            <div id="quote-form-wrapper">
                <p><b>Note :</b> Use a combination of uppercase and lowercase letters, numbers, and symbols to create a unique password. Consider using a random word or phrase, and inserting numbers and symbols in place of letters (like iA$) to make it more secure. " ? $ % ^ & ).</p>
                <form onsubmit="return validateChangePassword()" method='post' action='<?php echo get_admin_url(); ?>admin-post.php?action=update_password'  id="resetpassform" name="resetpassform">
                    <!-- <div class='mypageMyDetailsBox'>
                        <h1 class='titleSub entry-title post-title'>Change Password</h1> -->

                        <div id="validation_errors" class="errors">
                            <?php
                            // Show validation message
                            if (isset($_GET['updated'])) {
                                if ($_GET['updated'] == "false") {
                                    ?>
                                    <?php if (isset($_SESSION['cp_error_msg']) && !empty($_SESSION['cp_error_msg'])) { ?>
                                        <div class="error">
                                            <p><?php
                                                echo $_SESSION['cp_error_msg'];
                                                unset($_SESSION['cp_error_msg']);
                                                ?></p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="error">
                                            <p><?php // _e('Something Went Wrong Please Try Again', 'Ias');  ?></p>
                                        </div>
                                    <?php } ?>
                                <?php } else if ($_GET['updated'] == "true") { ?>
                                    <?php if (isset($_SESSION['cp_success_msg']) && !empty($_SESSION['cp_success_msg'])) { ?>
                                        <div class="updated">
                                            <p><?php
                                                echo $_SESSION['cp_success_msg'];
                                                unset($_SESSION['cp_success_msg']);
                                                ?></p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="updated">
                                            <p><?php //_e('Password Updated Successfully!', 'Ias');  ?></p>
                                        </div>
                                    <?php } ?>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="col-md-12 padding-left-0">
                            <div class="col-md-3 padding-left-0 ">
                                <span class="color-red">*</span>                                
                                <span class="inner-frm-lable">Current Password</span>
                            </div>
                            <div class="col-md-9 padding-right-0 ">
                                <input size='15'  placeholder="Current Password" type="password" class="pop-up-innput" id="old_password" name='currentpassword'/>
                                <div class="errors"></div>
                            </div>
                        </div>
                        <div class="divider-20"></div>
                        <div class="col-md-12 padding-left-0">
                            <div class="col-md-3 padding-left-0 ">
                                <span class="color-red">*</span>                                
                                <span class="inner-frm-lable">New Password</span>
                            </div>
                            <div class="col-md-9 padding-right-0 ">
                                <!-- <div class="custom-lable"></div> -->
                                <input data-toggle="tooltip" data-placement="top" title="The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like !  ? $ % ^ &" size='15'  placeholder="New Password" type="password" class="pop-up-innput commonadd" id="pass1" name="newpassword"/>
                                <div class="errors"></div>
                            </div>
                        </div>
                        <div class="divider-20"></div>
                        <div class="col-md-12 padding-left-0">
                            <div class="col-md-3 padding-left-0 ">
                                <span class="color-red">*</span>                                
                                <span class="inner-frm-lable">Confirm New Password</span>
                            </div>
                            <div class="col-md-9 padding-right-0 ">
                                <!-- <div class="custom-lable"></div> -->
                                <input size='15'  placeholder="Confirm New Password" type="password" class="pop-up-innput" id="pass2" name='confirmpassword'/>
                                <div class="errors"></div>
                            </div>
                        </div>
                        <div class="divider-20"></div>

                            <div class="col-md-12 padding-left-0">
                                <div class="col-md-3 padding-left-0 ">                                
                                </div>
                                <div class="col-md-9 padding-right-0 ">
                                    <!-- <div class="custom-lable"></div> -->
                                    <div style="padding:2px;font-size: 12px;" id="pass-strength-result"></div>
                                </div>
                            </div>
                            <div class="divider-20"></div>
                            <div class="col-md-12 padding-left-0">
                                <div class="col-md-3 padding-left-0 "></div>
                                <div class="col-md-9 padding-right-0 ">
                                    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-large-custom margin-zero-auto width-100" style="max-width:100%!important" value="Save Changes"  />
                                    <input type="hidden" name="redirect_url" value="<?php echo $current_page_url; ?>"/>
                                    <input type="hidden" name="calling_from" value="<?php echo isset($params['calling_from']) ? $params['calling_from'] : ''; ?>"/>
                                </div>
                            </div>
                    <!-- </div> -->
                </form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .change-pwd-error{
        border: 1px solid red !important;
    }
    .errors{
        font-size: 14px;
        font-weight: normal;
        color: red;
    }
    .star{
        color: red;
    }
</style>
<script type="text/javascript">
    function validateChangePassword() {
        var flag = true;
        // var msg = 'Please correct following error(s) - <br/>';
        var old_password = jQuery('#old_password').val();
        var new_password = jQuery('#pass1').val();

        var confirm_password = jQuery('#pass2').val();
        
        if (!jQuery.trim(old_password)) {
            flag = false;
            // msg += 'Old password is required.<br/>';
            jQuery('#old_password').addClass('change-pwd-error');
            jQuery('#old_password').next(".errors").html("Current password is required")
        } else {
            jQuery('#old_password').removeClass('change-pwd-error');
            jQuery('#old_password').next(".errors").html('');
        }
        if (!jQuery.trim(new_password)) {

            flag = false;
            // msg += 'New password is required.<br/>';
            jQuery('#pass1').addClass('change-pwd-error');
            jQuery('#pass1').next(".errors").html("New password is required")
        } else {
            jQuery('#pass1').removeClass('change-pwd-error');
            jQuery('#pass1').next(".errors").html("")
        }
        if (!jQuery.trim(confirm_password)) {
            flag = false;
            //msg += 'Confirm password is required.<br/>';
            jQuery('#pass2').addClass('change-pwd-error');
            jQuery('#pass2').next(".errors").html("Confirm password is required")
        } else {
            jQuery('#pass2').removeClass('change-pwd-error');
            jQuery('#pass2').next(".errors").html("")
        }

        if (jQuery.trim(new_password) != '' && jQuery.trim(confirm_password) != '') {
            if (jQuery.trim(new_password) != jQuery.trim(confirm_password)) {
                //  msg += 'Password mismatch. Make sure the new and confirm passwords are identical.<br/>';
                jQuery('#pass1').addClass('change-pwd-error');
                jQuery('#pass2').addClass('change-pwd-error');
                jQuery('#pass2').next(".errors").html("Password mismatch. Make sure the new and confirm passwords are identical")
                // jQuery('#validation_errors').html(msg);
                flag = false;
            } else {
                jQuery('#pass1').removeClass('change-pwd-error');
                jQuery('#pass2').removeClass('change-pwd-error');
                jQuery('#pass2').next(".errors").html("");
               // flag = true;
            }
        }
        
        if (flag) {
            return true;
        } else {
            //alert(msg)
            // jQuery('#validation_errors').html(msg);
            return false;
        }


    }
</script>
<?php
// this goes in functions.php
if (strpos($_SERVER['REQUEST_URI'], 'change_password') !== false) {
    wp_localize_script( 'password-strength-meter', 'pwsL10n', array(
        'empty' => __( '' ),
        'short' => __( 'Very weak' ),
        'bad' => __( 'Weak' ),
        'good' => _x( 'Medium', 'password strength' ),
        'strong' => __( 'Strong' ),
        'mismatch' => __( 'Mismatch' )
    ) );
}
