<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
// Include base plugin class only once
require_once(JPATH_ADMINISTRATOR . '/components/com_j2store/library/plugins/app.php');

// Create new class to extend base plugin
class plgJ2StoreApp_bootstrap5plus extends J2StoreAppPlugin
{
  /**
   * @var $_element  string  
   * Should always correspond with the plugin's filename, forcing it to be unique     
   */
  var $_element   = 'app_bootstrap5plus';

  /**
   * Overriding
   *
   * @param $row
   * @return string
   */
  // Get the J2Store view list
  function onJ2StoreGetAppView($row)
  {
    // If this is not an instance of this plugin
    if (!$this->_isMe($row)) {
      return null;
    }

    // Get the view list
    return $this->viewList();
  }

  // Check if Joomla version 4 is being used
  function onJ2StoreIsJ2Store4($element)
  {
    // If this is not an instance of this plugin
    if (!$this->_isMe($element)) {
      return null;
    }
    return true;
  }

  /**
   * Validates the data submitted based on the suffix provided
   * A controller for this plugin, you could say
   * @return string
   */
  // Get and parse data
  function viewList()
  {
    // Get application instance
    $app = J2Store::platform()->application();

    // Model should always be a plural / end in an extra s
    // Load the custom model
    $this->includeCustomModel('AppBootstrap5pluss');
    // Create and extended version of the model
    $model = F0FModel::getTmpInstance(
      'AppBootstrap5pluss',
      'J2StoreModel'
    );

    // Toolbar Title
    JToolBarHelper::title(JText::_('J2STORE_APP') . '-' . JText::_('PLG_J2STORE_' . strtoupper($this->_element)), 'j2store-logo');
    // Back button
    JToolBarHelper::back('J2STORE_BACK_TO_DASHBOARD', 'index.php?option=com_j2store');

    var_dump($this->params);

    // Prepare data for the view
    // Create new stdClass object to store template variables
    $vars = new \stdClass();
    // Convert plugin params into an array
    $data = $this->params->toArray();
    // Create a new data array
    $newdata = array();
    // Wrap parameters inside the new data array
    $newdata['params'] = $data;
    // Get the form from the model
    $form = $model->getForm($newdata);
    // Store the form inside the variables
    $vars->form = $form;

    // Get HTTP Integer ID
    $id = $app->input->getInt('id', '0');
    // Save the ID
    $vars->id = $id;

    // Render the view
    return $this->_getLayout('default', $vars);
  }

  // Decode html special characters
  public function escape($var)
  {
    return htmlspecialchars_decode($var, ENT_COMPAT);
  }

  // Modify folder structure
  function onJ2StoreTemplateFolderList(&$folder)
  {
    // If main folder does not exist 
    if (!in_array('bootstrap5plus', $folder)) {
      // Add folder
      $folder[] = 'bootstrap5plus';
    }

    // If tag folder doesn't exist
    if (!in_array('tag_bootstrap5plus', $folder)) {
      // Add folder
      $folder[] = 'tag_bootstrap5plus';
    }
  }

  // Modify product list output
  function onJ2StoreViewProductListHtml(&$view_html, &$view, $model)
  {
    // Ignore all errors
    F0FPlatform::getInstance()->setErrorHandling(E_ALL, 'ignore');
    // Get template path
    $view = $this->setTemplatePath($view);
    // Load template
    $result = $view->loadTemplate();

    // If an exception occurs
    if ($result instanceof Exception) {
      // Handle error
      F0FPlatform::getInstance()->raiseError(
        $result->getCode(),
        $result->getMessage()
      );

      // Return error message
      return $result;
    }

    // Get final html output
    $view_html = $result;
  }

  // Modify the template search paths
  function setTemplatePath($view, $default = 'bootstrap5plus')
  {
    // Get Joomla application instance
    $app = J2Store::platform()->application();
    // Define DIrectory Separator (DS) if not already defined
    if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

    // Add default template path
    $view->addTemplatePath(
      JPATH_SITE . DS . 'plugins' . DS . 'j2store' . DS . $this->_element . DS . $this->_element . DS . 'tmpl' . DS . $default
    );

    // Add Joomla Template Override Paths
    $view->addTemplatePath(
      JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . 'templates'
    );
    $view->addTemplatePath(
      JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . 'templates' . DS . $default
    );

    // Add Joomla default overrides
    $view->addTemplatePath(
      JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . $default
    );
    $view->addTemplatePath(
      JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store'
    );

    // Add Paths for custom J2Store sub templates
    if ($view->params->get('subtemplate')) {
      // If a sub template is specified add paths for it.
      $view->addTemplatePath(
        JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . 'templates' . DS . $view->params->get('subtemplate')
      );
      $view->addTemplatePath(
        JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . $view->params->get('subtemplate')
      );
    }

    // Return the modified view
    return $view;
  }

  // Modify product list tag view
  function onJ2StoreViewProductListTagHtml(&$view_html, &$view, $model)
  {
    // Ignore errors
    F0FPlatform::getInstance()->setErrorHandling(E_ALL, 'ignore');
    // Get the template path
    $view = $this->setTemplatePath($view, 'tag_bootstrap5plus');
    // Load the template
    $result = $view->loadTemplate();

    // If an exception occurs
    if ($result instanceof Exception) {
      // Handle error
      F0FPlatform::getInstance()->raiseError(
        $result->getCode(),
        $result->getMessage()
      );

      // Return error message
      return $result;
    }

    // Get final html output
    $view_html = $result;
  }

  // Modify html output of product details page
  function onJ2StoreViewProductHtml(&$view_html, &$view, $model)
  {
    // Use the view.php layout file
    $view->setLayout('view');
    // Ignore all errors
    F0FPlatform::getInstance()->setErrorHandling(E_ALL, 'ignore');
    // Set the correct template path
    $view = $this->setTemplatePath($view);
    // Load the template
    $result = $view->loadTemplate();

    // If exception occurs
    if ($result instanceof Exception) {
      // Handle error
      F0FPlatform::getInstance()->raiseError(
        $result->getCode(),
        $result->getMessage()
      );

      // Return error message
      return $result;
    }

    // Get final html output
    $view_html = $result;
  }

  // Modify html output of product details tag page
  function onJ2StoreViewProductTagHtml(&$view_html, &$view, $model)
  {
    // Use the view.php layout file
    $view->setLayout('view');
    // Ignore all errors
    F0FPlatform::getInstance()->setErrorHandling(E_ALL, 'ignore');
    // Set the correct template path
    $view = $this->setTemplatePath($view, 'tag_bootstrap5plus');
    // Load template
    $result = $view->loadTemplate();

    // If exception occurs
    if ($result instanceof Exception) {
      // Handle error
      F0FPlatform::getInstance()->raiseError(
        $result->getCode(),
        $result->getMessage()
      );

      // Return error message
      return $result;
    }

    // Get final html output
    $view_html = $result;
  }
}
