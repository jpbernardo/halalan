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

class GoogleCloudDialogflowCxV3ToolOpenApiTool extends \Google\Model
{
  protected $authenticationType = GoogleCloudDialogflowCxV3ToolAuthentication::class;
  protected $authenticationDataType = '';
  protected $serviceDirectoryConfigType = GoogleCloudDialogflowCxV3ToolServiceDirectoryConfig::class;
  protected $serviceDirectoryConfigDataType = '';
  /**
   * @var string
   */
  public $textSchema;
  protected $tlsConfigType = GoogleCloudDialogflowCxV3ToolTLSConfig::class;
  protected $tlsConfigDataType = '';

  /**
   * @param GoogleCloudDialogflowCxV3ToolAuthentication
   */
  public function setAuthentication(GoogleCloudDialogflowCxV3ToolAuthentication $authentication)
  {
    $this->authentication = $authentication;
  }
  /**
   * @return GoogleCloudDialogflowCxV3ToolAuthentication
   */
  public function getAuthentication()
  {
    return $this->authentication;
  }
  /**
   * @param GoogleCloudDialogflowCxV3ToolServiceDirectoryConfig
   */
  public function setServiceDirectoryConfig(GoogleCloudDialogflowCxV3ToolServiceDirectoryConfig $serviceDirectoryConfig)
  {
    $this->serviceDirectoryConfig = $serviceDirectoryConfig;
  }
  /**
   * @return GoogleCloudDialogflowCxV3ToolServiceDirectoryConfig
   */
  public function getServiceDirectoryConfig()
  {
    return $this->serviceDirectoryConfig;
  }
  /**
   * @param string
   */
  public function setTextSchema($textSchema)
  {
    $this->textSchema = $textSchema;
  }
  /**
   * @return string
   */
  public function getTextSchema()
  {
    return $this->textSchema;
  }
  /**
   * @param GoogleCloudDialogflowCxV3ToolTLSConfig
   */
  public function setTlsConfig(GoogleCloudDialogflowCxV3ToolTLSConfig $tlsConfig)
  {
    $this->tlsConfig = $tlsConfig;
  }
  /**
   * @return GoogleCloudDialogflowCxV3ToolTLSConfig
   */
  public function getTlsConfig()
  {
    return $this->tlsConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3ToolOpenApiTool::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3ToolOpenApiTool');
