<div class="clearfix"></div>
<div class="gray-line-1"></div>
<div class="divider-10"></div>

<div class="col-lg-6">
    <div class="form-gorup">
     <b>Billing Contact&nbsp;<span class="billing heading-info-box-small">?</span></b>
        <div class="divider-10"></div>
        <div class="select-box-wp-2"> <span class="select-value">Select billing contact</span>
           <select class="select-box width-100" id="applicantbillingid" name="data[new_application][_linked][contact][new_billingcontact]" onchange="change_content(this.value,'content_ajax','billing')" <!--onblur="checkValidSelect(this.value,this.id)"--> title="Pleaset select billing contact">
               <option value="">Select billing contact</option>
           </select>
        </div>
    </div>
</div>

<div class="col-lg-6 add-more">
    <div class="form-group">
        <label>Add more</label>
    <a href="<?php echo get_admin_url();?>admin-post.php?action=user-add-form&KeepThis=true&TB_iframe=true&height=600&width=600&modal=true&label=applicantbillingid" class="iframe-colorbox glyphicon glyphicon-plus-sign"></a>
    </div>
</div>
<div class="divider-15"></div>
<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Title</label>-->
        <input class="pop-up-innput" placeholder="Job Title" id="applicantbillingtitle" name="data[new_application][applicantbillingtitle]" value='<?php if (isset($application_data->new_application->applicantbillingtitle)) {
echo $application_data->new_application->applicantbillingtitle;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Address</label>-->
        <input  class="pop-up-innput" placeholder="Address" id="applicantbillingadd" name="data[new_application][applicantbillingadd]" value='<?php if (isset($application_data->new_application->applicantbillingadd)) {
echo $application_data->new_application->applicantbillingadd;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Phone number</label>-->
        <input class="pop-up-innput" placeholder="Phone" id="applicantbillingnumber" name="data[new_application][applicantbillingnumber]" value='<?php if (isset($application_data->new_application->applicantbillingnumber)) {
echo $application_data->new_application->applicantbillingnumber;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>FAX NO.</label>-->
        <input  class="pop-up-innput" placeholder="Fax" id="applicantbillingfax" name="data[new_application][applicantbillingfax]" value='<?php if (isset($application_data->new_application->applicantbillingfax)) {
echo $application_data->new_application->applicantbillingfax;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Email</label>-->
        <input  class="pop-up-innput" placeholder="Email" id="applicantbillingemail" name="data[new_application][applicantbillingemail]" tabindex="4" value='<?php if (isset($application_data->new_application->applicantbillingemail)) {
echo $application_data->new_application->applicantbillingemail;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <!--<div class="form-group">
    <label>Name of third-party inspection agency</label>
        <input placeholder="Third Party Name" id="billingthirdpartyname" name="data[new_application][billingthirdpartyname]" value='<?php if (isset($application_data->new_application->billingthirdpartyname)) {
echo $application_data->new_application->billingthirdpartyname;
} ?>' class="form-control">
    </div>-->
</div>