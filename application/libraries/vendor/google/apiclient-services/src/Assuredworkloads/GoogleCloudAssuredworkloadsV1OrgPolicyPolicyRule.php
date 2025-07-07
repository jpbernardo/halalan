<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Assuredworkloads;

class GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRule extends \Google\Model
{
  /**
   * @var bool
   */
  public $allowAll;
  /**
   * @var bool
   */
  public $denyAll;
  /**
   * @var bool
   */
  public $enforce;
  protected $valuesType = GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRuleStringValues::class;
  protected $valuesDataType = '';

  /**
   * @param bool
   */
  public function setAllowAll($allowAll)
  {
    $this->allowAll = $allowAll;
  }
  /**
   * @return bool
   */
  public function getAllowAll()
  {
    return $this->allowAll;
  }
  /**
   * @param bool
   */
  public function setDenyAll($denyAll)
  {
    $this->denyAll = $denyAll;
  }
  /**
   * @return bool
   */
  public function getDenyAll()
  {
    return $this->denyAll;
  }
  /**
   * @param bool
   */
  public function setEnforce($enforce)
  {
    $this->enforce = $enforce;
  }
  /**
   * @return bool
   */
  public function getEnforce()
  {
    return $this->enforce;
  }
  /**
   * @param GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRuleStringValues
   */
  public function setValues(GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRuleStringValues $values)
  {
    $this->values = $values;
  }
  /**
   * @return GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRuleStringValues
   */
  public function getValues()
  {
    return $this->values;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRule::class, 'Google_Service_Assuredworkloads_GoogleCloudAssuredworkloadsV1OrgPolicyPolicyRule');
