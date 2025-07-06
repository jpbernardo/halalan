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

namespace Google\Service\Dataform;

class LoadConfig extends \Google\Model
{
  protected $appendType = SimpleLoadMode::class;
  protected $appendDataType = '';
  protected $maximumType = IncrementalLoadMode::class;
  protected $maximumDataType = '';
  protected $replaceType = SimpleLoadMode::class;
  protected $replaceDataType = '';
  protected $uniqueType = IncrementalLoadMode::class;
  protected $uniqueDataType = '';

  /**
   * @param SimpleLoadMode
   */
  public function setAppend(SimpleLoadMode $append)
  {
    $this->append = $append;
  }
  /**
   * @return SimpleLoadMode
   */
  public function getAppend()
  {
    return $this->append;
  }
  /**
   * @param IncrementalLoadMode
   */
  public function setMaximum(IncrementalLoadMode $maximum)
  {
    $this->maximum = $maximum;
  }
  /**
   * @return IncrementalLoadMode
   */
  public function getMaximum()
  {
    return $this->maximum;
  }
  /**
   * @param SimpleLoadMode
   */
  public function setReplace(SimpleLoadMode $replace)
  {
    $this->replace = $replace;
  }
  /**
   * @return SimpleLoadMode
   */
  public function getReplace()
  {
    return $this->replace;
  }
  /**
   * @param IncrementalLoadMode
   */
  public function setUnique(IncrementalLoadMode $unique)
  {
    $this->unique = $unique;
  }
  /**
   * @return IncrementalLoadMode
   */
  public function getUnique()
  {
    return $this->unique;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LoadConfig::class, 'Google_Service_Dataform_LoadConfig');
