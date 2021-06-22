<?php 


class Invoice_Form_Admin_Global extends Engine_Form{

	public function init()
  {

    $this
      ->setTitle('Global Settings')
      ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'invoice_page', array(
      'label' => 'Entries Per Page',
      'description' => 'How many invoice entries will be shown per page? (Enter a number between 1 and 999)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.page', 10),
    ));


    $this->addElement('Radio','invoice_currency',array(
      'label' => 'Default Currency',
      'description' => 'Default Currency For All Invoices',
      'multiOptions' =>  array(
        0 => 'Dollar',
        1 => 'Rupees'
      ),
      'value' => Engine_Api::_()->getApi('settings','core')->getSetting('invoice.currency',0),

    ));


    $this->addElement('Text', 'invoice_cgst', array(
      'label' => 'CGST Invoice',
      'description' => 'CGST value For Invoices In Percentage(%)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.cgst', 8),
    ));

    $this->addElement('Text', 'invoice_sgst', array(
      'label' => 'SGST Invoice',
      'description' => 'SGST value For Invoices In Percentage(%)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.sgst', 8),
    ));

    $this->addElement('Text', 'invoice_igst', array(
      'label' => 'IGST Invoice',
      'description' => 'IGST value For Invoices In Percentage(%)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.igst', 8),
    ));

     
    // Add submit button
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true  // what this does
      
    ));
  }

}


?>