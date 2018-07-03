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
  protected $debriefingActivityTypes = array();
  protected $debriefingCaseTypes = array();
  protected $executionCaseStatusId = null;
  protected $scheduledActivityStatusId = null;

  /**
   * Method to get case type default status
   *
   * @return array
   * @access public
   */
  public function getDefaultCaseStatus() {
    return $this->caseTypeDefaultStatusIds;
  }

  /**
   * Method to get the execution case status id
   *
   * @return int
   * @access public
   */
  public function getExecutionCaseStatusId() {
    return $this->executionCaseStatusId;
  }

  /**
   * Method to get the scheduled case status id
   *
   * @return int
   * @access public
   */
  public function getScheduledActivityStatusId() {
    return $this->scheduledActivityStatusId;
  }

  /**
   * Method to get the array with case types for which the debriefing activity will be generated
   *
   * @return array
   * @access public
   */
  public function getDebriefingCaseTypes() {
    return $this->debriefingCaseTypes;
  }

  /**
   * Method to get the array with the activity types for debriefing per case type
   *
   * @return array
   * @access public
   */
  public function getDebriefingActvityTypes() {
    return $this->debriefingActivityTypes;
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
      'FactFinding' => 'Preparation',
      'RemoteCoaching' => 'Matching',
      'Seminar' => 'Matching',
      'TravelCase' => 'Open');
    foreach ($caseTypes as $caseTypeName => $caseStatusName) {
      $caseTypeId = CRM_Casestatus_Utils::getCaseTypeIdWithName($caseTypeName);
      $caseStatusId = CRM_Casestatus_Utils::getCaseStatusIdWithName($caseStatusName);
      $this->caseTypeDefaultStatusIds[$caseTypeId] = $caseStatusId;
    }
    $this->setDebriefingCaseTypes();
    $this->setDebriefingActivityTypes();
    $this->setExecutionCaseStatusId();
    $this->setScheduledActivityStatusId();
  }

  /**
   * Method to set the execution case status id
   *
   * @throws Exception if error from API
   * @access private
   */
  private function setExecutionCaseStatusId() {
    $paramsOptionGroup = array('name' => 'case_status', 'return' => 'id');
    try {
      $optionGroupId = civicrm_api3('OptionGroup', 'Getvalue', $paramsOptionGroup);
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find option group with name case_status, '
          . 'error from API OptionGroup Getvalue: '.$ex->getMessage());
    }
    $paramsOptionValue = array(
      'option_group_id' => $optionGroupId,
      'name' => 'Execution',
      'return' => 'value');
    try {
      $this->executionCaseStatusId = civicrm_api3('OptionValue', 'Getvalue', $paramsOptionValue);
    } catch (CiviCRM_API3_Exception $ex) {}
  }

  /**
   * Method to set the execution case status id
   *
   * @throws Exception if error from API
   * @access private
   */
  private function setScheduledActivityStatusId() {
    $paramsOptionGroup = array('name' => 'activity_status', 'return' => 'id');
    try {
      $optionGroupId = civicrm_api3('OptionGroup', 'Getvalue', $paramsOptionGroup);
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find option group with name activity_status, '
        . 'error from API OptionGroup Getvalue: '.$ex->getMessage());
    }
    $paramsOptionValue = array(
      'option_group_id' => $optionGroupId,
      'name' => 'Scheduled',
      'return' => 'value');
    try {
      $this->scheduledActivityStatusId = civicrm_api3('OptionValue', 'Getvalue', $paramsOptionValue);
    } catch (CiviCRM_API3_Exception $ex) {}
  }

  /**
   * Method to set the debriefing case types (title and id)
   *
   * @access private
   */
  private function setDebriefingCaseTypes() {
    $caseTypes = array("Advice", "Seminar", "Business");
    foreach ($caseTypes as $caseType) {
      $optionValue = CRM_Threepeas_Utils::getCaseTypeWithName($caseType);
      $this->debriefingCaseTypes[$optionValue['value']] = $caseType;
    }
  }
  /**
   * Method to set the activity type ids for each debriefing activity (per case type)
   *
   * @access private
   */
  private function setDebriefingActivityTypes() {
    foreach ($this->debriefingCaseTypes as $caseTypeId => $caseTypeName) {
      $activityTypeName = $caseTypeName." Debriefing Expert";
      $activityType = CRM_Threepeas_Utils::getActivityTypeWithName($activityTypeName);
      $this->debriefingActivityTypes[$caseTypeId] = $activityType['value'];
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
