<?php
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    $parse_url = parse_url($url, PHP_URL_PATH);
    // get last arg of path (contact id)
    $contribution_id = arg(3);
    $results = civicrm_api("Contribution","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'contribution_id' => $contribution_id) 
                            );
    $contribution = $results['values'][0];
    include('civimobile.header.php');
?>
<div data-role="page" data-theme="c" id="jqm-contacts">

 <div data-role="header" data-theme="a">
    <a href="#" data-rel="back" class="ui-btn-left" data-icon="arrow-l">Back</a>
    <h3><?php print $contribution['display_name'];?></h3>
    	   <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>
  </div><!-- /header -->
	
	<?php
    civicrm_initialize( );
    require_once 'CRM/Core/Config.php';
    $config =& CRM_Core_Config::singleton( );
    $symbol = $config->defaultCurrencySymbol;
    require_once 'CRM/Utils/Date.php';
    ?>
	
	<div data-role="content" id="contribution-content"> 
        <div data-role="content"> 
          <ul id="main-contribution-list" data-role="listview" data-inset="true" >
              <li data-role="list-divider">Contribution Details</li>
              <li role="option" tabindex="-1" data-theme="c">Type: <?php print $contribution['contribution_type'];?></li>
              <li role="option" tabindex="-1" data-theme="c">Date: <?php print CRM_Utils_Date::customFormat( $contribution['receive_date'] , null, null );?></li>
              <li role="option" tabindex="-1" data-theme="c">Amount: <?php print $symbol.' '.$contribution['total_amount'];?></li><b></b>
          </ul>
         </div>
    </div> 
</div> 

<?php require('civimobile.footer.php'); ?>
