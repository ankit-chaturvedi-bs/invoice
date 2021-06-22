<?php 


class Invoice_Model_DbTable_Invoices extends Engine_Db_Table
{
  protected $_rowClass = 'Invoice_Model_Invoice';

  public function getInvoiceNumber($category,$name){
    $mydate=getdate(date("U"));
    $currYear = $mydate['year'];
    $currMonth = $mydate['mon'];

    $resetMonth = 6;


    $stmt = $this->select()->
            from($this,array('Max(invoice_number) as invoice_number'))
            ->where('category_id = ?',$category)
            ->query();

    $value = $stmt->fetch();
    if(!empty($value['invoice_number'])){
      $values = explode("/",$value['invoice_number']);
    }else{
    // covert the current year by taking last two chars
    $currYear = (int)(substr($currYear,2));
    $cat = $this->constructName($name);
    return "1"."/".$cat."/".$currYear."-".++$currYear;
    }
    
    return $this->getNumber($values,$name,$currYear);
    
  }

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
        $inNo++;
    }

    return $inNo."/".$cat."/".$startYear."-".$endYear;

  }

}

?>