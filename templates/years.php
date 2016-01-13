   
<div class="divider-15"></div>
<div class="col-lg-12"> 
   <span class="defaultP pull-left">
   <span class="radio-phone">
  <input checked="checked" type="checkbox" id="renewal" name="data[new_application][renewal]"  <?php if (isset($application_data->new_application->renewal) && $application_data->new_application->renewal == 'Yes' || (isset($resultsfromcrm->Entities[0]->new_type->FormattedValue) == 'Renewal')) { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-switch form-control renewal">
   <span class="radio-label" >Renewal Period</span>
   </span>
   </span>
   
   <span class="defaultP pull-left margin-left-15" >
   <span class="radio-phone">
   <input type="checkbox"  id="cmpnamechange" name="data[new_application][companynamechange]"  <?php if (isset($application_data->new_application->companynamechange) && $application_data->new_application->companynamechange == 'Yes') { ?> checked="checked" value="Yes"<?php }else {?>value="No"<?php }?> class="js-switch cmpnamechange form-control">
   <span class="radio-label" > Company Name Change</span>
   </span>
   </span>
   
      
</div>
<div class="divider-15"></div>
<div class="gray-line-1"></div>
<div class="divider-15"></div>