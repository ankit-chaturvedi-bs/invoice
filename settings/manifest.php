<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'invoice',
    'version' => '4.0.0',
    'path' => 'application/modules/Invoice',
    'title' => 'Invoice',
    'description' => 'Invoice Creator ',
    'author' => 'admin',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/Invoice',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/invoice.csv',
    ),
  ),

  // Items ---------------------------------------------------------------------
  'items' => array(
    'invoice',
    'invoice_category',
    'invoice_product',
  ),

    // Routes --------------------------------------------------------------------
  'routes' => array(
    // openssl_public_decrypt(data, decrypted, key)
    'invoice_general' => array(
      'route' => 'invoice/:action',
      'defaults' => array(
        'module' => 'invoice',
        'controller' => 'index',
        'action' => 'manage',
      ),
      'reqs' => array(
        'action' => '(create|manage)',
      ),
    ),

    'invoice_specific' => array(
      'route' => 'invoice/:action/:invoice_id',
      'defaults' => array(
        'module' => 'invoice',
        'controller' => 'index',
        'action' => 'edit',
      ),
      'reqs' => array(
        'action' => '(edit|delete)',
      ),

    ),
  ),
); ?>