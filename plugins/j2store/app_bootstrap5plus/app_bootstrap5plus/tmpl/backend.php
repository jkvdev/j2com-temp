<?php

/**
 * @package J2Store
 * @copyright Copyright (c)2025 Valentin Costea / J2Store.org
 * @license GNU GPL v3 or later
 */

// Import Joomla packages
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

//  Security check
// No direct access
defined('_JEXEC') or die;
?>
<!-- Generate form tag -->
<form
    id="adminForm"
    class="form-horizontal form-validate"
    name="adminForm"
    method="post"
    action="<?= Route::_('index.php'); ?>">
    <?php

    // Generate hidden input fields
    echo  J2Html::hidden('option', 'com_j2store');
    echo  J2Html::hidden('view', 'apps');
    echo  J2Html::hidden(
        'task',
        'view',
        array('id' => 'task')
    );

    // Generate CSRF Token
    echo HTMLHelper::_('form.token'); ?>
</form>