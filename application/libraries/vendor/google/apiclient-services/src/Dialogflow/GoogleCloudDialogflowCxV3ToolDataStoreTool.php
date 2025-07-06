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

namespace Google\Service\Dialogflow;

class GoogleCloudDialogflowCxV3ToolDataStoreTool extends \Google\Collection
{
  protected $collection_key = 'dataStoreConnections';
  protected $dataStoreConnectionsType = GoogleCloudDialogflowCxV3DataStoreConnection::class;
  protected $dataStoreConnectionsDataType = 'array';
  protected $fallbackPromptType = GoogleCloudDialogflowCxV3ToolDataStoreToolFallbackPrompt::class;
  protected $fallbackPromptDataType = '';

  /**
   * @param GoogleCloudDialogflowCxV3DataStoreConnection[]
   */
  public function setDataStoreConnections($dataStoreConnections)
  {
    $this->dataStoreConnections = $dataStoreConnections;
  }
  /**
   * @return GoogleCloudDialogflowCxV3DataStoreConnection[]
   */
  public function getDataStoreConnections()
  {
    return $this->dataStoreConnections;
  }
  /**
   * @param GoogleCloudDialogflowCxV3ToolDataStoreToolFallbackPrompt
   */
  public function setFallbackPrompt(GoogleCloudDialogflowCxV3ToolDataStoreToolFallbackPrompt $fallbackPrompt)
  {
    $this->fallbackPrompt = $fallbackPrompt;
  }
  /**
   * @return GoogleCloudDialogflowCxV3ToolDataStoreToolFallbackPrompt
   */
  public function getFallbackPrompt()
  {
    return $this->fallbackPrompt;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3ToolDataStoreTool::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3ToolDataStoreTool');
