<div class="divider-10"></div>
<div class="col-lg-5">
	<div class="form-group">
		<b>Year Jurisdiction was Established (Ex : <?php echo date('Y');?>) <span class="color-red">*</span></b>
		<div class="divider-10"></div>
			<input class="js-vld-year pop-up-innput" placeholder="Established year for jurisdication" id="jurisdiction_year" name="data[new_application][new_jurisdictionest_new]"  value='<?php if (isset($application_data->new_application->new_jurisdictionest_new)) {
	echo $application_data->new_application->new_jurisdictionest_new;
	} ?>' title="Please enter correct year" onblur="checkValidYear(this.value,this.id)">
	</div>
</div>

<div class="col-lg-6">
	<div class="form-group">
	<b>Year <?php if($current_program_id==9){?>Fire Prevention<?php }else{ ?>Building<?php }?> Department was Established (Ex : <?php echo date('Y');?>) <span class="color-red">*</span></b>
		<div class="divider-10"></div>
		<input class="js-vld-year pop-up-innput" placeholder="Established year for <?php if($current_program_id==9){?>fire prevention<?php }else{ ?>building<?php }?> department" id="building_dept_year" name="data[new_application][new_departmentest_new]"  value='<?php if (isset($application_data->new_application->new_departmentest_new)) {
		echo $application_data->new_application->new_departmentest_new;
		} ?>' title="Please enter correct year" onblur="checkValidYear(this.value,this.id)">
	</div>
</div>