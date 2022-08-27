<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_catalogsystem
 *
 * @copyright
 * @license     GNU General Public License version 3; see LICENSE
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');


?>
<h2><?= Text::_('COM_CATALOGSYSTEM_MSG_TEST') ?></h2>
Hello Foos:  <?= $this->get('Msg') ?>
<?php console.log($this->get('Msg')); ?>