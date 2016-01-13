<div class="col-lg-7">
    <div class="form-group">
   <b>Six-Digit National Flood Insurance Program (NFIP) Number (If applicable) </b>
   	<div class="divider-10"></div>
        <input class="js-vld-6-digits pop-up-innput" placeholder="NFIP Number" id="new_nfipnumber" name="data[new_application][new_nfipnumber]"  value='<?php if (isset($application_data->new_application->new_nfipnumber)) {
echo $application_data->new_application->new_nfipnumber;
} ?>' class="form-control" title="Please enter valid number" onblur="checkValid6Digit(this.value,this.id)">
    </div>
</div>