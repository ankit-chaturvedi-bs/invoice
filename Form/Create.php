<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Create.php 10168 2014-04-17 16:29:36Z andres $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Invoice_Form_Create extends Engine_Form
{
    public function init()
    {
        $this->setTitle('Create A New Invoice')
            ->setDescription('Create a new Invoice and click on submit to create it')
            ->setAttrib('name', 'invoices_create');
        $user = Engine_Api::_()->user()->getViewer();
        $userLevel = Engine_Api::_()->user()->getViewer()->level_id;

        $this->addElement('Text', 'creator', array(
            'label' => 'Creator Name',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'value' => $user->getTitle(),
            'readonly'=> true,
            'autofocus' => 'autofocus',
        ));



        $this->addElement('Text', 'customer_name', array(
            'label' => 'Name (Bill To)',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'autofocus' => 'autofocus',
        ));

        // $this->addElement('Date', 'date', array(
        //     'label' => 'Invoice Date',
        //     'allowEmpty' => false,
        //     'required' => true,
        //     'maxlength' => '63',
        //     'autofocus' => 'autofocus',
        // ));


        $this->addElement('Text', 'address', array(
            'label' => 'Address',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'autofocus' => 'autofocus',
        ));

        $this->addElement('Text', 'contact_number', array(
            'label' => 'Contact Number',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'autofocus' => 'autofocus',
        ));

        $this->addElement('Text', 'email', array(
            'label' => 'Email',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'autofocus' => 'autofocus',
        ));

        $currency = Engine_Api::_()->getApi('settings', 'core')->invoice_currency;

        //set the default value based on the global settings
        $this->addElement('Select', 'currency', array(
            'label' => 'Currency',
            'multiOptions' => array("0" => "Dollar", "1" => "Rupees"),
            'value' => $currency,
            'onchange'=> 'currencyChange(this)',
        ));


        //set the default value based on the global settings
        $this->addElement('Select', 'state', array(
            'label' => 'State',
            'multiOptions' => array("0" => "Haryana", "1" => "Outside Haryana"),
            'value' => 0,
            'onchange' => 'stateChange(this)'
        ));


        $this->addElement('Select','type',array(
            'label' => 'Type',
            'multiOptions' => array("1"=>"paid","2"=>"unpaid"),
            'value' => 2,
        ));

        $categories = Engine_Api::_()->getDbtable('categories', 'invoice')->getCategoriesAssoc();
        if (count($categories) > 0) {
            $this->addElement('Select', 'category_id', array(
                'label' => 'Product Category',
                'multiOptions' => $categories,
            ));
        }


        $this->addElement('Text', 'products', array(
            'label' => 'Products',
            'id' => 'products',
            'style' => 'display:none',
        ));


        $this->addElement('Button', 'add_product', array(
            'label' => 'Add More Product',
        ));


        $this->addElement('Text', 'sub_total', array(
            'label' => 'Sub Total',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'placeholder' => '0.00',
            'readonly'=> true,

         
        ));


        $this->addElement('Text', 'discount', array(
            'label' => 'Discount(Enter the percentage and it will auto calculate the amount)',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'placeholder' => '0.00',
            'onchange' => 'discountChange(this)',
            'value' => 0
        ));


        $cgst = 'CGST @'.Engine_Api::_()->getApi('settings', 'core')->invoice_cgst;


        //cgst value take from global settings
        $this->addElement('Text', 'cgst', array(
            'label' => $cgst,
            'maxlength' => '63',
            'placeholder' => '0.00',
            'readonly'=> true,
            
            
        ));

        $sgst = 'SGST @'.Engine_Api::_()->getApi('settings', 'core')->invoice_sgst;

        //sgst value take from global settings
        $this->addElement('Text', 'sgst', array(
            'label' => $sgst,
            'maxlength' => '63',
            'placeholder' => '0.00',
            'readonly'=> true,
        ));

        $igst = 'IGST @'.Engine_Api::_()->getApi('settings', 'core')->invoice_igst;

        //igst value take from global settings
        $this->addElement('Text', 'igst', array(
            'label' => $igst,
            'maxlength' => '63',
            'placeholder' => '0.00',
            'readonly'=> true,
        ));

        //igst value take from global settings
        $this->addElement('Text', 'total', array(
            'label' => 'Total',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '63',
            'placeholder' => '0.00',
            'readonly'=> true,
        ));

        // Element: submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Post Entry',
            'type' => 'submit',
            'onclick' => 'addProductCount()',
        ));
    }


    public function validEmail($email){
        $isValid = true;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $isValid = false;
        }
        return $isValid;

    }

    public function validMobile($mobile){
        $regex =  "/^(\+\d{1,3}[- ]?)?\d{10}$/";

       $isValid = preg_match($regex,$mobile);

        return $isValid;
    }


    public function validDate($date){
        $isValid = true;
        if(!$date['day'] || !$date['month'] || !$date['year']) $isValid = false;

        return $isValid;

    }



    /**
     * @param array of all form values
     * @return array of products
     */


    public function getProducts($param = array()){
        if(!isset($param)) return;
        $names = array();
        $qtys =array();
        $amounts =array();
        $cnt = (int)$param['products'];

        for($i = 1; $i <= $cnt;$i++) {
            $id = "p".$i;
            $names[$i] = $param[$id];
        }

        for($i = 1; $i<= $cnt;$i++) {
            $id = "q".$i;
            $qtys[$i] = $param[$id];
        }

        for($i = 1; $i<= $cnt;$i++) {
            $id = "pr".$i;
            $amounts[$i] = $param[$id];
        }

        

        return array(
            'names' => $names,
            'quantitys' => $qtys,
            'amounts' => $amounts,
        );

    }


    /**
     * @param products array
     * @return whether products are valid or not
     */
    public function isValidProducts($products){
        // arrays
        $names = $products['names']; 
        $qtys = $products['quantitys'];
        $amounts = $products['amounts']; 

        // validate fields by checking empty fields
        foreach($names as $value){
            if(empty($value)) return false;
        }
        foreach($qtys as $value){
            if(empty($value) || $value < 0) return false;
        }
        foreach($amounts as $value){
            if(empty($value) || $value < 0) return false;
        }

        return true;
    }

    

    


}