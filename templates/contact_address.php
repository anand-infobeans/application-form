<div class="divider-10"></div>
<div class="col-lg-12">
    <div class="form-group">
        <b>Contact Address <span class="color-red">*</span></b>
        <div class="divider-10"></div>
  
 <span class="defaultP">
 	<span class="radio-phone">
          
      </span>
    </span>
    
        <input class="js-vld-add form-control pop-up-innput commonadd" placeholder="Address" id="contactadd" name="data[new_application][contactadd]" value='<?php if (isset($application_data->new_application->contactadd)) {
echo $application_data->new_application->contactadd;
} ?>' title="Please enter contact address" onblur="checkValidAdd(this.value,this.id)">
    </div>
</div>



<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select Country</label>-->
    
    <div class="select-box-wp-2"> <span class="select-value">Select country</span>
       <select class="js-vld-select select-box width-100 commoncountry"  id="contactcountry" name="data[new_application][contactcountry]" onblur="checkValidSelect(this.value,this.id)" title="Please select country">
            <option value="">Select country</option>
            <?php foreach($country_result as $val){?>
            <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->contactcountry) && $val->id==$application_data->new_application->contactcountry){ echo "selected"; }?>><?php echo $val->country;?></option>
            <?php }?>
        </select>
    </div>
    
        
    </div>
</div>
<script>
    <?php if(isset($application_data->new_application->contactcountry)) { ?>
        jQuery('#contactcountry').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#contactcountry option:selected").text());
    <?php }?>
</script>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select State</label>-->
    
    <div class="select-box-wp-2"> <span class="select-value">Select state</span>
      <select class="js-vld-select select-box width-100 commonstate" id="contactstate" name="data[new_application][contactstate]" onblur="checkValidSelect(this.value,this.id)" title="Please select state">
             <option value="">Select state</option>
             <?php foreach($state_result as $val){?>
            <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->contactstate) && $val->id==$application_data->new_application->contactstate){ echo "selected"; }?>><?php echo $val->state;?></option>
            <?php }?>
        </select>
    </div>
     
    </div>
</div>
<script>
    <?php if(isset($application_data->new_application->contactstate)) { ?>
        jQuery('#contactstate').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#contactstate option:selected").text());
    <?php }?>
</script>



<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select City</label>-->
        <input class="js-vld-required pop-up-innput commoncity" type="text" id="contactcity" name="data[new_application][contactcity]" placeholder="City" value="<?php if(isset($application_data->new_application->contactcity)){ echo $application_data->new_application->contactcity;}?>" onblur="checkValidInput(this.value,this.id)" title="Please enter city">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Zip Code</label>-->
        <input class="js-vld-required pop-up-innput commonzip" placeholder="Zip/Postal Code" id="contactzip" name="data[new_application][contactzip]"  value='<?php if (isset($application_data->new_application->contactzip)) {
echo $application_data->new_application->contactzip;
} ?>' onblur="checkValidInput(this.value,this.id)" title="Please enter zipcode">
    </div>
</div>