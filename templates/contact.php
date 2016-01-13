<div class="divider-10"></div>
    <div class="col-lg-4">
        <div class="form-group">
            <div class="divider-15"></div>
            <b>Telephone No. <span class="color-red">*</span></b>
            <div class="divider-10"></div>
                <input placeholder="Telephone no." title="Please enter valid phone number"  id="telenumber"  name="data[new_application][telenumber]" value='<?php if (isset($application_data->new_application->telenumber)) {
echo $application_data->new_application->telenumber;
} ?>' class="js-vld-number pop-up-innput" onblur="checkValidNumber(this.value,this.id)">
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="form-group">
            <div class="divider-15"></div>
            <b>Fax No.</b>
            <div class="divider-10"></div>
                <input title="Please enter only number" placeholder="Fax no." id="faxno" name="data[new_application][faxno]"  value='<?php if (isset($application_data->new_application->faxno)) {
echo $application_data->new_application->faxno;
} ?>' class="js-vld-number pop-up-innput" onblur="checkValidNumber(this.value,this.id)">
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="form-group">
            <div class="divider-15"></div>
            <b>E-mail Address <span class="color-red">*</span></b>
            <div class="divider-10"></div>
                <input placeholder="Email address" title="Please enter email id"  id="emailadd" name="data[new_application][emailadd]"  value='<?php if (isset($application_data->new_application->emailadd)) {
echo $application_data->new_application->emailadd;
} ?>' class="js-vld-email pop-up-innput" onblur="checkValidEmail(this.value,this.id)">
        </div>
    </div>
    <div class="divider-5"></div>
    <div class="col-lg-4">
        <div class="form-group">
            <div class="divider-15"></div>
            <b>Web Address</b>
            <div class="divider-10"></div>
                <input placeholder="Web address" id="webadd" title="Please enter web address"  name="data[new_application][webadd]"  value='<?php if (isset($application_data->new_application->webadd)) {
echo $application_data->new_application->webadd;
} ?>' class="js-vld-url pop-up-innput" onblur="checkValidUrl(this.value,this.id)">
        </div>
    </div>