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

namespace Google\Service\AndroidPublisher;

class RefundDetails extends \Google\Model
{
  protected $taxType = Money::class;
  protected $taxDataType = '';
  protected $totalType = Money::class;
  protected $totalDataType = '';

  /**
   * @param Money
   */
  public function setTax(Money $tax)
  {
    $this->tax = $tax;
  }
  /**
   * @return Money
   */
  public function getTax()
  {
    return $this->tax;
  }
  /**
   * @param Money
   */
  public function setTotal(Money $total)
  {
    $this->total = $total;
  }
  /**
   * @return Money
   */
  public function getTotal()
  {
    return $this->total;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RefundDetails::class, 'Google_Service_AndroidPublisher_RefundDetails');
