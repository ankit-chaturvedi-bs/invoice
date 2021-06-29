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
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '4.0.0',
      ),
    ),
    'callback' => 
    array (
      'path' => 'application/modules/Invoice/settings/install.php',
      'class' => 'Invoice_Installer',
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

  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Invoice_Plugin_Core',
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
        'action' => '(edit|delete|view)',
      ),

    ),
  ),
  ); ?>