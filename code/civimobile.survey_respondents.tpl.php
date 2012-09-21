<?php 
 $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

  
$parse_url = parse_url($url, PHP_URL_PATH);
    // get last arg of path (survey id)
$id = arg(3);
?>
<div data-role="page">

	<div data-role="header">
		<h1>Respondants</h1>
		<a href="#" data-rel="back" class="ui-btn-left" data-icon="arrow-l">Back</a>
	</div><!-- /header -->
	
	<div data-role="content" id="respondants-content">
		<div class="content-primary">
		
			<ul data-role="listview" data-theme="c" id="respondants-list">
		    </ul>
		
		</div><!-- /content-primary -->
	</div><!-- /content -->
</div><!-- /page -->
<?php require_once('civimobile.footer.php') ?>

<script>
$( function(){
    var survey_id = "<?php echo $id; ?>";
	console.log(survey_id);
	$().crmAPI ('SurveyVoter','get',{'version': '3', 'id': survey_id}
		,{ success:function (data){
			console.log(data);
			if (data.count == 0) {
				$("#respondants-list").append('<li data-theme="c">No results found</li>');
				$("#respondants-list").listview('refresh');
			}
			else {
				$.each(data.values, function(index, value) {
				$('#respondants-list').append('<li role="option" tabindex="-1" data-theme="c" id="respondants-'+value.voter_id+'" ><a href="'+value.voter_id+'" data-ajax="false">'+value.voter_name+' </a></li>');
				});
			}
			$("#respondants-list").listview('refresh');
		}	
	});
});


</script>