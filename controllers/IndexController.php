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

    if(!$form->validDate($formValues['date'])) return $form->addError('Date is not valid');



    // get the products array
    $products = $form->getProducts($formValues);

    
    if(!$form->isValidProducts($products))  return $form->addError('Products are not valid');

;

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
