<?php
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    $parse_url = parse_url($url, PHP_URL_PATH);
    
    // get last arg of path (contact id)
    $contact_id = arg(4);
    $results = civicrm_api("Contact","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'contact_id' => $contact_id, 
                            'return' =>'display_name,email,phone,tag,group,contact_type,street_address,city,postal_code,state_province')
                            );
    $contact = $results['values'][0];
    
    $note_results = civicrm_api("Note","get",
                            array ( 'sequential' =>'1', 
                                    'version'=>3,
                                    'entity_table' => 'civicrm_contact', 
                                    'entity_id' => $contact_id) 
                                    );                                    
    include('civimobile.header.php');
?>
<div data-role="page" data-theme="c" id="jqm-contacts">

 <div data-role="header" data-theme="a">
    <a href="#" data-rel="back" class="ui-btn-left" data-icon="arrow-l" data-transition="slideup">Back</a>
    <h3><?php print $contact['display_name'];?></h3>
 <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-right jqm-home">Home</a>
  </div><!-- /header -->
	
	<div data-role="content" id="contact-content"> 
        <?php if ($note_results['count'] > 0) :?>
        <div data-role="content">
        <ul id="main-notes-list" data-role="listview" data-inset="true" >
        <li data-role="list-divider">Notes</li>
         <?php 	
         $notes  = $note_results['values'];
         foreach($notes as $key => $note) { ?>
            <li role="option" tabindex="-1" data-theme="c" id="note-<?php print $note['id']; ?>" >
                <?php print $note['note']; ?>
            </li>
            
         <?php } ?>
         </ul>
         </div>
        <?php endif; ?>
    </div> 
</div> 

<?php require('civimobile.footer.php'); ?>
