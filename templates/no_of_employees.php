<div class="divider-15"></div>
<div class="gray-line-1"></div>
<div class="divider-15"></div>
<div class="col-lg-12">
    <b>Number of Employees</b>
</div>
<div class="divider-10"></div>
<?php if(isset($program_id)){
    $current_program = $program_id;
}else if(isset($_REQUEST['program_id']))
{
    $current_program = $_REQUEST['program_id'];
}
if($current_program==6){
?>
<div class="col-lg-2 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofemptwentyfiveorless" name="data[new_application][new_noofemployeesfa]" value="100000000" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000000') { ?> checked="checked"<?php }else if (!isset($application_data->new_application->new_noofemployeesfa)) { echo "checked";} ?>>
            <span class="radio-label" > 1-10</span>
        </span>
    </span>  
</div>

<div class="col-lg-2 padding-right-0">  
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofemptwentysixtoseventyfive" name="data[new_application][new_noofemployeesfa]" value="100000001" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000001') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 11-25</span>
        </span>
   </span>
</div> 
     
<div class="col-lg-2 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofempseventysixorabove" name="data[new_application][new_noofemployeesfa]" value="100000002" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000002') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 26-50</span>
        </span>
   </span>
</div>

<div class="col-lg-2 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofempseventysixorabove" name="data[new_application][new_noofemployeesfa]" value="100000003" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000003') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 51-100</span>
        </span>
   </span>
</div>

<div class="col-lg-2 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofempseventysixorabove" name="data[new_application][new_noofemployeesfa]" value="100000004" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000004') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 101 and Above</span>
        </span>
   </span>
</div> 
<?php }else if($current_program==9){
?> 
<div class="col-lg-6">
    <div class="form-group">
   	<div class="divider-10"></div>
        <input class="pop-up-innput" placeholder="No of employees" id="noofemployees" name="data[new_application][new_noofemployeesfa]" value="<?php if (isset($application_data->new_application->new_noofemployeesfa)) { echo $application_data->new_application->new_noofemployeesfa; }?>">
    </div>
</div>
<?php    
}else { 
?>
<div class="col-lg-2 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofemptwentyfiveorless" name="data[new_application][new_noofemployeesfa]" value="100000000" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000000') { ?> checked="checked"<?php }else if (!isset($application_data->new_application->new_noofemployeesfa)) { echo "checked";} ?>>
            <span class="radio-label" > 25 or Less</span>
        </span>
    </span>  
</div>

<div class="col-lg-2 padding-right-0">  
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofemptwentysixtoseventyfive" name="data[new_application][new_noofemployeesfa]" value="100000001" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000001') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 26-75</span>
        </span>
   </span>
</div> 
   
   
<div class="col-lg-2 padding-right-0">
    <span class="defaultP pull-left">
        <span class="radio-phone">
            <input type="radio"  id="noofempseventysixorabove" name="data[new_application][new_noofemployeesfa]" value="100000002" <?php if (isset($application_data->new_application->new_noofemployeesfa) && $application_data->new_application->new_noofemployeesfa == '100000002') { ?> checked="checked"<?php } ?>>
            <span class="radio-label" > 76 and Above</span>
        </span>
   </span>
</div> 
<?php }?>
<div class="divider-15"></div>