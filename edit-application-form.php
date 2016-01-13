<?php

global $wpdb;
$sql = 'select * from wp_application where id='.base64_decode($_GET['id']);
$result = $wpdb->get_results($sql);
//echo "<pre>";print_r($result);die('exit');
?>
<h1>View Application Form</h1>
<?php foreach ($result as $val) 
    $application_data = $val->application_data;
    $application_data = json_decode($application_data);
    //echo "<pre>";print_r($application_data);die('exit');
    ?>
<form>
    <h4 class="js-lbl" rel="js-div-general">General</h4>
    <div class="js-container js-div-general">
<div class="col-1">
   
        <label><?php echo $application_data->newaccreditation;?>:New accreditation<br />If this is a new request for accreditation, a copy of the applicant’s management system manual, complying with the applicable IAS

Accreditation Criteria, should be submitted with the application.</label>
    
  </div>
  <div class="col-2">
    
   
          <label><?php echo $application_data->renewal;?>:Renewal</label>
          </div>
       <div class="col-2">
           
       
         <label><?php echo $application_data->oneyear;?>:One Years</label>
         </div>
        <div class="col-2">
       
         <label><?php echo $application_data->twoyear;?>:Two Years</label>
         </div>
       <div class="col-2">
       
         <label><?php echo $application_data->threeyear;?>:Three Years</label>
         </div>
 
     <div class="col-1">
  
    
     <label><?php echo $application_data->cmpnamechange;?>:Company Name Change</label>
     </div> 
    <div class="col-3">
    <label>Number of Employees<br /></label>
   
    <label><?php echo $application_data->noofemptwentyfiveorless;?>:25 or Less</label>
    </div> 
     <div class="col-3">
    
    
    <label><?php echo $application_data->noofemptwentysixtoseventyfive;?>:26-75</label>
     </div> 
     <div class="col-3">
    
    <label><?php echo $application_data->noofempseventysixorabove;?>:76 and Above</label>
     </div> 
    <div class="col-2">
    <label>
      NAME OF APPLICANT (COMPANY NAME) :<?php echo $application_data->companyyname;?>
     
    </label>
  </div>
  <div class="col-2">
    <label>
      DESIRED SCOPE OF ACCREDITATION :<?php echo $application_data->desiredscopeofaccred;?>
      
    </label>
  </div>
        <div class="right">
     <h3 class="nextbutton">Next</h3>         
</div>
    </div>
    <div class="clear"></div>
    <h4 class="js-lbl" rel="js-div-adinfo">Additional Information</h4>
    <div class="js-container js-div-adinfo">
  <div class="col-2">
    <label>
      FACILITY STREET ADDRESS :<?php echo $application_data->new_labaddressstreet1;?>
      
    </label>
  </div>
    <div class="col-2">
    <label>
      
      City :<?php echo $application_data->new_labcity;?>
    </label>
  </div>
    <div class="col-3">
    <label>
      Country :<?php echo $application_data->_linked->new_country->new_labcountryid;?>
     
    </label>
  </div>
    <div class="col-3">
    <label>
    
      State :<?php echo $application_data->_linked->new_state->new_labstateid;?>
    </label>
  </div>
     
  <div class="col-3">
    <label>
      
     Zip Code :<?php echo $application_data->new_labzippostalcode;?>
    </label>
  </div>
     <div class="col-2">
    <label>
    MAILING ADDRESS :<?php echo $application_data->new_addressstreet1;?>
     
    </label>
  </div>
    <div class="col-2">
    <label>
      City :<?php echo $application_data->new_city;?>
     
    </label>
  </div>
    <div class="col-3">
    <label>
      Country :<?php echo $application_data->_linked->new_country->new_countryid;?>
      <input placeholder="Country (if other than U.S.A.)" id="mailcountry" name="mailcountry" tabindex="3" value='<?php if(isset($result[0]->mailcountry)){ echo $result[0]->mailcountry;}?>'>
    </label>
  </div>
    <div class="col-3">
    <label>
    State :<?php echo $application_data->_linked->new_state->new_stateid;?>
     
    </label>
  </div>
     
  <div class="col-3">
    <label>
      Zip :<?php echo $application_data->new_zippostalcode;?>
      
    </label>
  </div>
    <div class="col-2">
    <label>
      TELEPHONE NO. :<?php echo $application_data->telenumber;?>
      
    </label>
  </div>
    <div class="col-2">
    <label>
      FAX NO. :<?php echo $application_data->faxno;?>
     
    </label>
  </div>
  <div class="col-2">
    <label>
      E-MAIL ADDRESS :<?php echo $application_data->emailadd;?>
      
    </label>
  </div>
    <div class="col-2">
    <label>
     WEB ADDRESS :<?php echo $application_data->webadd;?>
      
    </label>
  </div>
        <div class="col-1">
    <label>
      Within the past five years have any of your accreditations been revoked, withdrawn, placed on suspension, and/or removed from listing? If

“yes” please explain on a separate page.:<?php echo $application_data->withinpastfiveyear;?>
     
    </label>
  </div>
    <div class="col-1">
    <label>
     If this is a renewal, please answer the three questions below. If you answer “yes” to any of the questions, please explain on a separate

sheet and/or include appropriate supporting documentation.
    
    </label>
  </div>
    <div class="col-1">
    <label>
     a.Since the last time your company applied for IAS accreditation, have there been any changes in ownership or in key management,

technical, or quality assurance personnel? :<?php echo $application_data->changesinownershiporkey;?>

    </label>
  </div>
    <div class="col-1">
    <label>
      b.Since the last time your company applied for IAS accreditation, have there been any changes in the documented quality system?:<?php echo $application_data->changesinqualitymanagement;?>
    
    </label>
  </div>
    <div class="col-1">
    <label>
      c.Are you aware of any complaints, from your company’s clients or others, about the services covered by this application? :<?php echo $application_data->awareofanycomplaint;?>
     
    </label>
  </div>
        <div class="right">
      <h3 class="prevbutton">Prev</h3>     <h3 class="nextbutton">Next</h3>         
</div>
        </div>
        <div class="clear"></div>
        <h4 class="js-lbl" rel="js-div-document">Documents</h4>
    <div class="js-container js-div-document">
        <input type="file" name="document" value="Upload">
    
    
<div class="right">
     <h3 class="prevbutton">Prev</h3><h3 class="nextbutton">Next</h3>         
</div>
    </div>
        <div class="clear"></div>
        <h4 class="js-lbl" rel="js-div-contact">Assign Contact</h4>
    <div class="js-container js-div-contact">
    <div class="col-2">
    <label>
    Name and title of applicant’s technical representative :<?php echo $application_data->applicanttechname;?>
      
    </label>
  </div>
    <div class="col-2">
    <label>
        Title :<?php echo $application_data->applicanttechtitle; ?>
      
    </label>
  </div>
    <div class="col-1">
    <label>
      Address :<?php echo $application_data->applicanttechadd;?>
      
    </label>
  </div>
    <div class="col-3">
    <label>
      Phone number :<?php echo $application_data->applicanttechnumber;?>
      
    </label>
  </div>
    <div class="col-3">
    <label>
      FAX NO. :<?php echo $application_data->applicanttechfax;?>
      
    </label>
  </div>
  <div class="col-3">
    <label>
      Email :<?php echo $application_data->applicanttechemail;?>
     
    </label>
  </div>
    <div class="col-1">
    <label>
      Name of third-party inspection agency :<?php echo $application_data->thirdpartyname;?>
      
    </label>
  </div>
    <div class="right">
     <h3 class="prevbutton">Prev</h3><h3 class="nextbutton">Next</h3>         
</div>
        </div>
        <div class="clear"></div>
        
        <h4 class="js-lbl" rel="js-div-payment">Payments</h4>
    <div class="js-container js-div-payment">
        
    
    
<div class="right">
    <h3 class="prevbutton">Prev</h3> <h3 class="nextbutton">Next</h3>         
</div>
    </div>
    </form>