<?php  // $Id: page.tpl.php,v 1.0 kylejaster Exp $ 
  if ( CRM_Utils_Array::value( 'locale', $GLOBALS ) ) { 
      $civimobile_vars['language'] = $GLOBALS['locale'];
    } else {
      $civimobile_vars['language'] = 'en_US';
    }

    $civimobile_vars['title']   = 'CiviMobile';
    //$civimobile_vars['head']    = drupal_get_html_head();
    $civimobile_vars['favicon'] = theme_get_setting("toggle_favicon") ? "<link rel=\"shortcut icon\" href=\"". theme_get_setting("favicon") ."\" type=\"image/x-icon\"/>\n" : "";
    //$civimobile_vars['styles']   = drupal_get_css();
    //$civimobile_vars['scripts']   = drupal_get_js();
    $civimobile_vars['civimobile_assets'] = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
    $civimobile_vars['civimobile_assets'] =  str_replace($_SERVER['DOCUMENT_ROOT'] ,'',$civimobile_vars['civimobile_assets']);
    $config =& CRM_Core_Config::singleton();
    $civimobile_vars['civicrm_base']   = $config->userFrameworkResourceURL;
    $civimobile_page_settings = $civimobile_vars;

if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')):
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
     <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $civimobile_page_settings['language'] ?>" lang="<?php print $civimobile_page_settings['language'] ?>" >
     <head>
     <title><?php print $civimobile_page_settings['title'];?></title>
     <?php //print $civimobile_page_settings['head'] ?>
     <?php print $civimobile_page_settings['favicon'] ?>
     <?php //print $civimobile_page_settings['styles'] ?>
     <?php //print $civimobile_page_settings['scripts'] ?>
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" >
    <link rel="stylesheet" href="<?php print $civimobile_page_settings['civimobile_assets'];?>libraries/jquery.mobile-1.1.1/jquery.mobile-1.1.1.css" />
    <script src="<?php print $civimobile_page_settings['civimobile_assets']; ?>libraries/jquery.mobile-1.1.1/jquery.mobile-1.1.1.min.js"></script>
    <script src="<?php print $civimobile_page_settings['civicrm_base']; ?>js/rest.js"></script>
    <script>
    var crmajaxURL = '<?php print base_path(); ?>civicrm/ajax/rest';
var base_url =  '<?php print base_path(); ?>';
</script>
<script src="<?php print $civimobile_page_settings['civimobile_assets'];?>/js/base.js"></script>
  </head>
  <body> 
  <?php endif; 
global $base_url;
function navbar ($back = false) {
  if ($back) { 
    $ajax="true";
  } else {
    $ajax="false";
  }  
}
?>


