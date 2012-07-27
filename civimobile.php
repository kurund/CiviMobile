<?php
function civimobile_civicrm_config( &$config ) {
  $tabRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
  // fix php include path
  $include_path = $tabRoot .'php'. PATH_SEPARATOR . get_include_path( );
  set_include_path( $include_path );
  }

function civimobile_civicrm_xmlMenu( &$files ) {
  $files[] = dirname(__FILE__)."/civimobile.xml";
}

function civimobile_civicrm_navigationMenu( &$params )
{
  //  Get the maximum key of $params
  $maxKey = ( max( array_keys($params) ) );
  $params[$maxKey+1] =  array (
                               'attributes' => array (
                                                      'label'      => 'CiviCRM Mobile',
                                                      'name'       => 'CiviMobile',
                                                      'url'        => 'civicrm/mobile',
                                                      'permission' => null,
                                                      'operator'   => null,
                                                      'separator'  => null,
                                                      'parentID'   => 2,
                                                      'navID'      => $maxKey+1,
                                                      'active'     => 1
                                                      ),
                                                       
                               )  ;
}

