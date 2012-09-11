<div id="jqm-homeheader" data-role="header">
   <h1>Upcoming Events</h1>
   <a href="#menu" data-role="button" data-icon="home" data-iconpos="notext" class="ui-btn-left jqm-home">Home</a>
</div> 
<div data-role="content"> 
   <ul id="main-events-list" data-role="listview" data-inset="true" >
<?php 	
$params  = array ('version' =>'3');
$results = civicrm_api("Event","get",$params);
$events  = $results['values'];
foreach($events as $key => $event) { ?>
  <li role="option" tabindex="-1" data-theme="c" id="event-<?php print $event['id']; ?>" >
    <a href="<?php print url('civicrm/mobile/participants/eventid/').$event['id']; ?>" data-role="participants-<?php print $event['id']; ?>" data-transition="slideup">
      <?php print $event['title']; ?>
    </a>
  </li>
  <?php } ?>
  </ul>
</div>

