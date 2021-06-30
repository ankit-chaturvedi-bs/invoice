<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: WidgetController.php
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Invoice_Form_Search extends Engine_Form
{
  public function init()
  {
    $this
      ->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
      ->setMethod('GET')
      ;
      $this->setTitle("Search Invoices");
    
    $this->addElement('Text', 'search', array(
      'label' => 'Search Invoices By Invoice Number',
    ));

    $this->addElement('Text', 'name', array(
      'label' => 'Creator Name',
    ));


     $this->addElement('Text', 'date', array(
      'label' => 'Creation date',
      'inputType'=> 'date',
      'onchange' => 'this.form.submit();',
    ));


    // $this->addElement('Select', 'draft', array(
    //   'label' => 'Show',
    //   'multiOptions' => array(
    //     '' => 'All Entries',
    //     '0' => 'Only Published Entries',
    //     '1' => 'Only Drafts',
    //   ),
    //   'onchange' => 'this.form.submit();',
    // ));
  // $this->addElement('Select', 'domain', array(
  //           'label' => 'Domain',
  //           'description' => 'Please select the domain',
  //           'multiOptions' => array("0"=>"SocialEngineAddOns", "1"=>"Prime Messenger / Channelize.io", "2"=>"AlmaHub", "3"=>"MageCube",'"4'=>"Other Projects"),
  //           'onchange' => 'this.form.submit();',
  //       ));


    $this->addElement('Select', 'status', array(
      'label' => 'Status',
      'multiOptions' => array(
        '0'=>'',
        '1' => 'Paid',
        '2' => 'Unpaid',
      ),
      'onchange' => 'this.form.submit();',
    ));

    // $this->addElement('Select', 'category', array(
    //   'label' => 'Category',
    //   'multiOptions' => array(
    //     '0' => 'All Categories',
    //   ),
    //   'onchange' => 'this.form.submit();',
    // ));

    $this->addElement('Button', 'find', array(
      'type' => 'submit',
      'label' => 'Search',
      'ignore' => true,
      'order' => 10000001,
    ));

    // $this->addElement('Hidden', 'page', array(
    //   'order' => 100
    // ));

    // $this->addElement('Hidden', 'tag', array(
    //   'order' => 101
    // ));

    // $this->addElement('Hidden', 'start_date', array(
    //   'order' => 102
    // ));

    // $this->addElement('Hidden', 'end_date', array(
    //   'order' => 103
    // ));

    // // Populate category
    // $categories = Engine_Api::_()->getDbtable('categories', 'blog')->getCategoriesAssoc();
    // if( !empty($categories) && is_array($categories) ) {
    //   $this->category->addMultiOptions($categories);
    // }
  }
}