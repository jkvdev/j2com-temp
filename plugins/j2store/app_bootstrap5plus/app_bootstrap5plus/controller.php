<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

//  Prevent direct access to this file
defined('_JEXEC') or die('Restricted access');

// Require package only once
require_once(JPATH_ADMINISTRATOR . '/components/com_j2store/library/appcontroller.php');

// Define a Joomla Controller class
class J2StoreControllerAppBootstrap5Plus extends J2StoreAppController
{
    // Identify app controller variable
    var $_element   = 'app_bootstrap5plus';
}
