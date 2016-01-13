<label>Accreditation Criteria</label>
<div class="col-lg-12">
 <span class="defaultP">
 	<span class="radio-phone">
         <input  type="checkbox" id="accreditation_criteria_us" class="js-switch accreditation_criteria_us"  name="data[new_application][accreditation_criteria_us]" <?php if (isset($application_data->new_application->accreditation_criteria_us) && $application_data->new_application->accreditation_criteria_us == 'Yes') { ?> value="Yes" checked="checked"<?php }else if(isset($application_data->new_application->accreditation_criteria_us) && $application_data->new_application->accreditation_criteria_us == 'No'){?>value="No"<?php }elseif(!isset($_GET['id'])){?>value="Yes" checked="checked" <?php }?>> &nbsp; AC251 (United States)
      </span>
    </span>
</div>
<div class="col-lg-12">
 <span class="defaultP">
 	<span class="radio-phone">
         <input  type="checkbox" id="accreditation_criteria_can" class="js-switch accreditation_criteria_can"  name="data[new_application][accreditation_criteria_can]" <?php if (isset($application_data->new_application->accreditation_criteria_can) && $application_data->new_application->accreditation_criteria_can == 'Yes') { ?>  value="Yes" checked="checked"<?php }else {?>value="No"<?php }?>> &nbsp; AC475 (Canada)
      </span>
    </span>
</div>
<div class="clearfix"></div>
<div class="divider-10"></div>