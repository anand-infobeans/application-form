<div class="divider-10"></div>
<div class="col-lg-5">
    <div class="form-group">
   <b>Name of Building Department <span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Name of Building Department" id="building_department" name="data[new_application][building_department]"  value='<?php if (isset($application_data->new_application->building_department)) {
echo $application_data->new_application->building_department;
} ?>' title="Please enter building department name" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>