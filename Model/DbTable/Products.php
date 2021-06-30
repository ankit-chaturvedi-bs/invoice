<?php 


class Invoice_Model_DbTable_Products extends Core_Model_Item_DbTable_Abstract
{
  protected $_rowClass = 'Invoice_Model_Product';

  /**
   * @param invoice_number
   * @return products array
   */

  public function getProducts($param){

    $stmt = $this->select()->from($this)->where("invoice_number = ?",$param)->query();

    $data = $stmt->fetchAll();
    return $data;

  }


  public function deleteProducts($invoice_number){
    $stmt = $this->delete("engine4_invoice_products","invoice_number = ?",$invoice_number);

    /*


    $streamTable = Engine_Api::_()->getDbtable('stream', 'activity');
    $streamTable->delete(array(
      'action_id = ?' => $action->getIdentity(),
    ));

    */

    echo $stmt;
    die;
  }

}

?>