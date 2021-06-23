<?php 


class Invoice_Model_DbTable_Invoices extends Core_Model_Item_DbTable_Abstract
{
  protected $_rowClass = 'Invoice_Model_Invoice';

  // get the tow initials from category name
  private function constructName($name){
    $arr = explode(" ",$name);
    return substr($arr[0],0,1).substr($arr[1],0,1);

}

private function getNumber($param,$name,$currYear){
    // invoice starting number
    $inNo = (int)$param[0]; 
    // invoice category name
    $cat = $this->constructName($name);
    // invoice start and end year format(19-20);
    $stEnYear = $param[2]; 
    $years = explode("-",$stEnYear);

    // split the years to get start and end year;
    $endYear = (int)$years[1];
    $startYear = (int)$years[0];

    // covert the current year by taking last two chars
    $currYear = (int)(substr($currYear,2));
    // echo $currYear."\n";
    


    /*
      * if current year is equal and greater then end year of last inserted invoice
      * then start the new invoice no for new finacial year
      * set the years value with respect to curr finacial year
      * else just increment the invoice start no;
    */

    if(($endYear == $currYear && $currMonth >= $resetMonth) || ($endYear <= $currYear)) {
        $inNo = 1;
        $startYear = $currYear;
        $endYear = ++$currYear;
    }
    else{
        ++$inNo;
    }



    return $inNo."/".$cat."/".$startYear."-".$endYear;

}



public function getInvoiceNumber($category,$name,$curr = 0){
    $mydate=getdate(date("U"));
    $currYear = $mydate['year'];
    $currMonth = $mydate['mon'];

    $resetMonth = 6;


    $stmt = $this->select()->
    from($this,array('Max(invoice_id) as id'))
    ->where('category_id = ?',$category)
    ->query();

    $id = $stmt->fetch();
    

    // zero adding logic $k=str_pad($k, 4, '0', STR_PAD_LEFT);

    if(empty($id['id'])){
    // covert the current year by taking last two chars
        $currYear = (int)(substr($currYear,2));
        $cat = $this->constructName($name);

        return "1"."/".$cat."/".$currYear."-".++$currYear;
    }

    $stmt = $this->select()->
    from($this,array('invoice_number'))
    ->where('invoice_id = ?',$id['id'])
    ->query();

    $value = $stmt->fetch();
    // print_r($value);
    // die;
    $values = explode("/",$value['invoice_number']);
    
    return $this->getNumber($values,$name,$currYear);
    
}

  /**
     * Gets a select object for the user's blog entries
     *
     * @param Core_Model_Item_Abstract $user The user to get the messages for
     * @return Zend_Db_Table_Select
     */
  public function getInvoicesSelect($params = array())
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();


    $table = Engine_Api::_()->getDbtable('invoices', 'invoice');

    $select = $table->select()
    ->where("creator_id = ?",$viewerId);
    
    return $select;
    
}



  /**
     * Gets a paginator for blogs
     *
     * @param Core_Model_Item_Abstract $user The user to get the messages for
     * @return Zend_Paginator
     */
  public function getInvoicesPaginator($params = array())
  {
    $paginator = Zend_Paginator::factory($this->getInvoicesSelect($params));
    if (!empty($params['page'])) {
        $paginator->setCurrentPageNumber($params['page']);
    }
    if (!empty($params['limit'])) {
        $paginator->setItemCountPerPage($params['limit']);
    }

    if (empty($params['limit'])) {
        $page = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.page',10);
        $paginator->setItemCountPerPage($page);
    }

    return $paginator;
}

    /**
     * @return Array of Invoice and Prdoucts 
     * @param  viewer id and invoice id which to edit
     * 
     * 
     */


    public function getInvoice($invoice_id,$viewer_id){

      $stmt = $this->select()->from($this)->where("invoice_id = ? and creator_id = ".$viewer_id,$invoice_id)->query();

      $data = $stmt->fetch(); 
      return $data;
  }








}

?>