<?php  
if ( CRM_Utils_Array::value( 'locale', $GLOBALS ) ) { 
  $civimobile_vars['language'] = $GLOBALS['locale'];
} 
else {
  $civimobile_vars['language'] = 'en_US';
}

$civimobile_vars['title'] = 'CiviMobile';

$config =& CRM_Core_Config::singleton();
$civimobile_vars['civicrm_base'] = $config->userFrameworkResourceURL . DIRECTORY_SEPARATOR;

$session =& CRM_Core_Session::singleton();
$civimobile_vars['loggedInContactID'] = $session->get('userID');

// extension include path
$includePath = $config->extensionsURL . 'com.webaccessglobal.module.civimobile' . DIRECTORY_SEPARATOR;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $civimobile_vars['language'] ?>" lang="<?php print $civimobile_vars['language'] ?>" >
  <head>
    <title><?php print $civimobile_vars['title'];?></title>
    <?php //print $civimobile_page_settings['favicon'] ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="stylesheet" href="<?php print $includePath;?>libraries/jquery.mobile-1.2.0-rc.2/jquery.mobile-1.2.0-rc.2.min.css" />
    <script src="<?php print $includePath; ?>libraries/jquery.mobile-1.2.0-rc.2/jquery-1.8.2.min.js"></script>
    <script src="<?php print $includePath; ?>libraries/jquery.mobile-1.2.0-rc.2/jquery.mobile-1.2.0-rc.2.js"></script>
    <script src="<?php print $includePath; ?>js/common.js"></script>
    <script src="<?php print $civimobile_vars['civicrm_base']; ?>packages/jquery/plugins/jquery.mustache.js"></script>
    <script src="<?php print $civimobile_vars['civicrm_base']; ?>js/rest.js"></script>
    <script>
      var crmajaxURL = '<?php print base_path(); ?>civicrm/ajax/rest';
      var base_url =  '<?php print base_path(); ?>';
      var cmurl = '<?php print url('civicrm/mobile/contact/')?>';
			var profileId = 1;
    </script>
    <script src="<?php print $includePath; ?>js/events.js"></script>
  </head>
  <body> 
