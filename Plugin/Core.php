<?php

class Invoice_Plugin_Core
{

  public function onUserDeleteBefore($event)
  {
    $payload = $event->getPayload();
    if( $payload instanceof User_Model_User ) {
      // Delete blogs
      $invoiceTable = Engine_Api::_()->getDbtable('invoices', 'invoice')->updateOwner($payload->getIdentity(),$payload->getTitle());
      

    }
  }
}
?>