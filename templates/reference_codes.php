<div class="col-lg-6">
    <div class="form-group">
   <b>Reference Codes</b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Reference codes" id="reference_codes" name="data[new_application][reference_codes]"  value='<?php if (isset($application_data->new_application->reference_codes)) {
echo $application_data->new_application->reference_codes;
} ?>' title="Please enter reference codes" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>
<div class="clearfix"></div>
<div class="gray-line-1"></div>
<div class="divider-10"></div>