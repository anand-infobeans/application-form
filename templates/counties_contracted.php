<div class="col-lg-6">
    <div class="form-group">
   <b>Name of Counties Contracted</b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Name of counties contracted" id="counties_contracted" name="data[new_application][new_county]"  value='<?php if (isset($application_data->new_application->new_county)) {
echo $application_data->new_application->new_county;
} ?>' title="Please enter name of counties contracted" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>