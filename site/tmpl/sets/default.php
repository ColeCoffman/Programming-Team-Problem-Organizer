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

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');
$urlStr = "index.php?option=com_catalogsystem&view=catalog&set=";
?>

<form class= "search-box" action="index.php?option=com_catalogsystem&view=sets"
    method="post" name="setsForm" id="setsForm" enctype="multipart/form-data">
    <div>
	<div>
		<?php echo $this->form->renderField('sets_name');  ?>
	</div>
  <div class= "rowoptions schedulers">
  <div class= "date" style= "display: flex; flex: 2;">
    <?php echo $this->form->renderField('sets_date_before');  ?>
    <?php echo $this->form->renderField('sets_date_after');  ?>
  </div>
</div>
<div class= "rowoptions schedulers">
  <div class= "not_date" style= "display: flex; flex: 2;">
    <?php echo $this->form->renderField('sets_date_notbefore');  ?>
    <?php echo $this->form->renderField('sets_date_notafter');  ?>
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
            <th id="Col0" class="unsorted" onclick="sortTable(0)">Name</th>
            <th id="Col1" class="unsorted"onclick="sortTable(1)">Number of Problems</th>
            <th id="Col2" class="unsorted" onclick="sortTable(2)">Zip URL</th>
      			<th id="Col3" class="unsorted" onclick="sortTable(3)">First Used</th>
      			<th id= "Col4" class="unsorted" onclick="sortTable(4)">Last Used</th>
        </tr>
    </thead>
    <tbody>
     <?php foreach ($this->items as $i => $row) : ?>
            <tr>
                <td><?php $url = Route::_($urlStr . $row->set_id); echo "<a href='$url'>$row->name</a>";?></td>
                <td><?php echo $row->numProblems; ?></td>
                <td><?php echo $row->zip; ?></td>
      				<td><?php echo $row->firstUsed; ?></td>
      				<td><?php echo $row->lastUsed; ?></td>
          </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>
