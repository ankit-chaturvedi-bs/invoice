<?php  


class Invoice_Form_Admin_Filter extends Engine_Form {

  public function init() {

    $this->clearDecorators()
            ->addDecorator('FormElements')
            ->addDecorator('Form')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
            ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));
    $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET');

    $this->addElement('Text', 'invoice_number', array(
      'label' => 'Invoice Number',
      'decorators' => array(
          'ViewHelper',
          array('Label', array('tag' => null, 'placement' => 'PREPEND')),
          array('HtmlTag', array('tag' => 'div'))
      ),
    ));

    $categories = Engine_Api::_()->getDbtable('categories', 'invoice')->getCategoriesAssoc();
    $categories = array_merge(array(0=>""),$categories);
    $this->addElement('Select', 'category_id', array(
      'label' => 'Invoice Category',
      'decorators' => array(
          'ViewHelper',
          array('Label', array('tag' => null, 'placement' => 'PREPEND')),
          array('HtmlTag', array('tag' => 'div'))
      ),
      'multiOptions' => $categories,
    ));

    $this->addElement('Select', 'type', array(
        'label' => "Invoice Type",
        'required' => true,
        'multiOptions' => array("0"=>"","1"=>"Paid","2"=>"unpaid"),
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));

    $this->addElement('Button', 'search', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true,
    ));
  }
}


?>