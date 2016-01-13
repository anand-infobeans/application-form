<div class="col-lg-6">
  <div class="form-group">
  <b><?php if($current_program_id==3){?>SIA Director<?php }else{ ?>Technical<?php }?> Contact <span class="color-red">*</span>&nbsp;<span class="technical heading-info-box-small">?</span></b>
  <div class="divider-10"></div>
    <div class="select-box-wp-2"> <span class="select-value">Select  contact</span>
      <select class="js-vld-select select-box width-100" id="applicanttechid" name="data[new_application][_linked][contact][new_technicalcontactid]" onchange="change_content(this.value,'content_ajax','tech')" class="form-control" onblur="checkValidSelect(this.value,this.id)" title="Please select technical contact">
      <option value="">Select technical contact</option>
      </select>
    </div>
  </div>
</div>

<div class="col-lg-6 add-more">
  <div class="form-group">
    <label>Add More</label>
    <a href="<?php echo get_admin_url();?>admin-post.php?action=user-add-form&KeepThis=true&TB_iframe=true&height=600&width=600&modal=true&label=applicanttechid" class="iframe-colorbox glyphicon glyphicon-plus-sign"></a> </div>
</div>

<div class="divider-15"></div>

<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Title</label>-->
    <input class="pop-up-innput" placeholder="Job Title" id="applicanttechtitle" name="data[new_application][applicanttechtitle]"  value='<?php if (isset($application_data->new_application->applicanttechtitle)) {
echo $application_data->new_application->applicanttechtitle;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Address</label>-->
    <input class="pop-up-innput" placeholder="Address" id="applicanttechadd" name="data[new_application][applicanttechadd]"  value='<?php if (isset($application_data->new_application->applicanttechadd)) {
echo $application_data->new_application->applicanttechadd;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Phone number</label>-->
    <input class="pop-up-innput" placeholder="Phone" id="applicanttechnumber" name="data[new_application][applicanttechnumber]" tabindex="4" value='<?php if (isset($application_data->new_application->applicanttechnumber)) {
echo $application_data->new_application->applicanttechnumber;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>FAX NO.</label>-->
    <input class="pop-up-innput" placeholder="Fax" id="applicanttechfax" name="data[new_application][applicanttechfax]"  value='<?php if (isset($application_data->new_application->applicanttechfax)) {
echo $application_data->new_application->applicanttechfax;
} ?>' class="form-control">
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    <!--<label>Email</label>-->
    <input class="pop-up-innput" placeholder="Email" id="applicanttechemail" name="data[new_application][applicanttechemail]"  value='<?php if (isset($application_data->new_application->applicanttechemail)) {
echo $application_data->new_application->applicanttechemail;
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

