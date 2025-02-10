<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.B5Indigo
 *
 * @copyright   (C) YEAR Your Name
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * This is a heavily stripped down/modified version of the default Cassiopeia template, designed to build new templates off of.
 */

defined('_JEXEC') or die;  //required for basically ALL php files in Joomla, for security. Prevents direct access to this file by url.

//Imports ("use" statements) - objects from Joomla that we want to use in this file
use Joomla\CMS\Factory; // Factory class: Contains static methods to get global objects from the Joomla framework. Very important!
use Joomla\CMS\HTML\HTMLHelper; // HTMLHelper class: Contains static methods to generate HTML tags.
use Joomla\CMS\Language\Text; // Text class: Contains static methods to get text from language files
use Joomla\CMS\Uri\Uri; // Uri class: Contains static methods to manipulate URIs.

/** @var Joomla\CMS\Document\HtmlDocument $this */

// Get Joomla Application
$app = Factory::getApplication();
// Get the Web Asset Manager - used to load CSS and JS files
$wa  = $this->getWebAssetManager();

// Add Favicon from images folder
$this->addHeadLink(HTMLHelper::_(
  'image',
  'favicon.ico',
  '',
  [],
  true,
  1
), 'icon', 'rel', [
  'type' => 'image/x-icon'
]);


// Detecting Active Page Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
// Gets the menu but should be done only in the frontend
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

//Get params from template styling
//If you want to add your own parameters you may do so in templateDetails.xml
$testparam =  $this->params->get('testparam');

//uncomment to see how this works on site... it just shows 1 or 0 depending on option selected in style config.
//You can use this style to get/set any param according to instructions at https://kevinsguides.com/guides/webdev/joomla4/joomla-4-templates/adding-config
//echo('the value of testparam is: '.$testparam);

// Get this template's path
$templatePath = 'templates/' . $this->template;


//load bootstrap collapse js (required for mobile menu to work)
//this loads collapse.min.js from media/vendor/bootstrap/js - you can check out that folder to see what other bootstrap js files are available if you need them
HTMLHelper::_('bootstrap.collapse');
//dropdown needed for 2nd level menu items
HTMLHelper::_('bootstrap.dropdown');
//You could also load all of bootstrap js with this line, but it's not recommended because it's a lot of extra code that you probably don't need
//HTMLHelper::_('bootstrap.framework');


//Register web assets (Css/JS) with the Web Asset Manager
//The files are defined in joomla.asset.json!!! If you don't want to use the included CSS or JS, just remove these lines or replace the CSS/JS files with your own code!
$wa->useStyle('template.b5indigo.mainstyles');
$wa->useStyle('template.b5indigo.user');
$wa->useScript('template.b5indigo.scripts');

//Set viewport meta tag for mobile responsiveness -- very important for scaling on mobile devices
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

?>

<?php // Everything below here is the actual "template" part of the template. Where we put our HTML code for the layout and such. 
?>
<!DOCTYPE html>
<html
  lang="<?= $this->language; ?>"
  dir="<?= $this->direction; ?>">

<head>

  <?php // Loads important metadata like the page title and viewport scaling 
  ?>
  <jdoc:include type="metas" />

  <?php // Loads the site's CSS and JS files from web asset manager 
  ?>
  <jdoc:include type="styles" />
  <jdoc:include type="scripts" />

  <?php /** You can put links to CSS/JS just like any regular HTML page here too, and remove the jdoc:include script/style lines above if you want.
   * Do not delete the metas line though
   * 
   * For example, if you want to manually link to a custom stylesheet or script, you can do it like this:
   * <link rel="stylesheet" href="https://mysite.com/templates/mytemplate/mycss.css" type="text/css" />
   * <script src="https://mysite.com/templates/mytemplate/myscript.js"></script>
   * */
  ?>

</head>

<?php // you can change data-bs-theme to dark for dark mode  // 
?>

<body
  class="site <?= $pageclass; ?>"
  data-bs-theme="light">

  <!-- Main Header and navigation -->
  <div class="container-fluid">
    <!-- Keep the navigation in a container -->
    <div class="container d-flex flex-row justify-content-between align-items-center py-3">
      <!-- Header with Title -->
      <header class='fs-2 fw-semibold'>
        <!-- Site Name -->
        <span class=''>
          <!-- Redirect Link -->
          <a
            href="index.php"
            class="navbar-brand text-black">
            <!-- Text -->
            <?= ($sitename); ?>
          </a>
        </span>
      </header>

      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg fw-medium text-primary">

        <?php // Update 1.14 - Added support for mobile menu with bootstrap 
        ?>
        <!-- Burger Menu -->
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#mainmenu"
          aria-controls="mainmenu"
          aria-expanded="false"
          aria-label="Toggle navigation">

          <!-- Menu Icon -->
          <span class="navbar-toggler-icon"></span>
        </button>

        <?php // Put menu links in the navbar - main menu must be in the "menu" position!!! Only supports top level and 1 down, so no more than 1 level of child items 

        // If any module is assigned the menu position
        if ($this->countModules('menu')): ?>
          <div
            class="collapse navbar-collapse"
            id="mainmenu">
            <jdoc:include type="modules" name="menu" style="none" />
          </div>
        <?php endif; ?>

      </nav>
    </div>
  </div>

  <!-- Include breadcrumbs -->
  <?php if ($this->countModules('breadcrumbs')): ?>
    <div class="bg-light text-dark m-0 p-0">
      <div class="container">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
      </div>
    </div>
  <?php endif; ?>

  <!-- Main Content Area -->
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg">
        <main class="py-5">
          <jdoc:include type="message" />
          <jdoc:include type="component" />
        </main>
      </div>
      <?php if ($this->countModules('sidebar')): ?>
        <div class="col-12 col-lg-3">
          <jdoc:include type="modules" name="sidebar" style="none" />
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <div class="bg-dark text-white mt-5">
    <div class="container">
      <footer class='p-3'>
        <span>&copy; 2025 Valentin Costea</span>
        <!-- <?php if ($this->countModules('footer')): ?>
                    <jdoc:include type="modules" name="footer" style="none" />
                <?php endif; ?> -->
      </footer>
    </div>
  </div>


  <?php // Include any debugging info 
  ?>
  <jdoc:include type="modules" name="debug" style="none" />
</body>

</html>