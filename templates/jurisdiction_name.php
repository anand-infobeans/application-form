<div class="col-lg-6">
    <div class="form-group">
   <b>Name of the Jurisdiction <span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Name of the jurisdiction" id="jurisdiction_name" name="data[new_application][new_jurisdictionname]"  value='<?php if (isset($application_data->new_application->new_jurisdictionname)) {
echo $application_data->new_application->new_jurisdictionname;
} ?>' title="Please enter jurisdiction name" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>