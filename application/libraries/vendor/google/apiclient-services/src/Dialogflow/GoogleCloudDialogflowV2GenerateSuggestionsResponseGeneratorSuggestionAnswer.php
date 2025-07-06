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

class GoogleCloudDialogflowV2GenerateSuggestionsResponseGeneratorSuggestionAnswer extends \Google\Model
{
  /**
   * @var string
   */
  public $answerRecord;
  protected $generatorSuggestionType = GoogleCloudDialogflowV2GeneratorSuggestion::class;
  protected $generatorSuggestionDataType = '';
  /**
   * @var string
   */
  public $sourceGenerator;

  /**
   * @param string
   */
  public function setAnswerRecord($answerRecord)
  {
    $this->answerRecord = $answerRecord;
  }
  /**
   * @return string
   */
  public function getAnswerRecord()
  {
    return $this->answerRecord;
  }
  /**
   * @param GoogleCloudDialogflowV2GeneratorSuggestion
   */
  public function setGeneratorSuggestion(GoogleCloudDialogflowV2GeneratorSuggestion $generatorSuggestion)
  {
    $this->generatorSuggestion = $generatorSuggestion;
  }
  /**
   * @return GoogleCloudDialogflowV2GeneratorSuggestion
   */
  public function getGeneratorSuggestion()
  {
    return $this->generatorSuggestion;
  }
  /**
   * @param string
   */
  public function setSourceGenerator($sourceGenerator)
  {
    $this->sourceGenerator = $sourceGenerator;
  }
  /**
   * @return string
   */
  public function getSourceGenerator()
  {
    return $this->sourceGenerator;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowV2GenerateSuggestionsResponseGeneratorSuggestionAnswer::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowV2GenerateSuggestionsResponseGeneratorSuggestionAnswer');
