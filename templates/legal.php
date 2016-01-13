<div class="clearfix"></div>
<div class="gray-line-1"></div>
<div class="divider-10"></div>

<div class="col-lg-6">
    <div class="form-gorup">
    <b>Legal Contact&nbsp;<span class="legal heading-info-box-small">?</span></b>
        <div class="divider-10"></div>
        <div class="select-box-wp-2"> <span class="select-value">Select legal contact</span>
            <select  class="select-box width-100" id="applicantlegalid" name="data[new_application][_linked][contact][new_legalcontactid]" onchange="change_content(this.value,'content_ajax','legal')" <!--onblur="checkValidSelect(this.value,this.id)"--> title="Please select legal contact">
                <option value="">Select legal contact</option>
            </select>
        </div>
    </div>
</div>

<div class="col-lg-6 add-more">
    <div class="form-group">
        <label>Add more</label>
    <a href="<?php echo get_admin_url();?>admin-post.php?action=user-add-form&KeepThis=true&TB_iframe=true&height=600&width=600&modal=true&label=applicantlegalid" class="iframe-colorbox glyphicon glyphicon-plus-sign"></a>
    </div>
</div>

<div class="divider-15"></div>
<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Title</label>-->
        <input  class="pop-up-innput" placeholder="Job Title" id="applicantlegaltitle" name="data[new_application][applicantlegaltitle]" value='<?php if (isset($application_data->new_application->applicantlegaltitle)) {
echo $application_data->new_application->applicantlegaltitle;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
     <!--<label>Address</label>-->
        <input   class="pop-up-innput" placeholder="Address" id="applicantlegaladd" name="data[new_application][applicantlegaladd]"  value='<?php if (isset($application_data->new_application->applicantlegaladd)) {
echo $application_data->new_application->applicantlegaladd;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!-- <label>Phone number</label>-->
        <input  class="pop-up-innput" placeholder="Phone" id="applicantlegalnumber" name="data[new_application][applicantlegalnumber]" tabindex="4" value='<?php if (isset($application_data->new_application->applicantlegalnumber)) {
echo $application_data->new_application->applicantlegalnumber;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
     <!--<label>FAX NO.</label>-->
        <input class="pop-up-innput" placeholder="Fax" id="applicantlegalfax" name="data[new_application][applicantlegalfax]"  value='<?php if (isset($application_data->new_application->applicantlegalfax)) {
echo $application_data->new_application->applicantlegalfax;
} ?>' class="form-control">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
     <!--<label>Email</label> -->
            <input  class="pop-up-innput" placeholder="Email" id="applicantlegalemail" name="data[new_application][applicantlegalemail]" tabindex="4" value='<?php if (isset($application_data->new_application->applicanttechemail)) {
echo $application_data->new_application->applicantlegalemail;
} ?>' class="form-control">
    </div>
</div>

<!--<div class="col-lg-4">
    <div class="form-group">
    <label>Name of third-party inspection agency</label>
        <input placeholder="Third Party Name" id="legalthirdpartyname" name="data[new_application][legalthirdpartyname]"  value='<?php if (isset($application_data->new_application->legalthirdpartyname)) {
echo $application_data->new_application->legalthirdpartyname;
} ?>' class="form-control">
    </div>
</div>
-->