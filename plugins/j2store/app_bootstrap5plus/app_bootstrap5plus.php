<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

//  Ensure this file is included by a parent file
// Give no direct access permissions
defined('_JEXEC') or die('Restricted access');

// Require plugin instance only once
require_once(JPATH_ADMINISTRATOR . '/components/com_j2store/library/plugins/app.php');

// Import Joomla packages
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;

// Class definition that extends the default class
class plgJ2StoreApp_bootstrap5plus extends J2StoreAppPlugin
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     * forcing it to be unique
     */
    // Get unique plugin name / identifier
    var $_element   = 'app_bootstrap5plus';

    /**
     * Overriding
     *
     * @param $row
     * @return string
     */
    // Check if the current app is this plugin
    function onJ2StoreGetAppView($row)
    {
        // Ensure function only runs for this plugin
        if (!$this->_isMe($row)) {
            return null;
        }
        // Run View List function
        return $this->viewList();
    }

    // Check if J2STore version 4 is being used
    function onJ2StoreIsJ2Store4($element)
    {
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

    //  Main View List function
    function viewList()
    {
        // Get J2Store application instance
        $app = J2Store::platform()->application();

        // Create and configure the Toolbar
        $toolbar = Toolbar::getInstance('toolbar');
        // Generate admin UI
        $toolbar->title(
            Text::_('J2STORE_APP') .
                '-' .
                Text::_('PLG_J2STORE_' .
                    strtoupper((string) $this->_element)),
            'j2store-logo'
        );
        // Add back button to Joomla admin toolbar
        $toolbar->back(
            'J2STORE_BACK_TO_DASHBOARD',
            'index.php?option=com_j2store'
        );

        // Create an object to store variables and load backend layout
        $vars = new \stdClass();
        $id = $app->input->getInt('id', '0');
        $vars->id = $id;
        return $this->_getLayout('backend', $vars);
    }

    // Decode special characters
    public function escape($var)
    {
        return htmlspecialchars_decode($var, ENT_COMPAT);
    }

    // Managing template folders
    function onJ2StoreTemplateFolderList(&$folder)
    {
        // If bootstrap5plus folder exists
        if (!in_array('bootstrap5plus', $folder)) {
            // Add folder to J2Store's template folders
            $folder[] = 'bootstrap5plus';
        }

        // If tag_bootstrap5plus folder exists
        if (!in_array('tag_bootstrap5plus', $folder)) {
            // Add folder to J2Store's template folders
            $folder[] = 'tag_bootstrap5plus';
        }
    }

    // Override product list template with bootstrap 5 plus
    function onJ2StoreViewProductListHtml(&$view_html, &$view, $model)
    {
        // Ignore all errors
        F0FPlatform::getInstance()->setErrorHandling(
            E_ALL,
            'ignore'
        );
        // Get template
        $view = $this->setTemplatePath($view);
        // Load template
        $result = $view->loadTemplate();

        // If errors exists
        if ($result instanceof Exception) {
            // Generate and show error message
            F0FPlatform::getInstance()->raiseError(
                $result->getCode(),
                $result->getMessage()
            );

            return $result;
        }

        // Get the html result
        $view_html = $result;
    }

    // Define template path
    function setTemplatePath($view, $default = 'bootstrap5plus')
    {
        // Get application Instance
        $app = J2Store::platform()->application();
        // If directory separator is not defined, define one
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

        // Look for template files in component folders
        $view->addTemplatePath(
            JPATH_SITE . DS . 'plugins' . DS . 'j2store' . DS . $this->_element . DS . $this->_element . DS . 'tmpl' . DS . $default
        );

        // Look for overrides in template folder (J2 template structure)
        $view->addTemplatePath(
            JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . 'templates'
        );
        $view->addTemplatePath(
            JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . 'templates' . DS . $default
        );

        // Look for overrides in template folder (Joomla! template structure)
        $view->addTemplatePath(
            JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . $default
        );
        $view->addTemplatePath(
            JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store'
        );

        // Look for specific J2 theme files / sub templates
        if ($view->params->get('subtemplate')) {
            // Add sub templates
            $view->addTemplatePath(
                JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . 'templates' . DS . $view->params->get('subtemplate')
            );
            $view->addTemplatePath(
                JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_j2store' . DS . $view->params->get('subtemplate')
            );
        }
        return $view;
    }

    // Override product tag list view
    function onJ2StoreViewProductListTagHtml(&$view_html, &$view, $model)
    {
        // Ignore all errors
        F0FPlatform::getInstance()->setErrorHandling(
            E_ALL,
            'ignore'
        );
        // Get template
        $view = $this->setTemplatePath($view, 'tag_bootstrap5plus');
        // Load template
        $result = $view->loadTemplate();

        // If any errors occur
        if ($result instanceof Exception) {
            // Generate and show error message
            F0FPlatform::getInstance()->raiseError(
                $result->getCode(),
                $result->getMessage()
            );

            return $result;
        }

        // Get html result
        $view_html = $result;
    }

    // Override how product views are rendered
    function onJ2StoreViewProductHtml(&$view_html, &$view, $model)
    {
        // Set the layout of the view
        $view->setLayout('view');
        // Ignore all errors
        F0FPlatform::getInstance()->setErrorHandling(
            E_ALL,
            'ignore'
        );
        // Get the template
        $view = $this->setTemplatePath($view);
        // Load the template
        $result = $view->loadTemplate();

        // If errors occur
        if ($result instanceof Exception) {
            // Generate error message
            F0FPlatform::getInstance()->raiseError(
                $result->getCode(),
                $result->getMessage()
            );

            return $result;
        }

        // Get html result
        $view_html = $result;
    }

    // Override how tag views are rendered
    function onJ2StoreViewProductTagHtml(&$view_html, &$view, $model)
    {
        // Set the layout of the view
        $view->setLayout('view');
        // Ignore error messages
        F0FPlatform::getInstance()->setErrorHandling(
            E_ALL,
            'ignore'
        );
        // Get the template view
        $view = $this->setTemplatePath($view, 'tag_bootstrap5plus');
        // Load template
        $result = $view->loadTemplate();

        // If errors occur
        if ($result instanceof Exception) {
            // Generate error message
            F0FPlatform::getInstance()->raiseError(
                $result->getCode(),
                $result->getMessage()
            );

            return $result;
        }

        // Get html result
        $view_html = $result;
    }
}
