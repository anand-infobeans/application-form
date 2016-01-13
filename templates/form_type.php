<div class="col-lg-4 padding-right-0">

<span class="defaultP pull-left">
   <span class="radio-phone">
   <input type="radio"  id="new" name="data[new_application][form_type]"  <?php if (isset($application_data->new_application->form_type) && $application_data->new_application->form_type == 'Yes') { ?> checked="checked" value="Yes"<?php }else { echo "value='No'";} ?> class="new js-switch">
   <span class="radio-label" > New</span>
   </span>
   </span>
   
  
</div> 
<!--<div class="col-lg-4 padding-right-0">

<span class="defaultP pull-left">
   <span class="radio-phone">
   <input type="radio"  id="renewal" name="data[new_application][form_type]" value="" <?php if (isset($application_data->new_application->renewal) && $application_data->new_application->renewal == '2') { ?> checked="checked"<?php } ?>>
   <span class="radio-label" >Renewal</span>
   </span>
   </span>
   
   </div> -->
