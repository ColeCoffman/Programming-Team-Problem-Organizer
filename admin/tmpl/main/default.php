<?php


// This is a default admin template.
// Since our component only adds site pages, this is unused


use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<h2><?= Text::_('COM_CATALOGSYSTEM_MSG_TEST') ?></h2>
Hello Foos:  <?= $this->get('Msg') ?>
<?php console.log($this->get('Msg')); ?>