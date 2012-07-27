<?php
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    $parse_url = parse_url($url, PHP_URL_PATH);
    // get last arg of path (contact id)
    $participant_id = arg(3);
    $results = civicrm_api("Participant","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'participant_id' => $participant_id) 
                            );
    $participant = $results['values'][0];
    include('civimobile.header.php');
?>
<div data-role="page" data-theme="c" id="jqm-contacts" data-add-back-btn=”true”>

 <div data-role="header" data-theme="a">
    <a href="#" data-rel="back" class="ui-btn-left" data-icon="arrow-l">Back</a>
    <h3><?php print $participant['display_name'];?></h3>
    	    <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>

  </div><!-- /header -->
	
	<?php
    civicrm_initialize( );
    require_once 'CRM/Utils/Date.php';
    require_once 'CRM/Event/PseudoConstant.php';
    ?>
	
	<div data-role="content" id="event-content"> 
        <div data-role="content"> 
          <ul id="main-event-list" data-role="listview" data-inset="true" >
              <li data-role="list-divider">Participant Details</li>
              <li role="option" tabindex="-1" data-theme="c">Event: <?php print $participant['event_title'];?></li>
              <li role="option" tabindex="-1" data-theme="c">Date: <?php print CRM_Utils_Date::customFormat( $participant['event_start_date'] , null, null );?></li>
              <li role="option" tabindex="-1" data-theme="c">Status: <?php print $participant['participant_status'];?></li>
          </ul>
         </div>
    </div> 
</div> 

<?php require('civimobile.footer.php'); ?>
