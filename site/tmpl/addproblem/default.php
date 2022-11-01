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

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;

require_once dirname(__FILE__).'/../functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');


if(is_object($this->result))
{
	// If a problem was added successfully, display a temporary confirmation message
	if($this->result->state === 0)
	{
		echo '<br/><b>[Problem Added Successfully]</b><br/>';
	}
	// If a problem failed to add, display the error message
	else if($this->result->state < 0)
	{
		echo "<br/><b>Problem Failed to Add:</b><br/>$this->result->msg<br/>";
	}
}


?>

<form action="index.php?option=com_catalogsystem&view=addproblem" class="add-box"
    method="post" name="com_catalogsystem.AddProblem" id="com_catalogsystem.AddProblem" enctype="multipart/form-data">
    <div>
      <div>
        <?php echo $this->form->renderField('name');  ?>
      </div>
    <div>
        <?php echo $this->form->renderField('cattoggle');  ?>
    </div>
      <div>
        <?php echo $this->form->renderField('category');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('newcategory');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('sourcetoggle');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('source');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('newsource');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('dif');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('set');  ?>
      </div>
      <div class= "schedulers">
        <?php echo $this->form->renderField('firstUse');  ?>
        <?php echo $this->form->renderField('firstUseTeam');  ?>
      </div>
      <div class="fileupload">
        <?php echo $this->form->renderField('pdfupload');  ?>
      </div>
      <div class="fileupload">
        <?php echo $this->form->renderField('zipupload');  ?>
      </div>
    </div>

    <div class= "end-content">
  <button class = "submit-button" type="submit">AddProblem</button>
  </div>
</form>
