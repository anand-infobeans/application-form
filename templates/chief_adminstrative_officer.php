<div class="clearfix"></div>
<div class="gray-line-1"></div>
<div class="divider-10"></div>
<div class="col-lg-6">
  <div class="form-group">
  <b>Chief Administrative Officer and Title <span class="color-red">*</span>&nbsp;<span class="chief heading-info-box-small">?</span></b>
  <div class="divider-10"></div>
    <div class="select-box-wp-2"> <span class="select-value">Select chief admin contact</span>
      <select class="js-vld-select select-box width-100" id="applicantchiefid" name="data[new_application][_linked][contact][new_chiefadminofficerid]" onchange="change_content(this.value,'content_ajax','chief')" class="form-control" onblur="checkValidSelect(this.value,this.id)" title="Please select chief contact">
      <option value="">Select chief admin contact</option>
      </select>
    </div>
  </div>
</div>

<div class="col-lg-6 add-more">
  <div class="form-group">
    <label>Add More</label>
    <a href="<?php echo get_admin_url();?>admin-post.php?action=user-add-form&KeepThis=true&TB_iframe=true&height=600&width=600&modal=true&label=applicantchiefid" class="iframe-colorbox glyphicon glyphicon-plus-sign"></a> </div>
</div>

<div class="divider-15"></div>

<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Title</label>-->
    <input class="pop-up-innput" placeholder="Job Title" id="applicantchieftitle" name="data[new_application][applicantchieftitle]"  value='<?php if (isset($application_data->new_application->applicantchieftitle)) {
echo $application_data->new_application->applicantchieftitle;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Address</label>-->
    <input class="pop-up-innput" placeholder="Address" id="applicantchiefadd" name="data[new_application][applicantchiefadd]"  value='<?php if (isset($application_data->new_application->applicantchiefadd)) {
echo $application_data->new_application->applicantchiefadd;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Phone number</label>-->
    <input class="pop-up-innput" placeholder="Phone" id="applicantchiefnumber" name="data[new_application][applicantchiefnumber]" tabindex="4" value='<?php if (isset($application_data->new_application->applicantchiefnumber)) {
echo $application_data->new_application->applicantchiefnumber;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>FAX NO.</label>-->
    <input class="pop-up-innput" placeholder="Fax" id="applicantchieffax" name="data[new_application][applicantchieffax]"  value='<?php if (isset($application_data->new_application->applicantchieffax)) {
echo $application_data->new_application->applicantchieffax;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Email</label>-->
    <input class="pop-up-innput" placeholder="Email" id="applicantchiefemail" name="data[new_application][applicantchiefemail]"  value='<?php if (isset($application_data->new_application->applicantchiefemail)) {
echo $application_data->new_application->applicantchiefemail;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6"> 
  <!--<div class="form-group">
    <label>Name of third-party inspection agency</label>
        <input placeholder="Third Party Name" id="techthirdpartyname" name="data[new_application][techthirdpartyname]"  value='<?php if (isset($application_data->new_application->techthirdpartyname)) {
echo $application_data->new_application->techthirdpartyname;
} ?>' class="form-control">
    </div>--> 
</div>