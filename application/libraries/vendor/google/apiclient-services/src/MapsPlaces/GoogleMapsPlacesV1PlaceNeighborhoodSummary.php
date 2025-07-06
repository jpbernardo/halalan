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

namespace Google\Service\MapsPlaces;

class GoogleMapsPlacesV1PlaceNeighborhoodSummary extends \Google\Model
{
  protected $descriptionType = GoogleMapsPlacesV1ContentBlock::class;
  protected $descriptionDataType = '';
  protected $disclosureTextType = GoogleTypeLocalizedText::class;
  protected $disclosureTextDataType = '';
  /**
   * @var string
   */
  public $flagContentUri;
  protected $overviewType = GoogleMapsPlacesV1ContentBlock::class;
  protected $overviewDataType = '';

  /**
   * @param GoogleMapsPlacesV1ContentBlock
   */
  public function setDescription(GoogleMapsPlacesV1ContentBlock $description)
  {
    $this->description = $description;
  }
  /**
   * @return GoogleMapsPlacesV1ContentBlock
   */
  public function getDescription()
  {
    return $this->description;
  }
  /**
   * @param GoogleTypeLocalizedText
   */
  public function setDisclosureText(GoogleTypeLocalizedText $disclosureText)
  {
    $this->disclosureText = $disclosureText;
  }
  /**
   * @return GoogleTypeLocalizedText
   */
  public function getDisclosureText()
  {
    return $this->disclosureText;
  }
  /**
   * @param string
   */
  public function setFlagContentUri($flagContentUri)
  {
    $this->flagContentUri = $flagContentUri;
  }
  /**
   * @return string
   */
  public function getFlagContentUri()
  {
    return $this->flagContentUri;
  }
  /**
   * @param GoogleMapsPlacesV1ContentBlock
   */
  public function setOverview(GoogleMapsPlacesV1ContentBlock $overview)
  {
    $this->overview = $overview;
  }
  /**
   * @return GoogleMapsPlacesV1ContentBlock
   */
  public function getOverview()
  {
    return $this->overview;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleMapsPlacesV1PlaceNeighborhoodSummary::class, 'Google_Service_MapsPlaces_GoogleMapsPlacesV1PlaceNeighborhoodSummary');
