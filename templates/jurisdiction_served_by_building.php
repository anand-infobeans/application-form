<div class="col-lg-6">
    <div class="form-group">
   <b>Jurisdictions Served by <?php if($current_program_id==9){?>Fire Prevention<?php }else{ ?>Building<?php }?> Department<span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-required pop-up-innput" placeholder="(List name, county, NFIP number and population. Use additional sheet if necessary)" id="jurisdictions_served" name="data[new_application][new_jurisdictionsservedbydept]"  value='<?php if (isset($application_data->new_application->new_jurisdictionsservedbydept)) {
echo $application_data->new_application->new_jurisdictionsservedbydept;
} ?>' title="Please enter jurisdications served by <?php if($current_program_id==9){?>fire prevention<?php }else{ ?>building<?php }?> department" onblur="checkValidInput(this.value,this.id)">
    </div>
</div>