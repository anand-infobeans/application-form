<?php
$page_url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
$delete_url = $page_url . $_SERVER["SERVER_NAME"] . strtok($_SERVER["REQUEST_URI"], '?');

if (isset($_GET['type'])) {
    $delete_url .= '?page=' . $_GET['page'] . '&type=' . $_GET['type'];
}
$page_url .= $_SERVER["SERVER_NAME"] . strtok($_SERVER["REQUEST_URI"], '?');
?>
<div class="wrap" >
    <div class="col-lg-3" style="width:auto;">
        <h2 >Notification Reminders for Accreditation Renewal</h2>
    </div>
    <div class="clear"></div>
   	<div class="clear"></div>
    <div class="col-lg-3">
        <h2>Add notification day
        <div style="cursor: pointer;" id="iconforAdditionReminder_id" class="form-group glyphicon glyphicon-plus-sign" onclick="return iconforAdditionReminder(jQuery(this))">
        </div></h2>
    </div>
</div>
<div class="hiderenewal_notification_additional" id="renewal_notification_additional">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-2">
            <div class="select-box-wp-2">
                <label><span class="select-value">To </span><span class="color-red">*</span></label> 
                <select name="salutaions" class="select-box width-100" id="salutaions" onchange="validate_to();">
                    <option value="">Select</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>

                </select>
                 <div class="clearfix"></div>
                <span id="salutaions-error" class="color-red"></span>
            </div>
            </div>
            <div class="col-md-5 ">
            <label>No. of days Before due date <span class="color-red">*</span></label>
            <input type="text" placeholder="No.of days" name="noofdays" value="" class="required pop-up-innput width-100" id="noofdays" onchange="validate_noofdays();">
            <div class="clearfix"></div>
            <div id="addit-error" class="color-red text-center"></div>
          </div>
            <div class="col-md-2 ">



        <input style="min-width:100%" type="button" name="submit_button" value="Submit" class="btn-primary btn btn-large-custom width-100" onclick="return submit_additional_reminder_notification();" >


    </div>
        </div>
    </div>
     <div class="clearfix"></div>
            
       
  
</div>
<table class="form-table wp-list-table widefat fixed  pages table table-striped table-hover" id="renewal_settings">
</table>
<script>
	var reminder_config = new Object();
	var default_reminder_config = new Object();
	jQuery(document).ready(function () {
		ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery.post(ajaxurl, {'action': 'get_reminder_settings'},
		function (result) {
			if (typeof result != 'undefined' && result != 0) {
				default_reminder_config = result;
				reminder_config = jQuery.parseJSON(result);
				//Show reminder configuration settings
				show_reminder_config(reminder_config);
			}
		});
	});
	function show_reminder_config(reminder_config){
		jQuery('#renewal_settings').html('');
                var thead = jQuery("<thead/>");
                var tr = jQuery("<tr/>");
                var th1 = jQuery("<th/>", {id: 'additional_setting_entity', align:'left', text: 'Send Notification to'}).appendTo(tr);
                var th2 = jQuery("<th/>", {id: 'additional_setting_value', align:'left', text: 'Prior Day of Expiry Date'}).appendTo(tr);
                var th3 = jQuery("<th/>", {id: 'delete_renewal_notification', align:'left', text: 'Actions'}).appendTo(tr);
                jQuery(thead).append(tr);
		jQuery('#renewal_settings').append(thead);
		if ((typeof reminder_config != 'undefined') && (reminder_config != 0)) {
			jQuery.each(reminder_config, function (setting_type, setting_type_obj) {
				if (typeof setting_type_obj != 'undefined') {
					jQuery.each(setting_type_obj, function (type_key, setting_value) {
						add_reminder_row('renewal_settings', setting_type, setting_value);
					});
				}
			});
		} else {
			reminder_config = new Object();
			reminder_config.staff = new Object();
			reminder_config.customer = new Object();
		}
	}
	function add_reminder_row(parent_element_id, entity, setting_value) {
		if(((typeof setting_value != 'undefined') && (setting_value != '')) && ( (typeof entity != 'undefined') && (entity != '')) ) {
			var tr = jQuery("<tr/>", {id: 'notification_' + entity + '_' + setting_value});
			jQuery(tr).append(jQuery("<td/>", {text: entity[0].toUpperCase() + entity.substring(1)}));
			jQuery(tr).append(jQuery("<td/>", {text: setting_value.slice(1)}));
			var delete_anchor = jQuery("<a/>", {href: '#', text: 'Delete', click: function () {
				var checkfordelete = confirm('Are you sure you want to delete this notification?');
					if (checkfordelete) {
						delete_additional_setting(entity, setting_value.slice(1));
					}
				}});
			jQuery(tr).append(jQuery("<td/>").append(delete_anchor));
			jQuery('#' + parent_element_id).append(tr);
		}
	}
    /**
     * Used to manipulate notification settings and store it in the database
     * @setting_old_value string, previous value this setting 
     * @setting_new_value string, new value this setting
     */
	function save_reminder_config(entity_name, setting_old_value, setting_new_value) {
		if(entity_name == 'customer' || entity_name == 'staff') {
			if (typeof reminder_config != 'undefined') {
				if ((reminder_config.hasOwnProperty(entity_name)) && (Object.keys(reminder_config[entity_name]).length > 0)) {
					if (jQuery.inArray(setting_old_value, reminder_config[entity_name]) > '-1') {
						var setting_key = jQuery.inArray(setting_old_value, reminder_config[entity_name]);
						if (setting_new_value != '' && setting_new_value > 0) {
							reminder_config[entity_name][setting_key] = setting_new_value;
						} else {
							reminder_config[entity_name].splice(setting_key, 1);
						}
					} else if (setting_new_value != '') {
						reminder_config[entity_name].push(setting_new_value);
					}
				} else if (setting_new_value != '') {
					reminder_config[entity_name] = [setting_new_value];
				}
			} else if (setting_new_value != '') {
				reminder_config = new Object();
				reminder_config[entity_name] = [setting_new_value];
			}
		}
		jQuery.each(reminder_config, function (setting_type, setting_type_obj) {
			if (typeof setting_type_obj != 'undefined') {
				var test_arr = jQuery(setting_type_obj).toArray();
				test_arr.sort(function(a,b) {
					var a1 = parseInt(a.slice(1));
					var b1 = parseInt(b.slice(1));
					return (b1 - a1);
				});
				reminder_config[setting_type] = test_arr;
			}
		});
		//Finally store settings
		var result = 0;
		ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery.post(ajaxurl, {action: 'save_reminder_settings', 'data': JSON.stringify(reminder_config)},
		function (response) {
			default_reminder_config = response;
			reminder_config = jQuery.parseJSON(response);
			show_reminder_config(reminder_config);
		});
		return result;
	}
    /**
     * Used to store addtional notification setting by params
     * @param entity_name, name of the entity i.e. customer or staff
     * @param old_value, previous integer value this setting
     * @param new_value, new integer value this setting
     */
	function save_additional_setting(entity_name, old_value, new_value) {
		var setting_new_value = '';
		if (new_value != '') {
			setting_new_value = "d" + new_value;
		}
		save_reminder_config(entity_name, "d" + old_value, setting_new_value);
	}

    /**
     * Used to delete addtional notification setting by entity
     * @param entity_name, name of the entity i.e. customer or staff
     * @param day_value, integer value of day
     */
	function delete_additional_setting(entity_name, day_value) {
		response = save_reminder_config(entity_name, "d" + day_value, '');
		if (typeof jQuery('#notification_' + entity_name + '_' + "d" + day_value) != 'undefined') {
			jQuery('#notification_' + entity_name + '_' + "d" + day_value).remove();
		}
	}

	function submit_additional_reminder_notification() {
		temp = 1;
		var name = jQuery('#salutaions option:selected').val();
		var days = jQuery('#noofdays').val();
		if(!validate_to()) {
			temp = 0;
		} else if(!validate_noofdays()) {
			temp = 0;
		}
		else if (temp) {
			var setting_new_value = '';
			if (days != '') {
				setting_new_value = "d" + days;
			}
			save_reminder_config(name, null, setting_new_value);
			iconforAdditionReminder(jQuery('#iconforAdditionReminder_id'));
			return true;
		} else {
			return false;
		}
	}
	function validate_to() {
		temp = 1;
		var selectid = jQuery('#salutaions').val();
		var name = jQuery('#salutaions option:selected').val();
		if (selectid == '') {
			jQuery('#salutaions').attr('style', 'border: 1px solid red !important');
			jQuery('#salutaions-error').html("This is required");
			temp = 0;
		} else {
			jQuery('#salutaions-error').html("");
			jQuery('#salutaions').removeAttr("style");
		}
		if (temp) {
			return true;
		} else {
			return false;
		}
	}
	function validate_noofdays() {
		temp = 1;
		var selectid = jQuery('#salutaions').val();
		var setting_type = jQuery('#salutaions option:selected').val();
		var days = jQuery('#noofdays').val();
		var error_msg = "";
		if (days == '') {
			error_msg = 'Please fill this field';
			temp = 0;
		} else if (isNaN(days) || (new RegExp(/\D/).test(days))) {
			error_msg = 'Please enter number only';
			temp = 0;
		} else if (parseInt(days) < 1) {
			error_msg = 'Please enter days greater than 0';
			temp = 0;
		} else if (parseInt(days) > 0) {
			var test_days = "d"+days;
			if (typeof reminder_config != 'undefined') {
				if ((reminder_config.hasOwnProperty(setting_type)) && (Object.keys(reminder_config[setting_type]).length > 0)) {
					if (jQuery.inArray(test_days, reminder_config[setting_type]) > '-1') {
						error_msg = 'Duplicate days are not allowed';
						temp = 0;
					}
				}
			}
		}
		if (temp) {
			jQuery('#addit-error').html("");
			jQuery('#noofdays').removeAttr("style");
			return true;
		} else {
			jQuery('#addit-error').html(error_msg);
			jQuery('#noofdays').attr('style', 'border: 1px solid red !important');
			return false;
		}
	}
</script>
<style>
.color-red{color:red;}
table td,th{border-right: 1px solid #ddd;}
</style>