<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/core/css/ib-custom.css' type='text/css' media='all' />
<script type='text/javascript' src='<?php echo plugin_dir_url(__FILE__); ?>/js/jquery-1.11.1.min.js?ver=1.0.0'></script>
<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/core/js/colorbox/jquery.colorbox.js?ver=1.6.1'></script>
<style>
    html{overflow-x: visible !important;}
</style>
<div class="container">
<?php
if(isset($_GET['program_id']))
{
    switch($_GET['program_id'])
    {
        case 1 :
            /*terms and condition for Testing Laboratory*/
            $page = get_page_by_path( 'testing-lab' );
            $page_id = $page->ID;
            break;
        case 2 :
            /*terms and condition for Metal Building Inspection*/
            $page = get_page_by_path( 'metal-building-systems' );
            $page_id = $page->ID;
            break;
        case 3 :
            /*terms and condition for Special Inspection Agency (New York City)*/
            $page = get_page_by_path( 'sia-nyc' );
            $page_id = $page->ID;
            break;
        case 4 :
            /*terms and condition for Inspection Agency*/
            $page = get_page_by_path( 'inspection-agency-2' );
            $page_id = $page->ID;
            break;
        case 5 :
            /*terms and condition for Calibration Lab*/
            $page = get_page_by_path( 'calibration-lab-2' );
            $page_id = $page->ID;
            break;
        case 6 :
            /*terms and condition for Building Department Accreditation*/
            $page = get_page_by_path( 'building-department-accreditation' );
            $page_id = $page->ID;
            break;
        case 7 :
            /*terms and condition for Fabricator Inspection*/
            $page = get_page_by_path( 'fabricator-inspection' );
            $page_id = $page->ID;
            break;
        case 8 :
            /*terms and condition for Field Evaluation Body*/
            $page = get_page_by_path( 'field-evaluation-body' );
            $page_id = $page->ID;
            break;
        case 9 :
            /*terms and condition for Fire Prevention And Life Safety Department*/
            $page = get_page_by_path( 'fire-prevention-and-life-safety-dept' );
            $page_id = $page->ID;
            break;
        case 10 :
            /*terms and condition for Metal Building Assemblers*/
            $page = get_page_by_path( 'metal-building-assemblers' );
            $page_id = $page->ID;
            break;
        case 11 :
            /*terms and condition for Management Systems Certification Body*/
            $page = get_page_by_path( 'management-system-certification-body' );
            $page_id = $page->ID;
            break;
        case 12 :
            /*terms and condition for Product Certification Agency*/
            $page = get_page_by_path( 'product-certification-agency' );
            $page_id = $page->ID;
            break;
        case 13 :
            /*terms and condition for Personnel Certification Body*/
            $page = get_page_by_path( 'personnal-certification-bodies' );
            $page_id = $page->ID;
            break;
        case 14 :
            /*terms and condition for Training Agency Accreditation (QP Training)*/
            $page = get_page_by_path( 'qp-training' );
            $page_id = $page->ID;
            break;
        case 15 :
            /*terms and condition for Third Party Building Service Provider*/
            $page = get_page_by_path( 'third-party-building-service-provider' );
            $page_id = $page->ID;
            break;
        case 16 :
            /*terms and condition for Training Agency Accreditation (QP Curriculum)*/
            $page = get_page_by_path( 'qp-curriculum' );
            $page_id = $page->ID;
            break;
        case 17 :
            /*terms and condition for Special Inspection Agency (International Building Code)*/
            $page = get_page_by_path( 'sia-ibc' );
            $page_id = $page->ID;
            break;
        default :
            $page = get_page_by_path( 'terms-and-condition-2' );
            $page_id = $page->ID;
            break;
    }
}
// number should be replaced with a specific Page's id from your site, which you can find by mousing over the link to edit that Page on the Manage Pages admin page. The id will be embedded in the query string of the URL, e.g. page.php?action=edit&post=123.

$page_data = get_page( $page_id ); // You must pass in a variable to the get_page function. If you pass in a value (e.g. get_page ( 123 ); ), WordPress will generate an error. By default, this will return an object.

echo '<div class="pop-heading-wp">'. $page_data->post_title .'</div>';// echo the title
echo '<div class="row">';
echo '<div class="col-lg-12">';
echo apply_filters('the_content', $page_data->post_content); // echo the content and retain WordPress filters such as paragraph tags.
echo '</div>';
echo '</div>';
?>
<div style="text-align: center"><input type="checkbox" id="terms_condition_inner"> <label>I agree to terms and conditions</label><div class="clearfix"></div> <input type="button" id="terms_condition_inner_submit" value="Submit" class="colorbox-inline-70 btn btn-primary color-blue pull-right cboxElement">&nbsp;<span class="error-terms errors clearfix">Please read terms and conditions till bottom to submit</span>
</div>
<script>
    temp=1;
    //parent.jQuery('#cboxClose').hide();
    jQuery('#terms_condition_inner').hide();
    jQuery('#terms_condition_inner_submit').hide();
    jQuery('.container div').css({"margin": "0px 2px 20px 0px", "line-height": "22px"});
    jQuery('.col-lg-12 div.container').addClass('my-class');
    /*when user scroll to bottom show close button*/
    jQuery('.my-class').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            jQuery('#terms_condition_inner').show();
            jQuery('.error-terms').hide();
        }
    });
    
    jQuery('#terms_condition_inner').click(function(){
        if ($(this).prop('checked')) {
            jQuery("#terms_condition_inner_submit").show();    
        }else
        {
            jQuery("#terms_condition_inner_submit").hide();    
        }
        
    });
    
    jQuery('#terms_condition_inner_submit').click(function(){
        jQuery(this).prop('disabled', true);
        parent.jQuery('#application-form1').attr('target','');
        parent.jQuery('#application-form1').submit();
    });
    
</script>
<style>
body{background-image: none !important;}
#cboxClose {
position: absolute;
bottom: 0;
right: 0;
background: url(images/controls.png) no-repeat -25px 0;
width: 25px;
height: 25px;
text-indent: -9999px;
}
.my-class{overflow-y: scroll;height: 435px;}
.btn-primary{
    /* background: #005daa!important; */
background: #00539B!important;
color: #fff!important;
box-shadow: none!important;
text-shadow: none!important;
line-height: 20px;
border-radius: 3px!important;
border-color: #2e6da4;
border: 1px solid transparent;
display: inline-block;
padding: 6px 12px;
margin-bottom: 0;
font-size: 14px;
font-weight: 400;

text-align: center;
white-space: nowrap;
vertical-align: middle;

cursor: pointer;
-webkit-user-select: none;
}
.container{margin:0px 10px 0px 0px;padding:10px; background:#fff;}
</style>
