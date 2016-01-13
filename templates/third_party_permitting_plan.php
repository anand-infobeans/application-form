<div class="col-lg-8">
    <div class="form-group">
   <b>Name of Third-party Permitting, Plan Review and Inspection Service Provider <span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Name of third-party permitting,plan review and inspection service provider" id="third_party_permitting" name="data[new_application][third_party_permitting]"  value='<?php if (isset($application_data->new_application->third_party_permitting)) {
echo $application_data->new_application->third_party_permitting;
} ?>' class="form-control" title="Please enter third-party permitting" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>