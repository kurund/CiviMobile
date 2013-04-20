<?php
if ( CRM_Utils_Array::value( 'locale', $GLOBALS ) ) {
  $civimobile_vars['language'] = $GLOBALS['locale'];
}
else {
  $civimobile_vars['language'] = 'en_US';
}

$civimobile_vars['title'] = 'CiviMobile';

$config =& CRM_Core_Config::singleton();
$civimobile_vars['civicrm_resourceURL'] = $config->userFrameworkResourceURL . DIRECTORY_SEPARATOR;

$session =& CRM_Core_Session::singleton();
$civimobile_vars['loggedInContactID'] = $session->get('userID');

// extension include path
$includePath = $config->extensionsURL . DIRECTORY_SEPARATOR . 'com.webaccessglobal.module.civimobile' . DIRECTORY_SEPARATOR;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $civimobile_vars['language'] ?>" lang="<?php print $civimobile_vars['language'] ?>" >
<head>
  <title><?php print $civimobile_vars['title'];?></title>
  <?php //print $civimobile_page_settings['favicon'] ?>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" >
  <link rel="stylesheet" href="<?php print $includePath;?>libraries/jquery.mobile-1.3.1/jquery.mobile-1.3.1.min.css" />
  <script type="text/javascript" src="<?php print $includePath; ?>libraries/jquery.mobile-1.3.1/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="<?php print $includePath; ?>libraries/jquery.mobile-1.3.1/jquery.mobile-1.3.1.min.js"></script>
  <script type="text/javascript" src="<?php print $includePath; ?>js/common.js"></script>
  <script type="text/javascript" src="<?php print $civimobile_vars['civicrm_resourceURL']; ?>js/rest.js"></script>
  <script type="text/javascript">
    CRM.url('init', '<?php print CRM_Utils_System::url('civicrm/example', 'placeholder', true, null, false);?>');
  </script>
  <script type="text/javascript" src="<?php print $includePath; ?>js/events.js"></script>
</head>
<body>
