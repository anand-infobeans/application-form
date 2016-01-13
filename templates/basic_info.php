<div class="col-1">
    <center style="position:relative; margin-left: 10px;float: left;padding-top: 21px;">
        <input type="checkbox" id="newaccreditation" <?php if ($result[0]->newaccreditation == 'Yes') { ?> checked="checked"<?php } ?> class="js-switch" ></center>
    <input class="newaccreditation"  type="hidden" value="<?php if ($result[0]->newaccreditation == 'Yes') {
echo "Yes";
} else {
echo "No";
} ?>" name="newaccreditation"> 
    <label>New accreditation<br />If this is a new request for accreditation, a copy of the applicant’s management system manual, complying with the applicable IAS

        Accreditation Criteria, should be submitted with the application.</label>

</div>
<!--<div class="col-2">

    <center style="position:relative; margin-left: 10px;float: left;padding-top: 21px;">
        <input type="checkbox" id="renewal" <?php if ($result[0]->renewal == 'Yes') { ?> checked="checked"<?php } ?> class="js-switch"></center>
    <input class="renewal"  type="hidden" value="<?php if ($result[0]->renewal == 'Yes') {
echo "Yes";
} else {
echo "No";
} ?>" name="renewal">
    <label>Renewal</label>
</div>-->