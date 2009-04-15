<?php

/**
 * Unix specific pre-condition list
 *
 */
class rtUnixPreConditionList extends rtPreconditionList{


  /**
   * Adapts the list of pre-conditions to include those that relate to Unix only
   *
   */
  public function adaptList() {
    array_push( $this->preConditions, 'rtIfParallelHasPcntl');
  }
}

?>