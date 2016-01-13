<div class="divider-10"></div>
<div class="col-lg-5">
    <div class="form-group">
   <b>Jurisdiction Size ( In square miles )<span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-number pop-up-innput" placeholder="Jurisdiction size" id="jurisdiction_size" name="data[new_application][new_jurisdictionsize]"  value='<?php if (isset($application_data->new_application->new_jurisdictionsize)) {
echo $application_data->new_application->new_jurisdictionsize;
} ?>' title="Please enter jurisdiction size" onblur="checkValidNumber(this.value,this.id)">
    </div>
</div>