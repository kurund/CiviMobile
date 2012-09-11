<?php 
class civimobile {
  function home() {
    require_once 'civimobile.home.php';
    exit;
  }

  function contacts() {
    require_once 'civimobile.contacts.tpl.php';
    exit;
  }

  function activities() {
    require_once 'civimobile.contact.activities.tpl.php';
    exit;
  }

  function relationships() {
    require_once 'civimobile.contact.relationships.tpl.php';
    exit;
  }

  function notes() {
    require_once 'civimobile.contact.notes.tpl.php';
    exit;
  }

  function cases() {
    require_once 'civimobile.contact.cases.tpl.php';
    exit;
  }

  function membership() {
    require_once 'civimobile.membership.tpl.php';
    exit;
  }

  function contribution() {
    require_once 'civimobile.contribution.tpl.php';
    exit;
  }

  function participant() {
    require_once 'civimobile.participant.tpl.php';
    exit;
  }

  function event() {
    require_once 'civimobile.participants.tpl.php';
    exit;
  }

  function survey() {
    require_once 'civimobile.survey.tpl.php';
    exit;
  }
}
