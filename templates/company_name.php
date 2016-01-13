<div class="col-lg-6">
	<?php if(isset($app_name)){?>
	<b>Application Name : <?php echo $app_name;?></b>
	<?php }?>
</div>
<div class="col-lg-6">
	<?php if(isset($_GET['id'])){?>
	<b>Application ID : <?php echo base64_decode($_GET['id']);?></b>
	<?php }?>
</div>
<?php if(isset($_GET['id'])){?>
<div class="clearfix"></div>
<div class="divider-10"></div>
<div class="gray-line-1"></div>
<div class="divider-10"></div>
<?php }?>
<?php $user_data = get_userdata( get_current_user_id() );?>
<div class="col-lg-5">
    <div class="form-group">
	<input name="quotation_id" type="hidden" value="<?php if(isset($_GET["quotation_id"])) {echo $_GET["quotation_id"];}else{echo "0";} ?>"" >
        <b>Name of Applicant (Company name) <span class="color-red">*</span></b>
        <div class="divider-10"></div>
        <?php if(isset($_GET['view']) && $_GET['view']==true){ $display = 'style="display:none"';$class='class="select-box-wp-2"'?><?php }?>
         <div <?php if(isset($class)){ echo $class;}?>><!--class="select-box-wp-2"-->
			<?php if(isset($_GET['view']) && $_GET['view']==true){?><span class="select-value select-company-name">Select company</span><?php }?>
            <?php if(isset($resultsfromcrm->Entities[0]->new_customerid->Name) && ($resultsfromcrm->Entities[0]->new_customerid->Name)!=''){ echo "<br>".$resultsfromcrm->Entities[0]->new_customerid->Name; } else {?>
            <select class="select-box width-100 form-control" id="companyname" name="data[new_application][_linked][account][new_customerid]" title="Please select company name" onchange="filledAddress(this.value)" onblur="checkValidSelect(this.value)" <?php if(!user_can($current_user, "staff")){?>disabled="disabled"<?php } if(isset($display)){ echo $display;}?> >
                <option value="">Select company</option>
                <?php foreach($company_result as $val){ 
					if(isset($quotation->company_id) && $quotation->company_id ==  $val->id) $selected ="selected"; else $selected ="";
					if($company_id ==  $val->id) $selected ="selected"; else $selected ="";
                ?>
                <option value="<?php echo $val->id;?>" <?php if (isset($application_data->new_application->_linked->account->new_customerid) && $val->id==$application_data->new_application->_linked->account->new_customerid){ echo "selected"; }else {echo $selected;}?>><?php echo $val->name;?></option>
                <?php }?>
            </select>
            <?php }
			if(!user_can($current_user, "staff")){?>
			<input type="hidden" name="data[new_application][_linked][account][new_customerid]" value="<?php if(isset($company_id)) {echo $company_id;}?>">
			<?php }?>
        </div>
    </div>
</div>
<script>
    <?php if(isset($application_data->new_application->_linked->account->new_customerid)) { ?>
        jQuery('#companyname').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#companyname option:selected").text());
		jQuery('.select-company-name').html(jQuery("#companyname option:selected").text())
    <?php }else if(isset($company_id)) { ?>
        jQuery('#companyname').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#companyname option:selected").text());
		filledAddress('<?php echo $company_id;?>');<?php
		}
	?>
	function filledAddress(selectval){
		jQuery(".spinner-wp").css('display', 'block');// show spinner
		$.post(ajaxurl+'/admin_post.php',{"action":"change_address","select_value":selectval},function(result){
			jQuery(".spinner-wp").css('display', 'none');// hide spinner
			if (result==0) {
				jQuery('#companyname').parent().addClass('pop-error');
				jQuery(".commonadd").val('');
				checkValidInput(jQuery("#facilitystreetadd").val(),'facilitystreetadd');
				jQuery(".commoncountry").val('');
				checkValidSelect(jQuery("#facilitycountry").val(),'facilitycountry');
				jQuery(".commonstate").val('');
				checkValidSelect(jQuery("#facilitystate").val(),'facilitystate');
				jQuery(".commoncity").val('');
				checkValidInput(jQuery("#facilitycity").val(),'facilitycity');
				jQuery(".commonzip").val('');
				checkValidInput(jQuery("#facilityzip").val(),'facilityzip');
				jQuery('#facilitycountry').parent().find('.select-value').html(jQuery("#facilitycountry option:selected").text());
				//jQuery('#mailcountry').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#mailcountry option:selected").text());
				jQuery('#facilitystate').parent().find('.select-value').html(jQuery("#facilitystate option:selected").text());
				//jQuery('#mailstate').parent().find('.select-value').attr('style', 'color: rgb(85, 85, 85)').html(jQuery("#mailstate option:selected").text());
				copyFacilityToMailing();
			}else
			{
				jQuery('#companyname').parent().removeClass('pop-error');
				jQuery('#companyname').closest('.form-group').find('div.errors').html('');
				jQuery('#companyname').parent().removeClass('pop-error');
				var obj = JSON.parse(result);
				if(obj.length > 0){
					jQuery("#emailadd").val(obj[0].email);
					jQuery("#faxno").val(obj[0].fax);
					jQuery("#telenumber").val(obj[0].phone);
					jQuery("#webadd").val(obj[0].website_url);
					jQuery(".commonadd").val(obj[0].address);
					checkValidAdd(obj[0].address,'facilitystreetadd');
					jQuery(".commoncountry").val(obj[0].country);
					jQuery('#contactcountry').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#contactcountry option:selected").text());
					checkValidSelect(obj[0].country,'facilitycountry');
					jQuery(".commonstate").val(obj[0].state);;
					jQuery('#contactstate').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#contactstate option:selected").text());
					checkValidSelect(obj[0].state,'facilitystate');
					jQuery(".commoncity").val(obj[0].city);
					checkValidInput(obj[0].city,'facilitycity');
					jQuery(".commonzip").val(obj[0].zipcode);
					checkValidInput(obj[0].zipcode,'facilityzip');
					jQuery('#facilitycountry').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#facilitycountry option:selected").text());
					//jQuery('#mailcountry').parent().find('.select-value').html(jQuery("#mailcountry option:selected").text());
					jQuery('#facilitystate').parent().find('.select-value').attr('style', 'color: #555').html(jQuery("#facilitystate option:selected").text());
					//jQuery('#mailstate').parent().find('.select-value').html(jQuery("#mailstate option:selected").text());
					copyFacilityToMailing();
				}
			}
		});
	}
</script>
