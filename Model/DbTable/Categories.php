<?php 


class Invoice_Model_DbTable_Categories extends Engine_Db_Table
{
  protected $_rowClass = 'Invoice_Model_Category';


  public function getCategoriesAssoc()
  {
    $stmt = $this->select()
        ->from($this, array('category_id', 'category_name'))
        ->query();
    $data = array();
    foreach( $stmt->fetchAll() as $category ) {
      $data[$category['category_id']] = $category['category_name'];
    }
    
    return $data;
  }

  public function getCategory($category){

    // change the 1 with $category
    $stmt = $this->select()->from('engine4_invoice_categories',array('category_name'))->where('category_id = ?', $category)->query();

    
    

    $data = $stmt->fetch();

    // return category name;
    return $data['category_name'];

  }
}

?>