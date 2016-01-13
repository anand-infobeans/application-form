<label>Application for Accreditation of</label>
<div class="col-lg-12">
 <span class="defaultP">
 	<span class="radio-phone">
         <input  type="checkbox" id="accreditation_of_testing_lab" class="js-switch accreditation_of_testing_lab"  name="data[new_application][accreditation_of_testing_lab]" <?php if (isset($application_data->new_application->accreditation_of_testing_lab) && $application_data->new_application->accreditation_of_testing_lab == 'Yes') { ?>  value="Yes" checked="checked"<?php }else {?>value="No"<?php }?>> &nbsp; Testing Laboratory
      </span>
    </span>
</div>
<div class="col-lg-12">
 <span class="defaultP">
 	<span class="radio-phone">
         <input  type="checkbox" id="accreditation_of_calibration_lab" class="js-switch accreditation_of_calibration_lab"  name="data[new_application][accreditation_of_calibration_lab]" <?php if (isset($application_data->new_application->accreditation_of_calibration_lab) && $application_data->new_application->accreditation_of_calibration_lab == 'Yes') { ?>  value="Yes" checked="checked"<?php }else {?>value="No"<?php }?>> &nbsp; Calibration Laboratory
      </span>
    </span>
</div>
