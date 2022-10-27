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
use ProgrammingTeam\Component\CatalogSystem\Site\Helper\ajaxCategories;
use Joomla\CMS\Router\Route;

require_once __DIR__ . '/../functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');
?>

<form class= "search-box" action="index.php?option=com_catalogsystem&view=catalog"
    method="post" name="com_catalogsystem.catalogsearch" id="com_catalogsystem.catalogsearch" enctype="multipart/form-data">
    <div>
      <div>
  			<?php echo $this->form->renderField('catalog_name');  ?>
  		</div>
  		<div>
  			<?php echo $this->form->renderField('catalog_set');  ?>
  		</div>
  		<div>
  			<?php echo $this->form->renderField('catalog_category');  ?>
  		</div>
  		<div>
  			<?php echo $this->form->renderField('catalog_source');  ?>
  		</div>
      <div class= "rowoptions">
        <div class= "dif" style= "display: flex; flex: 2;">
           <?php echo $this->form->renderField('catalog_mindif');  ?>
          <?php echo $this->form->renderField('catalog_maxdif');  ?>
        </div>
      </div>
      <div class= "rowoptions schedulers">
  		<div class= "date" style= "display: flex; flex: 2;">
        <?php echo $this->form->renderField('catalog_date_before');  ?>
  			<?php echo $this->form->renderField('catalog_date_after');  ?>
  		</div>
    </div>
  <div class= "rowoptions schedulers">
		<div class=  "not_date" style= "display: flex; flex: 2;">
			<?php echo $this->form->renderField('catalog_date_notbefore');  ?>
			<?php echo $this->form->renderField('catalog_date_notafter');  ?>
		</div>
    </div>
    <div class= "end-content">
	<button  id="filter_clear" class="submit-button" type="button" onclick="window.location.reload();"> Reset </button>
	<button class = "submit-button" type="submit">Filter</button>
</div>
   </div>
</form>
<table class="catalog_table" id="myTable">
    <thead>
      <tr>
        <th id= "Col0" class= "unsorted" onclick="sortTable(0)">Name</th>
        <th id= "Col1" class= "unsorted" onclick="sortTable(1)">Category</th>
        <th id= "Col2" class= "unsorted" onclick="sortTable(2)">Difficulty</th>
        <th id= "Col3" class= "unsorted" onclick="sortTable(3)">Source</th>
        <th id= "Col4" class= "unsorted" onclick="sortTable(4)">First Used</th>
        <th id= "Col5" class= "unsorted" onclick="sortTable(5)">Last Used</th>
      </tr>
    </thead>
    <tbody>
        <?php
		if(is_array($this->items))
		{
			foreach ($this->items as $i => $row)
			{
				echo '<tr>';
				$url = Route::_("index.php?option=com_catalogsystem&view=problemdetails&id=" . $row->id);
				echo "<td><a href='$url'>$row->name</a></td>";
				echo "<td>$row->category</td>";
				echo "<td>$row->difficulty</td>";
				echo "<td>$row->source</td>";
				echo "<td>$row->firstUsed</td>";
				echo "<td>$row->lastUsed</td>";
				echo '</tr>';
			}
		}
		?>
    </tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>
