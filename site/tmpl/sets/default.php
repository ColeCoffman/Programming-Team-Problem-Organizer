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
//JHTML::script(Juri::base() . '/media/com_catalogsystem/js/categories.js');
$urlStr = "index.php?option=com_catalogsystem&view=catalog&set=";

?>

<form class= "search-box" action="index.php?option=com_catalogsystem&view=sets"
    method="post" name="setsForm" id="setsForm" enctype="multipart/form-data">
	<div>
		<?php echo $this->form->renderField('sets_name');  ?>
	</div>
	<div style = "display: flex; flex-wrap: wrap;">
        <?php echo $this->form->renderField('sets_date_after');  ?>
        <?php echo $this->form->renderField('sets_date_before');  ?>
    </div>
	<div style = "display: flex; flex-wrap: wrap;">
        <?php echo $this->form->renderField('sets_date_notbefore');  ?>
        <?php echo $this->form->renderField('sets_date_notafter');  ?>
    </div>

  <div class= "end-content">
     <button class = "submit-button" type="submit">Filter</button>
   </div>
</form>

<table class="catalog_table" id="myTable">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Name ↕</th>
            <th onclick="sortTable(1)">Number of Problems ↕</th>
            <th onclick="sortTable(2)">Zip URL ↕</th>
			<th onclick="sortTable(3)">First Used ↕</th>
			<th onclick="sortTable(4)">Last Used ↕</th>
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
