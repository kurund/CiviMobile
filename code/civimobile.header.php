<?php  
if ( CRM_Utils_Array::value( 'locale', $GLOBALS ) ) { 
  $civimobile_vars['language'] = $GLOBALS['locale'];
} 
else {
  $civimobile_vars['language'] = 'en_US';
}

$civimobile_vars['title']   = 'CiviMobile';
//$civimobile_vars['favicon'] = theme_get_setting("toggle_favicon") ? "<link rel=\"shortcut icon\" href=\"". theme_get_setting("favicon") ."\" type=\"image/x-icon\"/>\n" : "";

$config =& CRM_Core_Config::singleton();
$civimobile_vars['civicrm_base']   = $config->userFrameworkResourceURL . DIRECTORY_SEPARATOR;

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
    <link rel="stylesheet" href="<?php print $includePath;?>libraries/jquery.mobile-1.2.0-beta.1/jquery.mobile-1.2.0-beta.1.min.css" />
    <script src="<?php print $includePath; ?>libraries/jquery.mobile-1.2.0-beta.1/jquery-1.8.1.min.js"></script>
    <script src="<?php print $includePath; ?>libraries/jquery.mobile-1.2.0-beta.1/jquery.mobile-1.2.0-beta.1.js"></script>
    <script src="<?php print $civimobile_vars['civicrm_base']; ?>packages/jquery/plugins/jquery.mustache.js"></script>
    <script src="<?php print $civimobile_vars['civicrm_base']; ?>js/rest.js"></script>
    <script>
      var crmajaxURL = '<?php print base_path(); ?>civicrm/ajax/rest';
      var base_url =  '<?php print base_path(); ?>';
    </script>
  </head>
  <body> 
