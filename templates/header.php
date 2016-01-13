<?php global $wpdb;
$sql = 'select * from wp_application_form where id=' . base64_decode($_GET['id']);
$result = $wpdb->get_results($sql);

$user_sql = 'select * from  '.$wpdb->prefix .'application_form_user where created_user_id='.get_current_user_id( );
$user_result = $wpdb->get_results($user_sql);

$country_result = get_countries_list();

add_thickbox(); ?>

<style>label{margin: 10px}</style>
<h1>Application Form <span style="float: right;font-size: 20px;margin:20px 20px 0px 20px;"><a href="<?php echo get_admin_url();?>admin-post.php?action=user-add-form&KeepThis=true&TB_iframe=true&height=400&width=1200&modal=true" class="thickbox">Add User</a></span></h1>
<script>
<?php if(isset($_GET['id'])){?>
    alert_close();
<?php }?>
function alert_close()
{
    jQuery.post( ajaxurl ,{'action':'user_ajax','id':<?php if(isset($result[0]->applicanttechname) && $result[0]->applicanttechname!=""){ echo $result[0]->applicanttechname;}else{ echo 0;}?>},function(result){ jQuery("#applicanttechname").html(result);});
}
function change_content(id) {
    jQuery.post( ajaxurl ,{'action':'content_ajax','id':id},function(result){ 
        obj = JSON.parse( result );
        jQuery('#applicanttechtitle').val(obj[0].title);
        jQuery('#applicanttechadd').val(obj[0].address);
        jQuery('#applicanttechnumber').val(obj[0].phone);
        jQuery('#applicanttechfax').val(obj[0].fax);
        jQuery('#applicanttechemail').val(obj[0].email);
        jQuery('#thirdpartyname').val(obj[0].third_party);
        });
}
<?php if(isset($result[0]->facilitystate)){ ?>
change_state('<?php echo $result[0]->facilitycountry;?>','facilitystate','<?php echo $result[0]->facilitystate;?>')
<?php }?>

<?php if(isset($result[0]->mailstate)){ ?>
change_state('<?php echo $result[0]->mailcountry;?>','mailstate','<?php echo $result[0]->mailstate;?>')
<?php }?>

<?php if(isset($result[0]->facilitycity)){ ?>
change_city('<?php echo $result[0]->facilitystate;?>','facilitycity','<?php echo $result[0]->facilitycity;?>')
<?php }?>

<?php if(isset($result[0]->mailcity)){ ?>
change_city('<?php echo $result[0]->mailstate;?>','mailcity','<?php echo $result[0]->mailcity;?>')
<?php }?>

function change_state(country_id,label,edit) {
    jQuery.post( ajaxurl ,{'action':'get_state','id':country_id,'edit':edit},function(result){ 
        jQuery('#'+label).html(result);
        });
}
function change_city(state_id,label,edit) {
    jQuery.post( ajaxurl ,{'action':'get_city','id':state_id,'edit':edit},function(result){ 
        jQuery('#'+label).html(result);
        });
}

</script>
<form style="display: block ;margin: 30px 10px 30px 0px ;padding:10px;overflow : hidden ;font-size: 15px ;">
    <label>Form Type</label><input type="radio" name="formtype" value="formtype">
    <label>Renewal</label><input type="radio" name="formtype" value="renewal">
    <label>Branch</label><input type="radio" name="formtype" value="branch">
    <select id="applicationform">
        <option >Select Form</option>
        <option value="1">Application Form 1</option>
        <option value="2">Application Form 2</option>
    </select>
</form>

<form class="formhide" action="<?php echo get_admin_url(); ?>admin-post.php"  method="post" id="application-form1">