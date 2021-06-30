<?php 


class Invoice_Form_Edit extends Invoice_Form_Create{


	public function init(){
		parent::init();
		$this->setTitle("Edit")->setDescription("Edit the details and click on submit to save the changes");

	}
}




?>