<div class="col-lg-3">
    <div class="checkbox">
        <label>
            <input  type="checkbox" id="companynamechange" class="js-switch companynamechange"  name="data[new_application][companynamechange]" <?php if (isset($application_data->new_application->companynamechange) && $application_data->new_application->companynamechange == 'Yes') { ?>  value="Yes" checked="checked"<?php }else {?>value="No"<?php }?>>
            Company Name Change
        </label>
    </div>
</div>

