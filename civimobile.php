<?php
function civimobile_civicrm_config( &$config ) {
  $tabRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
  // fix php include path
  $include_path = $tabRoot .'code'. PATH_SEPARATOR . get_include_path( );
  set_include_path( $include_path );

  $template =& CRM_Core_Smarty::singleton();

  $extRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
  $extDir = $extRoot . 'templates';

  if ( is_array( $template->template_dir ) ) {
      array_unshift( $template->template_dir, $extDir );
  } else {
      $template->template_dir = array( $extDir, $template->template_dir );
  }
}

function civimobile_civicrm_xmlMenu( &$files ) {
  $files[] = dirname(__FILE__)."/civimobile.xml";
}

function civimobile_civicrm_navigationMenu( &$params ) {
  // get the maximum key of $params
  $maxKey = ( max( array_keys($params) ) );
  $params[$maxKey+1] =  array (
    'attributes' => array (
      'label'      => 'CiviMobile',
      'name'       => 'CiviMobile',
      'url'        => 'civicrm/mobile',
      'permission' => 'administer CiviCRM',
      'operator'   => null,
      'separator'  => null,
      'parentID'   => 2,
      'navID'      => $maxKey+1,
      'active'     => 1
    )
  );
  $key = max(array_keys($params)) + 1;
  // Find the Administer -> Customize Data and Screens
  // part of the navigation menu and make our item
  // and child of it.
  while(list($k,$v) = each($params)) {
    $attributes = CRM_Utils_Array::value('attributes', $v);
    $child = CRM_Utils_Array::value('child', $v);
    if($child && $attributes) {
      $name = CRM_Utils_Array::value('name', $v['attributes']);
      if($name == 'Administer') {
        while(list($k1, $v1) = each($v['child'])) {
          $attributes = CRM_Utils_Array::value('attributes', $v1);
          $child = CRM_Utils_Array::value('child', $v1);
          if($child && $attributes) {
            $name = CRM_Utils_Array::value('name', $v1['attributes']);
            if($name == 'Customize Data and Screens') {
              $params[$k]['child'][$k1]['child'][] = array(
                'attributes' => array(
                  'label' => 'CiviMobile', 
                  'name' => 'CiviMobile Options',
                  'url' => 'civicrm/admin/setting/mobile',
                  'permission' => 'administer CiviCRM',
                  'operator' => '',
                  'separator' => '',
                  'parentID' => $k1, 
                  'navID' => $key, 
                  'active' => 1),
                'child' => NULL,
              );
            } 
          }
        }
      }
    }
  }
}

