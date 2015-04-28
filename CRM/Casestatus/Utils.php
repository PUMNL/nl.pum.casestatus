<?php
/**
 * Class with Util functions
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 22 April 2015
 * @license AGPL-3.0
 */

class CRM_Casestatus_Utils {

  /**
   * Method to get the option group id with name
   *
   * @param string $optionGroupName
   * @return int $optionGroupId
   * @access public
   * @static
   */
  public static function getOptionGroupIdWithName($optionGroupName) {
    $optionGroupId = 0;
    if (!empty($optionGroupName)) {
      $params = array(
        'name' => $optionGroupName,
        'is_active' => 1,
        'return' => 'id');
      try {
        $optionGroupId = civicrm_api3('OptionGroup', 'Getvalue', $params);
      } catch (CiviCRM_API3_Exception $ex) {
      }
    }
    return $optionGroupId;
  }

  /**
   * Method to get case type id with case type name
   *
   * @param string $caseTypeName
   * @return array|null
   * @throws Exception when error from API
   * @access public
   * @static
   */
  public static function getCaseTypeIdWithName($caseTypeName) {
    $caseTypeId = null;
    if (!empty($caseTypeName)) {
      $optionValueParams = array(
        'option_group_id' => self::getOptionGroupIdWithName('case_type'),
        'name' => $caseTypeName,
        'return' => 'value'
      );
      try {
        $caseTypeId = civicrm_api3('OptionValue', 'Getvalue', $optionValueParams);
      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not find a case type with name '.$caseTypeName
          .', error from API OptionValue Getvalue: '.$ex->getMessage());
      }
    }
    return $caseTypeId;
  }

  /**
   * Method to get case status id with case status name
   *
   * @param string $caseStatusName
   * @return array|null
   * @throws Exception when error from API
   * @access public
   * @static
   */
  public static function getCaseStatusIdWithName($caseStatusName) {
    $caseStatusId = null;
    if (!empty($caseStatusName)) {
      $optionValueParams = array(
        'option_group_id' => self::getOptionGroupIdWithName('case_status'),
        'name' => $caseStatusName,
        'return' => 'value'
      );
      try {
        $caseStatusId = civicrm_api3('OptionValue', 'Getvalue', $optionValueParams);
      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not find a case status with name '.$caseStatusName
          .', error from API OptionValue Getvalue: '.$ex->getMessage());
      }
    }
    return $caseStatusId;
  }
}