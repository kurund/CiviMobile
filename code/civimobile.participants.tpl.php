<?php 
require_once('civimobile.header.php'); 
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

$parse_url = parse_url($url, PHP_URL_PATH);
// get last arg of path (contact id)
$event_id = arg(4);
$results = civicrm_api("Participant","get", 
array ( 'sequential' =>'1', 
'version'=>3, 
'event_id' => $event_id,
'participant_status_id' => '1')
);
$participants = $results['values'];
$params  = array ('version' =>'3','id' => $event_id,);
$results = civicrm_api("Event","get",$params);
$event_name = $results['values'][$event_id]['event_title'];

?>
<div data-role="page" data-theme="b" id="participants"> 
	<div id="jqm-participants" data-role="header">
		<h3>Participants for <?php echo $event_name ?></h3>
		<a href="#menu" data-direction="reverse" data-role="button" data-icon="home" data-transition="slideup" data-iconpos="notext" class="ui-btn-left jqm-home">Home</a>
	</div> 

	<div data-role="content" id="participants-content"> 
		<ul id="contacts" class="participants-list" data-role="listview" data-filter="true">
		<?php
		//$result=array();
		foreach ($participants as $participant){ ?>
			<li role="option" tabindex="-1" data-theme="c" data-icon="check" id="check-in-participant-<?php print $participant['participant_id']; ?>" data-participant-id="<?php print $participant['participant_id']; ?>" data-participant-status-id="<?php print $participant['participant_status_id']; ?>">
				<a href="#" data-role="participants-<?php print $event['id']; ?>">
					<?php print $participant['display_name']; ?></a>
				</li>
		<?php 
		}
		?>
		</ul>
	</div>
	<div data-role="footer" data-id="global-footer" data-position="fixed" data-theme="c">
	  <a id=undo-checkin-button style="width:100%; display:none" href="#" d ata-role="button" data-mini="true" data-icon="back" data-theme="e" data-participant-id="" data-prevous-participant-status-id="">Undo checkin</a>
	</div>
</div> 
<? require('civimobile.footer.php'); ?>

<script>

$( function(){
	$("[id^=check-in-participant-]").click(function(event){
		pid = $(this).attr('data-participant-id');
		checkinParticipant($(this).attr('data-participant-id'),$(this).attr('data-participant-status-id'));
	});
});
		
function checkinParticipant(pid,psid){
	$.mobile.showPageLoadingMsg( 'Searching' );	
	$("#undo-checkin-button").hide();
	$('#check-in-participant-'+pid+' a').css("color","#c7c7c7");
	$().crmAPI ('Participant','update',{'version' :'3', 'event_id' : <?php print $event_id; ?>, 'participant_id' : pid, 'participant_status_id' : '1' }
	,{
		ajaxURL: crmajaxURL,
		success:function (data){
			//remove colour	  	
			$('#check-in-participant-'+pid+' a').css("color","");
			//hide item children
			$('#check-in-participant-'+pid).children().hide();
			//need to figure out how to change button text before it get displayed.
			$("#undo-checkin-button").show();
			$("#contacts").listview('refresh');
			$("#undo-checkin-button").attr('data-participant-id', pid);
			$("#undo-checkin-button").attr('data-previous-participant-status-id', psid);
			//need to figure out how to cancel existing delays
			$("#undo-checkin-button").delay(10000).fadeOut("normal", function() {
				// need to remove row so that we don't end up with a weird thick line betwen ajacent rows this is link to above issue with cancelling existing delays
				$("#contacts").listview('refresh');  
			});
			$("#undo-checkin-button").click(function(event){
				undoCheckinParticipant($(this).attr('data-participant-id'),$(this).attr('data-previous-participant-status-id'));
			});
			$.mobile.hidePageLoadingMsg( 'Searching' );
		}
	});
}

function undoCheckinParticipant(pid,ppsid){
	$.mobile.showPageLoadingMsg( 'Searching' );
	$().crmAPI ('Participant','update',{'version' :'3', 'event_id' : <?php print $event_id; ?>, 'participant_id' : pid, 'participant_status_id' : ppsid }
	,{
		ajaxURL: crmajaxURL,
		success:function (data){
			//show checkin row
			$('#check-in-participant-'+pid).children().show();
			$("#undo-checkin-button").hide();
			$("#participants-list").listview('refresh');
			$.mobile.hidePageLoadingMsg( 'Searching' );
		}
	});
}

</script>