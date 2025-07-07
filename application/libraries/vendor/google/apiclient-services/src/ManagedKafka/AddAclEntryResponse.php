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

namespace Google\Service\ManagedKafka;

class AddAclEntryResponse extends \Google\Model
{
  protected $aclType = Acl::class;
  protected $aclDataType = '';
  /**
   * @var bool
   */
  public $aclCreated;

  /**
   * @param Acl
   */
  public function setAcl(Acl $acl)
  {
    $this->acl = $acl;
  }
  /**
   * @return Acl
   */
  public function getAcl()
  {
    return $this->acl;
  }
  /**
   * @param bool
   */
  public function setAclCreated($aclCreated)
  {
    $this->aclCreated = $aclCreated;
  }
  /**
   * @return bool
   */
  public function getAclCreated()
  {
    return $this->aclCreated;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AddAclEntryResponse::class, 'Google_Service_ManagedKafka_AddAclEntryResponse');
