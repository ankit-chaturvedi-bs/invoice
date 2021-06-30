<?php

class Invoice_AdminSettingsController extends Core_Controller_Action_Admin{


	function indexAction(){
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('invoice_admin_main', array(), 'invoice_admin_main_settings');


      $this->view->form = $form = new Invoice_Form_Admin_Global();


      // what this is valid is doing here don't know 

      if($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())){
      	$values = $form->getValues();

        // print_r($values);
        // die;

      	foreach ($values as $key => $value){
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');



      }
	}

  function categoriesAction(){

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('invoice_admin_main', array(), 'invoice_admin_main_categories');


      $this->view->categories = Engine_Api::_()->getItemTable('invoice_category')->fetchAll();

  }
}


?>