<div class="col-lg-12">
    <b>Population Served</b>
</div>

<div class="divider-10"></div>

<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
             <input type="radio"  id="new_population1" name="data[new_application][new_population]" value="100000000" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000000') { ?> checked="checked"<?php }else if (!isset($application_data->new_application->new_population)) { echo "checked";} ?>>
             <span class="radio-label" > 0-9,999</span>
        </span>
    </span>
</div>

<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="new_population2" name="data[new_application][new_population]" value="100000001" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000001') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 10,000-49,999</span>
        </span>
    </span>
</div> 
   
<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
                <input type="radio"  id="new_population3" name="data[new_application][new_population]" value="100000002" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000002') { ?> checked="checked"<?php } ?>>
                <span class="radio-label" > 50,000-99,999</span>
        </span>
   </span>  
</div>

<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
                <input type="radio"  id="new_population4" name="data[new_application][new_population]" value="100000003" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000003') { ?> checked="checked"<?php } ?>>
                <span class="radio-label" > 100,000-199,999</span>
        </span>
   </span>  
</div> 

<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
                <input type="radio"  id="new_population5" name="data[new_application][new_population]" value="100000004" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000004') { ?> checked="checked"<?php } ?>>
                <span class="radio-label" > 200,000-499,999</span>
        </span>
   </span>  
</div>

<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
                <input type="radio"  id="new_population6" name="data[new_application][new_population]" value="100000005" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000005') { ?> checked="checked"<?php } ?>>
                <span class="radio-label" > 500,000-999,999</span>
        </span>
   </span>  
</div>

<div class="col-lg-4 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
                <input type="radio"  id="new_population7" name="data[new_application][new_population]" value="100000006" <?php if (isset($application_data->new_application->new_population) && $application_data->new_application->new_population == '100000006') { ?> checked="checked"<?php } ?>>
                <span class="radio-label" > 1,000,000 and Above</span>
        </span>
   </span>  
</div>

<div class="divider-15"></div>
<div class="gray-line-1"></div>
<div class="divider-15"></div>