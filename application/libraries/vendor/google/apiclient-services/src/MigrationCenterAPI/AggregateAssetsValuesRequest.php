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

namespace Google\Service\MigrationCenterAPI;

class AggregateAssetsValuesRequest extends \Google\Collection
{
  protected $collection_key = 'aggregations';
  protected $aggregationsType = Aggregation::class;
  protected $aggregationsDataType = 'array';
  /**
   * @var string
   */
  public $filter;
  /**
   * @var bool
   */
  public $showHidden;

  /**
   * @param Aggregation[]
   */
  public function setAggregations($aggregations)
  {
    $this->aggregations = $aggregations;
  }
  /**
   * @return Aggregation[]
   */
  public function getAggregations()
  {
    return $this->aggregations;
  }
  /**
   * @param string
   */
  public function setFilter($filter)
  {
    $this->filter = $filter;
  }
  /**
   * @return string
   */
  public function getFilter()
  {
    return $this->filter;
  }
  /**
   * @param bool
   */
  public function setShowHidden($showHidden)
  {
    $this->showHidden = $showHidden;
  }
  /**
   * @return bool
   */
  public function getShowHidden()
  {
    return $this->showHidden;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AggregateAssetsValuesRequest::class, 'Google_Service_MigrationCenterAPI_AggregateAssetsValuesRequest');
