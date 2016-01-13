<div class="col-lg-4">
    <div class="form-group">
   <b>Name of the Jurisdictions Contracted</b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="Name of the jurisdiction contracted" id="jurisdiction_contracted" name="data[new_application][new_jurisdictionname]"  value='<?php if (isset($application_data->new_application->new_jurisdictionname)) {
echo $application_data->new_application->new_jurisdictionname;
} ?>' title="Please enter jurisdictions contracted" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>