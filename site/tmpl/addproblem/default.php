<?php

// This file holds the HTML and display information associated with the Add Problem page
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Imports
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

// Imports through WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');

if(is_object($this->result))
{
	// If a problem was added successfully, display a temporary confirmation message
	if($this->result->state === 0)
	{
		JFactory::getApplication()->enqueueMessage('Problem Added Successfully', 'success');
	}
	// If a problem failed to add, display the error message
	else if($this->result->state < 0)
	{
		JFactory::getApplication()->enqueueMessage($this->result->msg, 'error');
	}
}

// Link back to the catalog
$urlStr = Route::_("index.php?option=com_catalogsystem&view=catalogc");
echo "<a onclick= 'onLoad()' href='$urlStr'><button class='return-button'><label class='return-label'>Back</label></button></a>";
?>

<!--Holds all the fields for adding a problem-->
<form action="index.php?option=com_catalogsystem&view=addproblem" class="add-box"
    method="post" name="com_catalogsystem.AddProblem" id="com_catalogsystem.AddProblem" enctype="multipart/form-data">
    <div class= "details">
      <div>
        <?php echo $this->form->renderField('name');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('category');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('newcategory');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('cattoggle');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('source');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('newsource');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('sourcetoggle');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('dif');  ?>
      </div>
      <div>
        <?php echo $this->form->renderField('set');  ?>
      </div>
      <div class= "schedulers addproblem">
        <?php echo $this->form->renderField('firstUse');  ?>
	  </div>
	  <div>
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
  <button class = "submit-button" onclick= "onLoad()" type="submit">Add Problem</button>
  </div>
  <!--This generates the loading screen-->
  <div id= "pageloader">
		<svg  class="loader" viewBox="0 0 50 50">
			<circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
		</svg>
	</div>
</form>
