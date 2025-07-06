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

namespace Google\Service\Contactcenterinsights;

class GoogleCloudContactcenterinsightsV1Note extends \Google\Model
{
  protected $assessmentNoteType = GoogleCloudContactcenterinsightsV1NoteAssessmentNote::class;
  protected $assessmentNoteDataType = '';
  /**
   * @var string
   */
  public $content;
  protected $conversationTurnNoteType = GoogleCloudContactcenterinsightsV1NoteConversationTurnNote::class;
  protected $conversationTurnNoteDataType = '';
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $name;
  protected $noteCreatorType = GoogleCloudContactcenterinsightsV1UserInfo::class;
  protected $noteCreatorDataType = '';
  protected $qaQuestionNoteType = GoogleCloudContactcenterinsightsV1NoteQaQuestionNote::class;
  protected $qaQuestionNoteDataType = '';
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param GoogleCloudContactcenterinsightsV1NoteAssessmentNote
   */
  public function setAssessmentNote(GoogleCloudContactcenterinsightsV1NoteAssessmentNote $assessmentNote)
  {
    $this->assessmentNote = $assessmentNote;
  }
  /**
   * @return GoogleCloudContactcenterinsightsV1NoteAssessmentNote
   */
  public function getAssessmentNote()
  {
    return $this->assessmentNote;
  }
  /**
   * @param string
   */
  public function setContent($content)
  {
    $this->content = $content;
  }
  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }
  /**
   * @param GoogleCloudContactcenterinsightsV1NoteConversationTurnNote
   */
  public function setConversationTurnNote(GoogleCloudContactcenterinsightsV1NoteConversationTurnNote $conversationTurnNote)
  {
    $this->conversationTurnNote = $conversationTurnNote;
  }
  /**
   * @return GoogleCloudContactcenterinsightsV1NoteConversationTurnNote
   */
  public function getConversationTurnNote()
  {
    return $this->conversationTurnNote;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param GoogleCloudContactcenterinsightsV1UserInfo
   */
  public function setNoteCreator(GoogleCloudContactcenterinsightsV1UserInfo $noteCreator)
  {
    $this->noteCreator = $noteCreator;
  }
  /**
   * @return GoogleCloudContactcenterinsightsV1UserInfo
   */
  public function getNoteCreator()
  {
    return $this->noteCreator;
  }
  /**
   * @param GoogleCloudContactcenterinsightsV1NoteQaQuestionNote
   */
  public function setQaQuestionNote(GoogleCloudContactcenterinsightsV1NoteQaQuestionNote $qaQuestionNote)
  {
    $this->qaQuestionNote = $qaQuestionNote;
  }
  /**
   * @return GoogleCloudContactcenterinsightsV1NoteQaQuestionNote
   */
  public function getQaQuestionNote()
  {
    return $this->qaQuestionNote;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudContactcenterinsightsV1Note::class, 'Google_Service_Contactcenterinsights_GoogleCloudContactcenterinsightsV1Note');
