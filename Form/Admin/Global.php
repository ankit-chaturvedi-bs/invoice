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



    $this
    ->setDescription('Companys Details');




    $this->addElement('Text', 'invoice_pan_no', array(
      'label' => 'Pan No',
      'description' => 'Company pan no',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.pan.no', 'xxxxxx'),
    ));


    $this->addElement('Text', 'invoice_gst_no', array(
      'label' => 'GST No',
      'description' => 'Company GST no',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.gst.no', 'xxxxxx'),
    ));


    $this->addElement('Text', 'invoice_lut_no', array(
      'label' => 'LUT No',
      'description' => 'Company LUT no',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.lut.no', 'xxxxxx'),
    ));

    $this->addElement('Text', 'invoice_account_name', array(
      'label' => 'Account Name',
      'description' => 'Company Account Name',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.account_name', 'xxxxxx'),
    ));


    $this->addElement('Text', 'invoice_account_no', array(
      'label' => 'Account No',
      'description' => 'Company Account No',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.account.no', 'xxxxxx'),
    ));

    $this->addElement('Text', 'invoice_bank_name', array(
      'label' => 'Bank Name',
      'description' => 'Company Bank Name',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.bank.name', 'xxxxxx'),
    ));

    $this->addElement('Text', 'invoice_account_address', array(
      'label' => 'Account Address',
      'description' => 'Company Account Address',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.account.address', 'xxxxxx'),
    ));


    $this->addElement('Text', 'invoice_ifsc_code', array(
      'label' => 'IFSC Code',
      'description' => 'Company IFSC Code',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('invoice.ifsc.code', 'xxxxxx'),
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