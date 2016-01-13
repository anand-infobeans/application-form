<div class="divider-10"></div>
<div class="col-lg-5">
    <div class="form-group">
   <b>Department Name <span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Name of the Department" id="new_departmentname" name="data[new_application][new_departmentname]"  value='<?php if (isset($application_data->new_application->new_departmentname)) {
echo $application_data->new_application->new_departmentname;
} ?>' title="Please enter department name" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>