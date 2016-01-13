<div class="clearfix"></div>
<div class="gray-line-1"></div>
<div class="divider-10"></div>

<div class="col-lg-12">
    <div class="form-group">
   <b>Facility Address <span class="color-red">*</span></b>
   	<div class="divider-10"></div>
        <input class="js-vld-add form-control pop-up-innput commonadd" placeholder="Address (exactly as it should appear on listing)" id="facilitystreetadd" name="data[new_application][new_labaddressstreet1]"  value='<?php if (isset($application_data->new_application->new_labaddressstreet1)) {
echo $application_data->new_application->new_labaddressstreet1;
} ?>' title="Please enter facility address" onblur="checkValidAdd(this.value,this.id)">
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group">
    
        
    
    <!--<label>Select Country</label>-->
    <div class="select-box-wp-2">
      
      <span class="select-value">Select country</span>
       <select  class="js-vld-select select-box width-100 commoncountry" onblur="checkValidSelect(this.value,this.id)" id="facilitycountry" name="data[new_application][_linked][new_country][new_labcountryid]" title="Please select country" >
            <option value="">Select country</option>
            <?php foreach($country_result as $val){?>
            <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->_linked->new_country->new_labcountryid) && $val->id==$application_data->new_application->_linked->new_country->new_labcountryid){ echo "selected"; }?>><?php echo $val->country;?></option>
            <?php }?>
        </select>
    </div>
       
    </div>
</div>
<script>
<?php if(isset($application_data->new_application->_linked->new_country->new_labcountryid)) { ?>
	jQuery('#facilitycountry').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#facilitycountry option:selected").text());
<?php }?>
</script>

<div class="col-lg-6">
    <div class="form-group">
        
   <!-- <label>Select State</label>-->
    <div class="select-box-wp-2">
     
      <span class="select-value">Select state</span>
      <select class="js-vld-select select-box width-100 commonstate"id="facilitystate" name="data[new_application][_linked][new_state][new_labstateid]" onblur="checkValidSelect(this.value,this.id)" title="Please select state">
             <option value="">Select state</option>
             <?php foreach($state_result as $val){?>
            <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->_linked->new_state->new_labstateid) && $val->id==$application_data->new_application->_linked->new_state->new_labstateid){ echo "selected"; }?>><?php echo $val->state;?></option>
            <?php }?>
        </select>
    </div>

        
    </div>
</div>
<script>
<?php if(isset($application_data->new_application->_linked->new_state->new_labstateid)) { ?>
	jQuery('#facilitystate').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#facilitystate option:selected").text());
<?php }?>
</script>


<div class="clearfix"></div>
<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select City</label>-->
        <input class="js-vld-required pop-up-innput commoncity" type="text" id="facilitycity" name="data[new_application][new_labcity]"  placeholder="City" value="<?php if(isset($application_data->new_application->new_labcity)){ echo $application_data->new_application->new_labcity;}?>" onblur="checkValidInput(this.value,this.id)" title="Please enter city">
    </label>
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Zipcode</label>-->

        <input class="js-vld-required pop-up-innput commonzip" placeholder="Zip/Postal Code" id="facilityzip" name="data[new_application][new_labzippostalcode]" maxlength="5" value='<?php if (isset($application_data->new_application->new_labzippostalcode)) {
echo $application_data->new_application->new_labzippostalcode;
} ?>' onblur="checkValidInput(this.value,this.id)" title="Please enter zipcode">
    </div>
</div>
<div class="clearfix"></div>

<div class="gray-line-1"></div>
