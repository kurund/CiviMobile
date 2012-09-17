
<?php 
require_once('civimobile.header.php'); 
 $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

$parse_url = parse_url($url, PHP_URL_PATH);
      // get last arg of path (contact id)
    $event_id = arg(4);
 $results = civicrm_api("Participant","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'event_id' => $event_id)
                            );
$contact = $results['values'];
$contact_arr = array();
foreach ($contact as $key => $value){
    $contact_arr[$key] = $contact[$key]['contact_id'];
}
$params  = array ('version' =>'3','id' => $event_id,);
   $results = civicrm_api("Event","get",$params);
$event_name = $results['values'][$event_id]['event_title'];

?>
<div data-role="page" data-theme="b" id="participants"> 
	<div id="jqm-participants" data-role="header">
	    <h3>Participants in Event <?php echo $event_name ?></h3>
	   <a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-left jqm-home">Home</a>
	</div> 
	
	<div data-role="content" id="participants-content"> 
        <ul id="contacts" class="participants-list" data-role="listview" data-filter="true">
<?php
  //$result=array();
foreach ($contact_arr as $key => $value){
 $result = civicrm_api("Contact","get", 
                    array ( 'sequential' =>'1', 
                            'version'=>3, 
                            'contact_id' => $value)
                            );
 $cont  = $result['values'];
foreach($cont as $key => $contact) { ?>
     <li role="option" tabindex="-1" data-theme="c" id="contact-<?php print $contact['contact_id']; ?>" >
       <a href="<?php print url('civicrm/mobile/contact/').$contact['contact_id']; ?>" data-transition="slideup" data-role="participants-<?php print $event['id']; ?>">
       <?php print $contact['display_name']; ?></a>
                                          </li>
<?php
}
}
?>
</ul>
	</div>


</div> 

<? require('civimobile.footer.php'); ?>
<script>


</script>