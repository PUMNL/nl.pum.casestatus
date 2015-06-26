<?php
/**
 * Class following Singleton pattern for specific extension configuration
 * for Case Status PUM
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 22 April 2015
 * @license AGPL-3.0
 */

class CRM_Casestatus_Config {
  /*
   * singleton pattern
   */
  static private $_singleton = NULL;
  /*
   * properties for default status per case type
   */
  protected $caseTypeDefaultStatusIds = array();

  /**
   * Method to get case type default status
   *
   * @param int $caseTypeId
   * @return int|bool
   * @access public
   */
  public function getCaseTypeDefaultStatusId($caseTypeId) {
    if (isset($this->caseTypeDefaultStatusIds[$caseTypeId])) {
      return $this->caseTypeDefaultStatusIds[$caseTypeId];
    } else {
      return FALSE;
    }
  }

  /**
   * Constructor method
   *
   * @access public
   */
  function __construct() {
    $caseTypes = array(
      'Advice' => 'Matching',
      'Business' => 'Matching',
      'CTM' => 'Preparation',
      'Grant' => 'Submitted',
      'PDV' => 'Preparation',
      'RemoteCoaching' => 'Matching',
      'Seminar' => 'Matching',
      'TravelCase' => 'Open');
    foreach ($caseTypes as $caseTypeName => $caseStatusName) {
      $caseTypeId = CRM_Casestatus_Utils::getCaseTypeIdWithName($caseTypeName);
      $caseStatusId = CRM_Casestatus_Utils::getCaseStatusIdWithName($caseStatusName);
      $this->caseTypeDefaultStatusIds[$caseTypeId] = $caseStatusId;
    }
  }

  /**
   * Function to return singleton object
   *
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Casestatus_Config();
    }
    return self::$_singleton;
  }

}