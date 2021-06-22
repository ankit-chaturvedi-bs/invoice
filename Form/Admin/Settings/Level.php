<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    invoice
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Level.php 10100 2013-10-24 23:09:16Z guido $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    invoice
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Invoice_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
    public function init()
    {
        parent::init();

        // My stuff
        $this
            ->setTitle('Member Level Settings')
            ->setDescription("Invoice_FORM_ADMIN_LEVEL_DESCRIPTION");

        // Element: view
        $this->addElement('Radio', 'view', array(
            'label' => 'Allow Viewing of invoices?',
            'description' => 'Do you want to let members view invoices? If set to no, some other settings on this page may not apply.',
            'multiOptions' => array(
                2 => 'Yes, allow viewing of all invoices, even private ones.',
                1 => 'Yes, allow viewing of invoices.',
                0 => 'No, do not allow invoices to be viewed.',
            ),
            'value' => ( $this->isModerator() ? 2 : 1 ),
        ));
        if( !$this->isModerator() ) {
            unset($this->view->options[2]);
        }

        if( !$this->isPublic() ) {

            // Element: create
            $this->addElement('Radio', 'create', array(
                'label' => 'Allow Creation of invoices?',
                'description' => 'Do you want to let members create invoices? If set to no, some other settings on this page may not apply. This is useful if you want members to be able to view invoices, but only want certain levels to be able to create invoices.',
                'multiOptions' => array(
                    1 => 'Yes, allow creation of invoices.',
                    0 => 'No, do not allow invoices to be created.'
                ),
                'value' => 1,
            ));

            // Element: edit
            $this->addElement('Radio', 'edit', array(
                'label' => 'Allow Editing of invoices?',
                'description' => 'Do you want to let members edit invoices? If set to no, some other settings on this page may not apply.',
                'multiOptions' => array(
                    2 => 'Yes, allow members to edit all invoices.',
                    1 => 'Yes, allow members to edit their own invoices.',
                    0 => 'No, do not allow members to edit their invoices.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            if( !$this->isModerator() ) {
                unset($this->edit->options[2]);
            }

            // Element: delete
            $this->addElement('Radio', 'delete', array(
                'label' => 'Allow Deletion of invoices?',
                'description' => 'Do you want to let members delete invoices? If set to no, some other settings on this page may not apply.',
                'multiOptions' => array(
                    2 => 'Yes, allow members to delete all invoices.',
                    1 => 'Yes, allow members to delete their own invoices.',
                    0 => 'No, do not allow members to delete their invoices.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            if( !$this->isModerator() ) {
                unset($this->delete->options[2]);
            }

            // Element: max
            $this->addElement('Text', 'max', array(
                'label' => 'Maximum Allowed invoice Entries?',
                'description' => 'Enter the maximum number of allowed invoice entries. The field must contain an integer between 1 and 999, or 0 for unlimited.',
                'validators' => array(
                    array('Int', true),
                    new Engine_Validate_AtLeast(0),
                ),
            ));
            $this->addElement('FloodControl', 'flood', array(
                'label' => 'Maximum Allowed invoice Entries per Duration',
                'description' => 'Enter the maximum number of invoice entries allowed for the selected duration (per minute / per hour / per day) for members of this level. The field must contain an integer between 1 and 9999, or 0 for unlimited.',
                'required' => true,
                'allowEmpty' => false,
                'value' => array(0, 'minute'),
            ));
        }
    }
}
