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
//JHTML::script(Juri::base() . '/media/com_catalogsystem/js/catalogHelper.js');

// JHTML::script(Juri::base() . '/media/com_catalogsystem/js/categories.js');


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
       <div style = "display: flex; flex-wrap: wrap;">
            <?php echo $this->form->renderField('catalog_mindif');  ?>
           <?php echo $this->form->renderField('catalog_maxdif');  ?>
       </div>
    </div>
    <div class= "end-content">
  	   <button class = "submit-button" type="submit">Filter</button>
     </div>
</form>

<table class="catalog_table" id="myTable">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Name ↕</th>
            <th onclick="sortTable(1)">Category ↕</th>
            <th onclick="sortTable(2)">Difficulty ↕</th>
            <th onclick="sortTable(3)">Source ↕</th>
            <th onclick="sortTable(4)">Last Used ↕</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $i => $row) : ?>
            <tr>
                <td><?php $url = Route::_("index.php?option=com_catalogsystem&view=problemdetails&id=" . $row->id); echo "<a href='$url'>$row->name</a>";?></td>
                <td><?php echo $row->category; ?></td>
                <td><?php echo $row->difficulty; ?></td>
                <td><?php echo $row->source; ?></td>
                <td><?php echo $row->lastUsed; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>
