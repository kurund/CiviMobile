	<?php
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    $parse_url = parse_url($url, PHP_URL_PATH);
      // get last arg of path (contact id)
    $contact_id = arg(3);
    $results = civicrm_api("Contact","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'contact_id' => $contact_id, 
                            'return' =>'display_name,email,phone,tag,group,contact_type,street_address,city,postal_code,state_province')
                            );
    $contact = $results['values'][0];
    
    // All details are displayed in the Contact View Page
    // Need to rework to sort out the display, to optimize the page load time 
    $contrib_results = civicrm_api("Contribution","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3, 
                                    'contact_id' => $contact_id) 
                                    );
    $member_results = civicrm_api("Membership","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3, 
                                    'contact_id' => $contact_id) 
                                    );
    $event_results = civicrm_api("Participant","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3, 
                                    'contact_id' => $contact_id) 
                                    );
    $activity_results = civicrm_api("Activity","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3, 
                                    'contact_id' => $contact_id) 
                                    );
    $relationship_results = civicrm_api("Relationship","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3, 
                                    'contact_id' => $contact_id) 
                                    );
    $note_results = civicrm_api("Note","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3,
                                    'entity_table' => 'civicrm_contact', 
                                    'entity_id' => $contact_id) 
                                    );
    $case_results = civicrm_api("Case","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3,
                                    'contact_id' => $contact_id) 
                                    );
    include('civimobile.header.php');
?>
<div data-role="page" data-theme="c" id="jqm-contacts">

 <div data-role="header" data-theme="a">
    <h3><?php print $contact['display_name'];?></h3>
    	   <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>
  </div><!-- /header -->
	
	<div data-role="content" id="contact-content"> 
        <div class="vcard">
        
          <?php if ($contact['phone'] != '') : ?>
          <div class="tel"> 
           <span class="phone">
                  <a href="tel:<?php print $contact['phone'];?>" data-role="button">Phone: <?php print $contact['phone'];?></a>
            </span>
          </div>  
          <?php endif; ?>
          
          <?php if ($contact['email'] != '') : ?>
          <div class="email"> 
           <span class="email">
                  <a href="mailto:<?php print $contact['email'];?>" data-role="button">Email: <?php print $contact['email'];?></a>
              </span>
          </div>  
          <?php endif; ?>
        
          <center><div class="adr">
            <div class="street-address"><?php print $contact['street_address'];?></div>
            <span class="locality"><?php print $contact['city'];?></span><?php if($contact['city'] != '') : ?>,<?php endif; ?>  
            <abbr class="region" title="<?php print $contact['state_province'];?>"><?php print $contact['state_province'];?></abbr>&nbsp;
            <span class="postal-code"><?php print $contact['postal_code'];?></span>
            <div class="country-name"><?php print $contact['country'];?></div>
          </div></center>
        </div>
        <div><?php print $contact['group'];?></div>
        <div><?php print $contact['tag'];?></div>
        
        <?php
        civicrm_initialize( );
        require_once 'CRM/Core/Config.php';
        $config =& CRM_Core_Config::singleton( );
        $symbol = $config->defaultCurrencySymbol;
        ?>
        
        <?php if ($member_results['count'] > 0) :?>
        <div data-role="content">
        <!--<h3>Memberships</h3>
        <p>--> 
        <ul id="main-memberships-list" data-role="listview" data-inset="true" >
        <li data-role="list-divider">Memberships</li>
         <?php 	
         $memberships  = $member_results['values'];
         foreach($memberships as $key => $membership) { ?>
            <li role="option" tabindex="-1" data-theme="c" id="membership-<?php print $memberships['id']; ?>" >
                <a href="<?php print url('civicrm/mobile/membership/').$membership['id']; ?>" data-transition="slideup" data-role="memberships-<?php print $membership['id']; ?>">
                <?php print $membership['membership_name']; ?></a>
            </li>
            
         <?php } ?>
         </ul>
         <!--</p>-->
         </div>
        <?php endif; ?>
        
        <?php if ($contrib_results['count'] > 0) :?>
        <div data-role="content">
        <!--<h3>Contributions</h3>
        <p>--> 
        <ul id="main-contributions-list" data-role="listview" data-inset="true" >
        <li data-role="list-divider">Contributions</li>
         <?php 	
         $contributions  = $contrib_results['values'];
         foreach($contributions as $key => $contribution) { ?>
            <li role="option" tabindex="-1" data-theme="c" id="contribution-<?php print $contribution['id']; ?>" >
                <a href="<?php print url('civicrm/mobile/contribution/').$contribution['id']; ?>" data-transition="slideup" data-role="contribution-<?php print $contribution['id']; ?>">
                <?php print $contribution['contribution_type'].' - '.$symbol.' '.$contribution['total_amount']; ?></a>
            </li>
            
         <?php } ?>
         </ul>
         <!--</p>-->
         </div>
        <?php endif; ?>
        
        
        <?php if ($event_results['count'] > 0) :?>
        <div data-role="content">
        <!--<h3>Events</h3>
        <p>--> 
        <ul id="main-events-list" data-role="listview" data-inset="true" >
        <li data-role="list-divider">Events</li>
         <?php 	
         $events  = $event_results['values'];
         foreach($events as $key => $event) { ?>
            <li role="option" tabindex="-1" data-theme="c" id="event-<?php print $event['id']; ?>" >
                <a href="<?php print url('civicrm/mobile/participant/').$event['id']; ?>" data-transition="slideup" data-role="event-<?php print $event['id']; ?>">
                <?php print $event['event_title']; ?></a>
            </li>
            
         <?php } ?>
         </ul>
         <!--</p>-->
         </div>
        <?php endif; ?>
        
        <?php if ($activity_results['count'] > 0 OR $relationship_results['count'] > 0 OR $note_results['count'] > 0 OR $case_results['count'] > 0) :?>
        <div data-role="collapsible" data-collapsed="true" data-theme="e">
        <h3>More</h3>
        <p> 
          <ul id="main-events-list" data-role="listview" data-inset="true" >
            
            <?php if ($activity_results['count'] > 0) :?>
            <li role="option" tabindex="-1" data-theme="c" id="activities-<?php print $contact_id; ?>" >
                <a href="<?php print url('civicrm/mobile/contact/activities/').$contact_id; ?>" data-transition="slideup">Activities</a>
            </li>
            <?php endif; ?>
            
            <?php if ($relationship_results['count'] > 0) :?>
            <li role="option" tabindex="-1" data-theme="c" id="relationships-<?php print $contact_id; ?>" >
                <a href="<?php print url('civicrm/mobile/contact/relationships/').$contact_id; ?>" data-transition="slideup">Relationships</a>
            </li>
            <?php endif; ?>
            
            <?php if ($note_results['count'] > 0) :?>
            <li role="option" tabindex="-1" data-theme="c" id="notes-<?php print $contact_id; ?>" >
                <a href="<?php print url('civicrm/mobile/contact/notes/').$contact_id; ?>" data-transition="slideup">Notes</a>
            </li>
            <?php endif; ?>
            
            <?php if ($case_results['count'] > 0) :?>
            <li role="option" tabindex="-1" data-theme="c" id="cases-<?php print $contact_id; ?>" >
                <a href="<?php print url('civicrm/mobile/contact/cases/').$contact_id; ?>" data-transition="slideup">Cases</a>
            </li>
            <?php endif; ?>
            
          </ul>
        </p>  
        </div>
        <?php endif; ?>        
    </div> 

  <div> 
          <a href="#contactslist" data-ajax="true" data-role="button" data-transition="slideup" >Back to contact list</a>
  </div>  
 

                <script language="javascript" type="text/javascript">
                function getLocation() {
				// Get location no more than 10 minutes old. 600000 ms = 10 minutes.
                    navigator.geolocation.getCurrentPosition(showLocation, showError, {enableHighAccuracy:true,maximumAge:600000});
                }
 
                function showError(error) {
                    alert(error.code + ' ' + error.message);
                }
 
                function showLocation(position) {
                    geoinfo.innerHTML='<p>Latitude: ' + position.coords.latitude + '</p>' 
                        + '<p>Longitude: ' + position.coords.longitude + '</p>' 
                        + '<p>Accuracy: ' + position.coords.accuracy + '</p>' 
                        + '<p>Altitude: ' + position.coords.altitude + '</p>' 
                        + '<p>Altitude accuracy: ' + position.coords.altitudeAccuracy + '</p>' 
                        + '<p>Speed: ' + position.coords.speed + '</p>' 
                        + '<p>Heading: ' + position.coords.heading + '</p>';
                }
        
                </script>
</div> 

<?php require('civimobile.footer.php'); ?>
