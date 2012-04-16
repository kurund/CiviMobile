<?php 
require_once('civimobile.header.php');
require_once('initialise.php');


//Get the id from the URL
$path=$_SERVER['REQUEST_URI'];
$split = explode ( "/" , $path );
$id = end($split);
//api call to get fields from profile
$params=array('version' =>'3','uf_group_id' => $id);
$results=civicrm_api("UFField","get", $params);

$results=$results['values'];

foreach($results as $result){
//	print_r($result['field_name']."\n");
//api call to get profile details

$ufgroup_results=civicrm_api("UFGroup","get", array ('version' =>'3','id' => $id));
$profile_title=$ufgroup_results['values'][$id]['title'];
}
//print_r($results);exit;
?>
<div data-role="page">

	<div data-role="header">
		<h1><?php print_r($profile_title); ?></h1>
		<a href="#" id="back-profile-button"data-rel="back" class="ui-btn-left" data-icon="arrow-l">Back</a>
	</div><!-- /header -->
	<div data-role="content" id="profile-<?php print_r($id); ?>">
		<div id="profile-<?php print_r($id); ?>-fields">
			<?php foreach($results as $result){
				print_r('<div data-role="fieldcontain">
			        		<input type="text" name="'.$result['field_name'].'" id="'.$result['field_name'].'-'.$id.'" value="" placeholder="'.$result['label'].'" />
			    		</div>');
			}?>
	    </div>
	</div><!-- /content -->
	<?php require_once('civimobile.navbar.php') ?>
</div><!-- /page -->


<script>
// var contactId = <?php echo $id; ?>;
// var contact = <?php echo json_encode($results); ?>;

$( function(){

});


</script>

<?php require_once('civimobile.footer.php')?>
