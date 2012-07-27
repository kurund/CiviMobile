<?php
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    $parse_url = parse_url($url, PHP_URL_PATH);
    
    // get last arg of path (contact id)
    $membership_id = arg(3);
    $results = civicrm_api("Membership","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'membership_id' => $membership_id) 
                            );
    $membership = $results['values'][0];
    
    $contact_result = civicrm_api("Contact","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'contact_id' => $membership['contact_id'], 
                            'return' =>'display_name')
                            );
    $contact_name = $contact_result['values'][0]['display_name']; 
    include('civimobile.header.php');
?>
<div data-role="page" data-theme="c" id="jqm-contacts">

 <div data-role="header" data-theme="a">
    <a href="#" data-rel="back" class="ui-btn-left" data-icon="arrow-l">Back</a>
    <h3><?php print $contact_name;?></h3>
    	   <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>
  </div><!-- /header -->
	
	<?php
    civicrm_initialize( );
    require_once 'CRM/Utils/Date.php';
    require_once 'CRM/Member/PseudoConstant.php';
    ?>
	
	<div data-role="content" id="membership-content"> 
        <div data-role="content"> 
          <ul id="main-membership-list" data-role="listview" data-inset="true" >
              <li data-role="list-divider">Membership Details</li>
              <li role="option" tabindex="-1" data-theme="c">Name: <?php print $membership['membership_name'];?></li>
              <li role="option" tabindex="-1" data-theme="c">Start Date: <?php print CRM_Utils_Date::customFormat( $membership['start_date'] , null, null );?></li>
              <li role="option" tabindex="-1" data-theme="c">End Date: <?php print CRM_Utils_Date::customFormat( $membership['end_date'] , null, null );?></li>
              <li role="option" tabindex="-1" data-theme="c">Status: <?php print CRM_Member_PseudoConstant::membershipStatus( $membership['status_id'] );?></li>
          </ul>
         </div>
    </div> 
</div> 

<?php require('civimobile.footer.php'); ?>
