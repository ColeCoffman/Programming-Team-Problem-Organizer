<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<form action="index.php?option=com_catalogsystem&view=addproblem"
    method="post" name="addForm" id="addForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('name');  ?>
	
	<?php echo $this->form->renderField('category');  ?>
	
	<?php echo $this->form->renderField('source');  ?>
    
    <?php echo $this->form->renderField('dif');  ?>
    
    <?php echo $this->form->renderField('firstUse');  ?>
    
    <?php echo $this->form->renderField('set');  ?>
	
	<button type="submit">Add Problem</button>
</form>