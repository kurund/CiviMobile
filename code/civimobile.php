<?php
class civimobile {
  static public function home() {
    require_once 'civimobile.home.php';
    exit;
  }

  static public function contacts() {
    $action = $_GET['action'];
    if ( $action == 'view' ) {
      require_once 'civimobile.contact_view.html';
    }
    else {
      require_once 'civimobile.contact.html';
    }
    exit;
  }

  static public function participantCheckin() {
    require_once 'civimobile.participant_checkin.html';
    exit;
  }

  static public function surveyContacts() {
    require_once 'civimobile.survey_contacts.html';
    exit;
  }

  static public function surveyInterview() {
    require_once 'civimobile.survey_interview.html';
    exit;
  }

  static public function login() {
    require_once 'civimobile.login.html';
    exit;
  }

  static public function logout() {
    CRM_Utils_System::logout();
    CRM_Utils_System::redirect( CRM_Utils_System::url('civicrm/mobile') );
    CRM_Utils_System::civiExit();
  }

  static public function getProfileId($contact_type) {
    // Set variables based on contact type.
    if($contact_type == 'Individual') {
      $user_preference_key = 'ind_profile_id';
      $reserved_profile_name = 'new_individual';
      $default = 3;
    }
    elseif($contact_type == 'Organization') {
      $user_preference_key = 'org_profile_id';
      $reserved_profile_name = 'new_organization';
      $default = 4;
    }
    elseif($contact_type == 'Household') {
      $user_preference_key = 'house_profile_id';
      $reserved_profile_name = 'new_household';
      $default = 5;
    }
    // First try to get custom value set for this installation.
    $params = array(
      'name' => $user_preference_key,
    );
    try {
      $ret = civicrm_api3('Setting', 'getvalue', $params);
    }
    catch (CiviCRM_API3_Exception $e) {
      // What do we do with errors?
      $ret = False;
    }

    if(!empty($ret)) return $ret;

    // If we don't have a value, use the reserved profile
    $params = array(
      'return' => 'id',
      'name' => $reserved_profile_name,
    );
    try {
      $ret = civicrm_api3('uf_group', 'getvalue', $params);
    }
    catch (CiviCRM_API3_Exception $e) {
      // What do we do with errors?
      // Revert to hard coded ids
      $ret = $default;
    }
    if(!empty($ret)) return $ret;
    return $default;
  }
}
