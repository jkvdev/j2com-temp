<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2012 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//  Security check
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$title = $item->anchor_title
	? ' title="' . $item->anchor_title . '"'
	: '';
$anchor_css = $item->anchor_css ?: '';
$linktype = $item->title;

// Check if the item is active (Joomla automatically assigns the 'active' class to the selected menu item)
$activeClass = $item->active ? 'active' : '';

// If menu item is multi level
if ($item->deeper) : ?>
	<li class="nav-item dropdown">
		<a
			class="nav-link dropdown-toggle"
			data-bs-toggle="dropdown"
			role="button"
			aria-expanded="false">
			<?= $linktype; ?>
		</a>
	<?php else : ?>
		<li class="nav-item <?= $activeClass; ?>">
		<a
			class="nav-link <?= $activeClass; ?>"
			href="<?= $item->flink; ?>" <?= $title; ?>>
			<?= $linktype; ?>
		</a>
	<?php endif; ?>