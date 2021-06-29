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



    $viewer = Engine_Api::_()->user()->getViewer();

    // $creatorsId = array(1,2,3,6);

    // $canCreate = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('invoice', $this->view->viewer()->level_id, 'create');

    // if(!$canCreate){

    //   return $this->_helper->redirector->gotoRoute(array('action' => 'manage'), 'invoice_general', true);


    // }


    

    if( !$this->getRequest()->isPost() ) {
      return;
    }


    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }



      // * check for date
      // * check for products 
      // * email and mobile no regex check
    
    $formValues = $this->getRequest()->getPost();

    if(!$form->validEmail($formValues['email'])) return $form->addError('Email is not valid');

    // if(!$form->validMobile($formValues['contact_number'])) return;

    // if(!$form->validDate($formValues['date'])) return $form->addError('Date is not valid');



    // get the products array
    $products = $form->getProducts($formValues);

    // print_r($products);
    // die;
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

      /**
       * @var type is 1 or 2 
       * 1 means paid and 2 means unpaid
       * 
       */

      $values = array_merge($formValues, array(
        'creator_name' => $viewer->getTitle(),
        'creator_id' => $viewer->getIdentity(),
        'type' => 2
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

      $values['total'] = $this->calcTotal($products,$values);

      $invoice = $table->createRow();
      $invoice->setFromArray($values);
      $invoice->save();

      
      // products table insertion begin here

      $productTable = Engine_Api::_()->getDbtable('products','invoice');
      $prodDb = $productTable->getAdapter();
      
      $prodDb->beginTransaction();

      // arrays
      $names = $products['names'];
      $qtys = $products['quantitys'];
      $amounts = $products['amounts']; 


      $cnt = (int)$values['products'];


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

    $this->view->form = $form = new Invoice_Form_Search();

    $defaultValues = $form->getValues();
    if( $form->isValid($this->_getAllParams()) ) {
      $values = $form->getValues();
    } else {
      $values = $defaultValues;
    }
    $this->view->formValues = array_filter($values);

    $this->view->assign($values);

    // print_r($values);
    // die;
    // // Get paginator
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('invoice')->getInvoicesPaginator($values);


    // Render
    $this->_helper->content
            //->setNoRender()
    ->setEnabled()
    ;



  }


  public function editAction(){
    if (!$this->_helper->requireUser()->isValid()) return;


    $viewer = Engine_Api::_()->user()->getViewer();
    $invoice_id = $this->_getParam('invoice_id');

    // $invoiceValues = Engine_Api::_()->getDbtable('invoices','invoice')->getInvoice($invoice_id,$viewer->getIdentity());

    $invoice = Engine_Api::_()->getItem('invoice', $this->_getParam('invoice_id'));


    $invoiceValues = $invoice->toArray();

    if($invoiceValues['type'] == 1) return $this->_helper->redirector->gotoRoute(array('action' => 'manage'), 'invoice_general', true);


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


    $this->view->cgst = $formValues['cgst'];
    $this->view->sgst = $formValues['sgst'];
    $this->view->igst = $formValues['igst'];

        // Process

    $table = Engine_Api::_()->getDbtable('invoices', 'invoice');

    $db = $table->getAdapter();
    $db->beginTransaction();

    try{
      $finalValues = $this->getEditableValues($formValues);
      $finalValues['total'] = $this->calcTotal($products,$formValues);


      // $this->debugErrors($finalValues);
      $invoice->setFromArray($finalValues);
      $invoice->save();


      $this->updateProductDb($products,$invoiceValues['invoice_number']);



      $db->commit();

    }catch (Exception $e) {
      return $this->exceptionWrapper($e, $form, $db);
    }

    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'), 'invoice_general', true);

  }



  public function deleteAction(){
    if (!$this->_helper->requireUser()->isValid()) return;
    $viewer = Engine_Api::_()->user()->getViewer();
    $invoice_id = $this->_getParam('invoice_id');

    $invoice = Engine_Api::_()->getItem('invoice', $this->_getParam('invoice_id'));
    $invoiceValue = $invoice->toArray();
    $this->_helper->layout->setLayout('default-simple');

    $this->view->form = $form = new Invoice_Form_Delete();


    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $invoice_number = $invoiceValue['invoice_number'];

    $db = $invoice->getTable()->getAdapter();
    $db->beginTransaction();

    try{

      $streamTable = Engine_Api::_()->getDbtable('products', 'invoice');


      // first delete all products,then delete invoice
      $streamTable->delete(array(
        'invoice_number = ?' => $invoice_number,
      ));

      $invoice->delete();
      $db->commit();



    }catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;
    $this->view->message = "Invoice has been deleted";
    return $this->_forward('success' ,'utility', 'core', array(
      'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'invoice_general', true),
      'messages' => Array($this->view->message)
    ));
  }


  public function viewAction(){
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


    $this->view->invoice = $invoiceValues;
    $this->view->products = $products;
    $this->view->company = $this->getCompanyDetails();

  }



  private function getCompanyDetails(){
    $keys = array('account.name','account.no','account.address','bank.name','gst.no','ifsc.code','lut.no','pan.no');

    $details = array();

    foreach($keys as $k){
      $details[$k] = Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.'.$k, 'xxxxxx');
    }

    return $details;
  }



  private function calcTax($tax,$amount){
    return ($amount*$tax)/100;
  } 


  private function calcTotal($products,$formValues){
    $quantity = $products['quantitys'];
    $price = $products['amounts'];

    $cnt = count($quantity);
    $total = 0;

    for($i =0;$i<=$cnt;$i++){
      $total += ((int)$quantity[$i]) * ((int)$price[$i]);
    }

    $curr = $formValues['currency'];
    $tax = 0;
    if($curr){
      $state = $formValues['state'];

      if($state){
       $igstTax = $this->calcTax($formValues['igst'],$total);
       $cgstTax = $this->calcTax($formValues['cgst'],$total);
       $tax = $igstTax+$cgstTax;
     }else{
      $sgstTax = $this->calcTax($formValues['sgst'],$total);
      $tax = $sgstTax;
    }
  }
  $total += $tax + (int)$formValues['discount'];


    // echo $total;
    // die;

  return $total;

}


  /**
   * @param products to insert | invoice number for products
   * @return success or give error
   * 
   */

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

  /**
   * @param products to insert | invoice number of products 
   * 
   */
  private function updateProductDb($products,$invoice_number){
    $streamTable = Engine_Api::_()->getDbtable('products', 'invoice');
    

    // first delete all products,then insert
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
      'customer_name' => $param['customer_name'],
      'address'=>$param['address'],
      'contact_number' => $param['contact_number'],
      'customer_email' => $param['email'],
      'discount' => $param['discount'],
      'currency' => $param['currency'],
      'state' => $param['state'],
      'type' => $param['type'],
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
   * @return reseted array based on Invoice_Form_Create Values
   */

  private function resetKeys($array){

    $array['creator'] = $array['creator_name'];
    unset($array['creator_name']);

    $array['email'] = $array['customer_email'];
    unset($array['customer_email']);


    return $array;
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
