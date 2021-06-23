<?php

class Invoice_IndexController extends Core_Controller_Action_Standard
{
  public function indexAction()
  {
    $this->view->someVar = 'someVal';
  }

 

  
  public function createAction(){

    if( !$this->_helper->requireUser()->isValid() ) return;

    $this->view->form = $form = new Invoice_Form_Create();
    $this->view->cgst = $cgst = Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.cgst', 8);
    $this->view->sgst = $sgst = Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.sgst', 8);
    $this->view->igst = $igst = Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.igst', 8);


    if( !$this->getRequest()->isPost() ) {
            return;
    }


    if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
    }


    // will give the object of viewer who is creating form
    $viewer = Engine_Api::_()->user()->getViewer();
    
      // * check for date
      // * check for products 
      // * email and mobile no regex check
    
    $formValues = $this->getRequest()->getPost();

    if(!$form->validEmail($formValues['email'])) return $form->addError('Email is not valid');

    // if(!$form->validMobile($formValues['contact_number'])) return;

    // if(!$form->validDate($formValues['date'])) return $form->addError('Date is not valid');



    // get the products array
    $products = $form->getProducts($formValues);

    
    if(!$form->isValidProducts($products))  return $form->addError('Products are not valid');



    /*
      * form error
      * $form->addError('Error Msg');
    */


     // Process
    $table = Engine_Api::_()->getItemTable('invoice');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try{
      $values = array_merge($formValues, array(
                'creator_name' => $viewer->getTitle(),
                'creator_id' => $viewer->getIdentity(),
      ));

     
      // get the category name 
      $category_name = Engine_Api::_()->getDbtable('categories','invoice')->getCategory($values['category_id']);


      // get the invoice number
      $invoice_number = Engine_Api::_()->getDbtable('invoices', 'invoice')->getInvoiceNumber($values['category_id'],$category_name);

      
      $values = array_merge($values,array(
              'invoice_number' => $invoice_number,
              'customer_email' => $values['email']
      ));


      /*
        * change the gst values;
        * get the global values;
      */

      $values['cgst'] = $cgst;
      $values['igst'] = $igst;
      $values['sgst'] = $sgst;

      // print_r($values);
      // die;

      $invoice = $table->createRow();
      $invoice->setFromArray($values);
      $invoice->save();
      // echo "<pre>";
      // print_r($invoice->toArray());
      // print_r($values);
      // die;

      
      // products table insertion begin here

      $productTable = Engine_Api::_()->getDbtable('products','invoice');
      $prodDb = $productTable->getAdapter();
      
      $prodDb->beginTransaction();

      // products name array
      $names = $products['names'];
      // products quantity array  
      $qtys = $products['quantitys'];
      // products amounts array 
      $amounts = $products['amounts']; 

      // total added product count;
      $cnt = (int)$values['products'];
      // print_r($values);
      // die;

      for($i = 1; $i<=$cnt;$i++){
        $productArray = array();

        $productArray['product_name'] = $names[$i];
        $productArray['quantity'] = $qtys[$i];
        $productArray['price'] = $amounts[$i];
        $productArray['invoice_number'] = $invoice_number;
        $productArray['product_id'] = null;
        $product = $productTable->createRow();

        // echo "hello world";
        // print_r($product->toArray());
        // die;
        
        $product->setFromArray($productArray);
        $product->save();
      }
      $prodDb->commit();
      $db->commit();


    } catch( Exception $e ) {
            return $this->exceptionWrapper($e, $form, $db);
    }

    return $this->_helper->redirector->gotoRoute(array('action' => 'create'));
  }


  public function manageAction(){

    if( !$this->_helper->requireUser()->isValid() ) return;


    // Prepare data
    $viewer = Engine_Api::_()->user()->getViewer();

    $page_number = $this->_getParam('page');
    $items_per_page = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.page',10);

    $values = array(
            'page' => $page_number,
            'limit' => $item_per_page
    );




    // Get paginator
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('invoice')->getInvoicesPaginator($values);



  }


  public function editAction(){
    if (!$this->_helper->requireUser()->isValid()) return;


    $viewer = Engine_Api::_()->user()->getViewer();
    $invoice_id = $this->_getParam('invoice_id');

    // $invoiceValues = Engine_Api::_()->getDbtable('invoices','invoice')->getInvoice($invoice_id,$viewer->getIdentity());

    $invoice = Engine_Api::_()->getItem('invoice', $this->_getParam('invoice_id'));


    $invoiceValues = $invoice->toArray();

    //create a route;
    if(empty($invoiceValues)) return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));

    // $this->debugErrors($invoiceValues);

    $products = Engine_Api::_()->getDbtable('products','invoice')->getProducts($invoiceValues['invoice_number']);

    $this->view->form = $form = new Invoice_Form_Create();

    $invoiceValues = $this->resetKeys($invoiceValues);
    $form->populate($invoiceValues);

    $this->view->products = $products;




    // $this->debugErrors($this->getRequest()->getPost());

    if( !$this->getRequest()->isPost() ) {
            return;
    }


    if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
    }


    $formValues = $this->getRequest()->getPost();

    if(!$form->validEmail($formValues['email'])) return $form->addError('Email is not valid');

    // get the products array
    $products = $form->getProducts($formValues);

    
    if(!$form->isValidProducts($products))  return $form->addError('Products are not valid');


    

        // Process

    $table = Engine_Api::_()->getDbtable('invoices', 'invoice');

    $db = $table->getAdapter();
    $db->beginTransaction();

    try{
      $finalValues = $this->getEditableValues($formValues);
      // $this->debugErrors($finalValues);
      $invoice->setFromArray($finalValues);
      $invoice->save();


      $this->updateProductDb($products,$invoiceValues['invoice_number']);



      $db->commit();

    }catch (Exception $e) {
      return $this->exceptionWrapper($e, $form, $db);
    }

      return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));

  }



  private function productsInsertion($products,$invoice_number){
      // products table insertion begin here

      $productTable = Engine_Api::_()->getDbtable('products','invoice');
      $prodDb = $productTable->getAdapter();
      
      $prodDb->beginTransaction();

      // products name array
      $names = $products['names'];
      // products quantity array  
      $qtys = $products['quantitys'];
      // products amounts array 
      $amounts = $products['amounts']; 

      // total added product count;
      $cnt = count($amounts);


      for($i = 1; $i<=$cnt;$i++){
        $productArray = array();

        $productArray['product_name'] = $names[$i];
        $productArray['quantity'] = $qtys[$i];
        $productArray['price'] = $amounts[$i];
        $productArray['invoice_number'] = $invoice_number;
        $productArray['product_id'] = null;
        $product = $productTable->createRow();
        
        $product->setFromArray($productArray);
        $product->save();
      }
      $prodDb->commit();
  }

  private function updateProductDb($products,$invoice_number){
    // print_r($products);
    // die;
    $streamTable = Engine_Api::_()->getDbtable('products', 'invoice');
    $streamTable->delete(array(
      'invoice_number = ?' => $invoice_number,
    ));


    $this->productsInsertion($products,$invoice_number);
  }



  /**
   * @param post values
   * @return array of values need to be update in database
   * 
   */
  private function getEditableValues($param){
    return array(
      'to' => $param['to'],
      'address'=>$param['address'],
      'contact_number' => $param['contact_number'],
      'customer_email' => $param['email'],
      'discount' => $param['discount'],
      'currency' => $param['currency'],
      'state' => $param['state'],
    );
  }



  // delete it before deployment
  private function debugErrors($param){
    echo "<pre>";
    print_r($param);
    die;
  }

  /**
   * @param invoice array
   * @return reseted array base on Invoice_Form_Create Values
   */

  private function resetKeys($array){

    $array['creator'] = $array['creator_name'];
    unset($array['creator_name']);

    $array['email'] = $array['customer_email'];
    unset($array['customer_email']);


    return $array;
  }








  /*
    * return unique invoice number,
    * invoice financial start year,
    * invoice financial end year,
    * invoice financial month, 
  */
  public function invoiceDetails(){

  }


// output array;
//   Array
// (
//     [creator] => root
//     [to] => sdfsdfs
//     [date] => Array
//         (
//             [day] => 12
//             [month] => 0
//             [year] => 0
//         )

//     [address] => fsfsfsdf
//     [contact_number] => fsdfsf
//     [email] => ankit.chaturvedi@bigsteptech.com
//     [currency] => 0
//     [state] => 0
//     [category_id] => 1
//     [products] => 
//     [p1] => 
//     [q1] => 0
//     [pr1] => 0
//     [a1] => 0
//     [sub_total] => 
//     [discount] => 
//     [cgst] => 
//     [sgst] => 
//     [igst] => 
//     [total] => 
//     [submit] => 
// )

  
}
