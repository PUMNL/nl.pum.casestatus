<?php

require_once 'casestatus.civix.php';

/**
 * Implements hook_civicrm_post
 *
 * @param string $op
 * @param string $objectName
 * @param integer $objectId
 * @param object $objectRef
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_post
 */
function casestatus_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($objectName == 'Case' && $op == 'create') {
    $caseStatusConfig = CRM_Casestatus_Config::singleton();
    $caseTypeStatus = $caseStatusConfig->getDefaultCaseStatus();
    $caseTypeIdParts = explode(CRM_Core_DAO::VALUE_SEPARATOR, $objectRef->case_type_id);
    $defaultStatusId = false;

    if (isset($caseTypeIdParts[1]) && isset($caseTypeStatus[$caseTypeIdParts[1]])) {
      $defaultStatusId = $caseTypeStatus[$caseTypeIdParts[1]];
    } elseif (isset($caseTypeStatus[$caseTypeIdParts[0]])) {
      $defaultStatusId = $caseTypeStatus[$caseTypeIdParts[0]];
    }
    if ($defaultStatusId != FALSE) {
      $caseParams = array(
        'id' => $objectRef->id,
        'status_id' => $defaultStatusId
      );
      civicrm_api3('Case', 'Create', $caseParams);
    }
  }
  /*
   * issue 2857 for case type Advice en Seminar: if status is execution,
   * generate activity debriefingExpert for 10 days from today
   */
  if ($objectName == "Case" && $op == "edit") {
    CRM_Casestatus_Execution::processExecution($objectId, $objectRef->status_id);
  }
}

/**
 * Implements hook_civicrm_alterAPIPermissions
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterAPIPermissions
 */
function casestatus_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  $permissions['pum_case_status']['get'] = array('access CiviCRM');
}

/**
 * Implements hook_civicrm_buildForm
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function casestatus_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Case_Form_Case') {
    CRM_Core_Region::instance('page-body')->add(array('template' => 'CRM/Casestatus/PumCaseStatus.tpl'));
  }
}
/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function casestatus_civicrm_config(&$config) {
  _casestatus_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function casestatus_civicrm_xmlMenu(&$files) {
  _casestatus_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function casestatus_civicrm_install() {
  _casestatus_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function casestatus_civicrm_uninstall() {
  _casestatus_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function casestatus_civicrm_enable() {
  _casestatus_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function casestatus_civicrm_disable() {
  _casestatus_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function casestatus_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _casestatus_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function casestatus_civicrm_managed(&$entities) {
  _casestatus_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function casestatus_civicrm_caseTypes(&$caseTypes) {
  _casestatus_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function casestatus_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _casestatus_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
