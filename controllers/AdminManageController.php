<?php

class Invoice_AdminManageController extends Core_Controller_Action_Admin
{



	public function indexAction(){

		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
		->getNavigation('invoice_admin_main', array(), 'invoice_admin_main_manage');

		if ($this->getRequest()->isPost()) {
			$values = $this->getRequest()->getPost();
			foreach ($values as $key => $value) {
				if ($key == 'delete_' . $value) {
					$invoice = Engine_Api::_()->getItem('invoice', $value);
					$invoice->delete();
				}
			}
		}


		$this->view->form = $form = new Invoice_Form_Admin_Filter();

		$page = $this->_getParam('page',1);


		$param = $this->getRequest()->getParams();
		

		$search = $this->getSearchParam($param);
		// print_r($search);
		// die;



		$this->view->paginator = $paginator = Engine_Api::_()->getItemTable('invoice')->getInvoicesPaginator(array(
			'orderby' => 'invoice_id',
			'all' => true,
		),$search);

		$this->view->paginator->setItemCountPerPage(25);
		$this->view->paginator->setCurrentPageNumber($page);






	}



	public function deleteAction(){
		 // In smoothbox
		$this->_helper->layout->setLayout('admin-simple');
		$id = $this->_getParam('id');
		$this->view->invoice_id=$id;
    // Check post
		if( $this->getRequest()->isPost() )
		{
			$db = Engine_Db_Table::getDefaultAdapter();
			$db->beginTransaction();

			try
			{
				$invoice = Engine_Api::_()->getItem('invoice', $id);
        // delete the invoice entry into the database
				$invoice->delete();
				$db->commit();
			}

			catch( Exception $e )
			{
				$db->rollBack();
				throw $e;
			}

			$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => 10,
				'parentRefresh'=> 10,
				'messages' => array('')
			));
		}

    // Output
		$this->renderScript('admin-manage/delete.tpl');
	}




	private function getSearchParam($param){

		$arr = array();
		$keys = array("invoice_number","category_id","type"
		);

		foreach($param as $key => $value){

			if(!empty($value)){
				foreach($keys as $id){
					if($id == $key)
						$arr[$id] = $value;
				}
			}
		}
		return $arr;
	}






}


?>

