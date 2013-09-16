<?php

class civimobile_Form_Settings extends CRM_Core_Form {
  function buildQuickForm() {
    // Build list of options for individual, org and household
    // contact types.
    $ind_profile_options = array();
    $house_profile_options = array();
    $org_profile_options = array();
    try {
      $params = array('rowCount' => 200);
      $result = civicrm_api3('UFGroup', 'get', $params); 
    } 
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      $session = CRM_Core_Session::singleton();
      $session->setStatus(ts("Failed to get list of profiles."));
      return;
    }
    reset($result['values']);
    while(list($k,$v) = each($result['values'])) {
      if(array_key_exists('group_type', $v) && $v['is_active'] == 1) {
        if(is_array($v['group_type'])) {
          // Just check the first one (this is arbitrary)
          $group_type = array_pop($v['group_type']); 
        } 
        else {
          $group_type = $v['group_type'];
        }
        $id = $v['id'];
        if(preg_match('/Individual/', $group_type)) {
          $ind_profile_options[$id] = $v['title'];
        } 
        if(preg_match('/Houeshold/', $group_type)) {
          $house_profile_options[$id] = $v['title'];
        }
        if(preg_match('/Organization/', $group_type)) {
          $org_profile_options[$id] = $v['title'];
        }
      }
    }
    if(!empty($ind_profile_options)) {
      $this->addElement('select', 'ind_profile_id', ts('Individual'), $ind_profile_options, NULL);
    }
    if(!empty($house_profile_options)) {
      $this->addElement('select', 'house_profile_id', ts('Household'), $house_profile_options, NULL);
    }
    if(!empty($org_profile_options)) {
      $this->addElement('select', 'org_profile_id', ts('Organization'), $org_profile_options, NULL);
    }
    $this->addButtons(
      array(
        array(
          'type' => 'next',
          'name' => ts('Save'),
          'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          'isDefault' => TRUE,
        ),
        array(
          'type' => 'cancel',
          'name' => ts('Cancel'),
        ),
      )
    );
  }

  function setDefaultValues() {
    $defaults = parent::setDefaultValues();
    $group = "CiviCRM Mobile";
    $defaults['ind_profile_id'] = CRM_Core_BAO_Setting::getItem($group, 'ind_profile_id');
    $defaults['org_profile_id'] = CRM_Core_BAO_Setting::getItem($group, 'org_profile_id');
    $defaults['house_profile_id'] = CRM_Core_BAO_Setting::getItem($group, 'house_profile_id');
    return $defaults;
  }

  function postProcess() {
    $group = "CiviCRM Mobile";
    $values = $this->controller->exportValues($this->_name);
    $keys = array('ind_profile_id', 'org_profile_id', 'house_profile_id');
    while(list(,$key) = each($keys)) {
      if(array_key_exists($key, $values)) {
        CRM_Core_BAO_Setting::setItem($values[$key], $group, $key);
      }
    }
    $session = CRM_Core_Session::singleton();
    $session->replaceUserContext(CRM_Utils_System::url('civicrm/admin/setting/mobile'));
  }
}
