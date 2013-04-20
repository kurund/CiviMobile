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
}
