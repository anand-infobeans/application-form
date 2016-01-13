<div class="divider-10"></div>
<div class="col-lg-12">
    <div class="form-group">
        <b>Mailing Address <span class="color-red">*</span></b>
        <div class="divider-10"></div>
  
<div class="col-lg-12">
 <span class="defaultP">
 	<span class="radio-phone">
         <input type="checkbox" id="checktopopulatefield" name="data[new_application][new_sameasmailing]" <?php if (isset($application_data->new_application->new_sameasmailing) && $application_data->new_application->new_sameasmailing == '1') { ?> checked="checked" value="1"<?php }else {?>value="0"<?php }?> class="js-switch-mailing checktopopulatefield form-control"> &nbsp; Is Mailing Address the same as the Facility Address?
      </span>
    </span>
    
</div>

 <span class="defaultP">
 	<span class="radio-phone">
          
      </span>
    </span>
   <div class="divider-15"></div>   
        
        <input class="js-vld-add form-control pop-up-innput" placeholder="Address" id="mailadd" name="data[new_application][new_addressstreet1]" value='<?php if (isset($application_data->new_application->new_addressstreet1)) {
echo $application_data->new_application->new_addressstreet1;
} ?>' title="Please enter mailing address" onblur="checkValidAdd(this.value,this.id)">
    </div>
</div>



<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select Country</label>-->
    
    <div class="select-box-wp-2"> <span class="select-value">Select country</span>
       <select class="js-vld-select select-box width-100"  id="mailcountry" name="data[new_application][_linked][new_country][new_countryid]" onblur="checkValidSelect(this.value,this.id)" title="Please select country">
            <option value="">Select country</option>
            <?php foreach($country_result as $val){?>
            <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->_linked->new_country->new_countryid) && $val->id==$application_data->new_application->_linked->new_country->new_countryid){ echo "selected"; }?>><?php echo $val->country;?></option>
            <?php }?>
        </select>
    </div>
    
        
    </div>
</div>
<script>
    <?php if(isset($application_data->new_application->_linked->new_country->new_countryid)) { ?>
        jQuery('#mailcountry').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#mailcountry option:selected").text());
    <?php }?>
</script>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select State</label>-->
    
    <div class="select-box-wp-2"> <span class="select-value">Select state</span>
      <select class="js-vld-select select-box width-100" id="mailstate" name="data[new_application][_linked][new_state][new_stateid]" onblur="checkValidSelect(this.value,this.id)" title="Please select state">
             <option value="">Select state</option>
             <?php foreach($state_result as $val){?>
            <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->_linked->new_state->new_stateid) && $val->id==$application_data->new_application->_linked->new_state->new_stateid){ echo "selected"; }?>><?php echo $val->state;?></option>
            <?php }?>
        </select>
    </div>
     
    </div>
</div>
<script>
    <?php if(isset($application_data->new_application->_linked->new_state->new_stateid)) { ?>
        jQuery('#mailstate').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#mailstate option:selected").text());
    <?php }?>
</script>

<div class="clearfix"></div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Select City</label>-->
        <input class="js-vld-required pop-up-innput" type="text" id="mailcity" name="data[new_application][new_city]" placeholder="City" value="<?php if(isset($application_data->new_application->new_city)){ echo $application_data->new_application->new_city;}?>" onblur="checkValidInput(this.value,this.id)" title="Please enter city">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
    <!--<label>Zip Code</label>-->
        <input class="js-vld-required pop-up-innput" placeholder="Zip/Postal Code" id="mailzip" name="data[new_application][new_zippostalcode]"  maxlength="5" value='<?php if (isset($application_data->new_application->new_zippostalcode)) {
echo $application_data->new_application->new_zippostalcode;
} ?>' onblur="checkValidInput(this.value,this.id)" title="Please enter zipcode">
    </div>
</div>