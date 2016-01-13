<div class="divider-10"></div>
<div class="col-lg-12">
    <div class="form-group">
   <b>Name of Third-party Inspection Agency</b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Third party inspection agency" id="inspection_agency" name="data[new_application][inspection_agency]"  value='<?php if (isset($application_data->new_application->inspection_agency)) {
echo $application_data->new_application->inspection_agency;
} ?>' onblur="checkValidInput(this.value,this.id)" title="Please enter third-party inspection agency name">
        <input type="hidden" class="pop-up-innput" placeholder="Third party inspection agency" id="inspection_agency_hidden" name="data[new_application][_linked][new_certificate][new_inspectionagencycert]"  value='<?php if (isset($application_data->new_application->_linked->new_certificate->new_inspectionagencycert)) {
echo $application_data->new_application->_linked->new_certificate->new_inspectionagencycert;
} ?>'>
    </div>
</div>