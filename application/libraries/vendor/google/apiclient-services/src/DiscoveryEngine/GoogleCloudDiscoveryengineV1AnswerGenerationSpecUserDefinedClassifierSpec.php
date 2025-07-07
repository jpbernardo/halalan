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

namespace Google\Service\DiscoveryEngine;

class GoogleCloudDiscoveryengineV1AnswerGenerationSpecUserDefinedClassifierSpec extends \Google\Model
{
  /**
   * @var bool
   */
  public $enableUserDefinedClassifier;
  /**
   * @var string
   */
  public $modelId;
  /**
   * @var string
   */
  public $preamble;
  /**
   * @var int
   */
  public $seed;
  /**
   * @var string
   */
  public $taskMarker;
  public $temperature;
  /**
   * @var string
   */
  public $topK;
  public $topP;

  /**
   * @param bool
   */
  public function setEnableUserDefinedClassifier($enableUserDefinedClassifier)
  {
    $this->enableUserDefinedClassifier = $enableUserDefinedClassifier;
  }
  /**
   * @return bool
   */
  public function getEnableUserDefinedClassifier()
  {
    return $this->enableUserDefinedClassifier;
  }
  /**
   * @param string
   */
  public function setModelId($modelId)
  {
    $this->modelId = $modelId;
  }
  /**
   * @return string
   */
  public function getModelId()
  {
    return $this->modelId;
  }
  /**
   * @param string
   */
  public function setPreamble($preamble)
  {
    $this->preamble = $preamble;
  }
  /**
   * @return string
   */
  public function getPreamble()
  {
    return $this->preamble;
  }
  /**
   * @param int
   */
  public function setSeed($seed)
  {
    $this->seed = $seed;
  }
  /**
   * @return int
   */
  public function getSeed()
  {
    return $this->seed;
  }
  /**
   * @param string
   */
  public function setTaskMarker($taskMarker)
  {
    $this->taskMarker = $taskMarker;
  }
  /**
   * @return string
   */
  public function getTaskMarker()
  {
    return $this->taskMarker;
  }
  public function setTemperature($temperature)
  {
    $this->temperature = $temperature;
  }
  public function getTemperature()
  {
    return $this->temperature;
  }
  /**
   * @param string
   */
  public function setTopK($topK)
  {
    $this->topK = $topK;
  }
  /**
   * @return string
   */
  public function getTopK()
  {
    return $this->topK;
  }
  public function setTopP($topP)
  {
    $this->topP = $topP;
  }
  public function getTopP()
  {
    return $this->topP;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDiscoveryengineV1AnswerGenerationSpecUserDefinedClassifierSpec::class, 'Google_Service_DiscoveryEngine_GoogleCloudDiscoveryengineV1AnswerGenerationSpecUserDefinedClassifierSpec');
