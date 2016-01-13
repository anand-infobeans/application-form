<h1 class="entry-title post-title">Invoice Listing</h1>

<?php
global $wpdb;
$page = (isset($_GET['paged']) ? $_GET['paged'] : 1);
$per_page = (isset($per_page) ? $per_page : 10);
$start_from = ($page - 1) * $per_page;
$roles = get_user_meta(get_current_user_id(), "wp_capabilities");
foreach ($roles as $role) {
    if (array_key_exists("staff", $role)) {
        $company_sql = 'select * from ' . $wpdb->prefix . 'company';
        $company_result = $wpdb->get_results($company_sql);
    } else {
        $company_sql = 'select ' . $wpdb->prefix . 'company.name,' . $wpdb->prefix . 'company.crm_id from  ' . $wpdb->prefix . 'company_user_roles left join ' . $wpdb->prefix . 'users on  ' . $wpdb->prefix . 'company_user_roles.user_id = ' . $wpdb->prefix . 'users.ID join ' . $wpdb->prefix . 'company on  ' . $wpdb->prefix . 'company_user_roles.company_id = ' . $wpdb->prefix . 'company.id WHERE ' . $wpdb->prefix . 'company_user_roles.user_id=' . get_current_user_id();
        $company_result = $wpdb->get_results($company_sql);
    }
}
?>

<select id="selectid" onchange="getcrmid();">

    <option value="">Select Company</option>

    <?php
    foreach ($company_result as $val) {
        $crm_id = base64_encode($val->crm_id);
        ?>
        <option value="<?php echo (isset($crm_id) ? $crm_id : ''); ?>" <?php if (isset($_GET['crmid']) && $_GET['crmid'] == "$crm_id") { ?>selected="selected"<?php } ?>><?php echo (isset($val->name) ? $val->name : ''); ?></option>
    <?php } ?>
</select>

<table id="" class="example table table-striped table-hover dt-responsive" cellspacing="0" width="100%">

    <thead>
        <tr>
            <th>Application ID</th>
            <th>Status</th>


            <th>Amount Due</th>
            <th>Pay</th>

        </tr>
    </thead>

    <tbody>

        <?php
        if (isset($_GET['crmid'])) {
            $crmid = base64_decode($_GET['crmid']);
            $CrmOperationsobj = new CrmOperations();
            $resultsfromcrm = $CrmOperationsobj->getCrmEntityDetails('invoice', array('type' => 'and', 'conditions' => array(array('attribute' => 'customerid', 'operator' => 'eq', 'value' => $crmid))), 'list', '', $page, $per_page);
// echo "<pre>";print_r($resultsfromcrm);
            $resulttotal = $resultsfromcrm->TotalRecordCount;

            foreach ($resultsfromcrm->Entities as $result) {
                ?>
                <tr>
                    <td><?php echo (isset($result->new_applicationid->Id) ? $result->new_applicationid->Id : ""); ?></td>
                    <td><?php echo (isset($result->statuscode->FormattedValue) ? $result->statuscode->FormattedValue : ""); ?></td>
                    <td><?php echo (isset($result->totalamount->FormattedValue) ? $result->totalamount->FormattedValue : ""); ?></td>
                    <td><a href="#">Pay</a></td>



                </tr>

            <?php
            }
        }
        ?>
    </tbody>
</table>
<?php
if (isset($_GET['crmid'])) {
    echo paginate_links(array(
        'base' => '%_%',
        'format' => '?paged=%#%',
        'current' => $page,
        'total' => ceil(($resulttotal) / $per_page)
    ));
}
?>