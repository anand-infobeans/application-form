<div class="divider-10"></div>
<div class="col-lg-5">
	<div class="form-group">
	<b>Name of County <span class="color-red">*</span></b>
	<div class="divider-10"></div>
		<input class="js-vld-required pop-up-innput"placeholder="County name" id="county_name" name="data[new_application][new_county]"  value='<?php if (isset($application_data->new_application->new_county)) {
echo $application_data->new_application->new_county;
} ?>'  title="Please enter county name"  onblur="checkValidInput(this.value,this.id)">
	</div>
</div>