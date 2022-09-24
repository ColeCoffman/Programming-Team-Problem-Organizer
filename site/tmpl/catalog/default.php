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

JHTML::script(Juri::base() . '/media/com_catalogsystem/js/categories.js');
$urlStr = "index.php?option=com_catalogsystem&view=problemdetails&id=";

//<form action="index.php?option=com_catalogsystem&view=catalog" method="post" name="searchForm" id="searchForm" enctype="multipart/form-data">
?>

<form action="cataloglink?view=catalog"
    method="post" name="searchForm" id="searchForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('name');  ?>
	
	<?php echo $this->form->renderField('category');  ?>
	
	<?php echo $this->form->renderField('source');  ?>
    
    <?php echo $this->form->renderField('mindif');  ?>
    
    <?php echo $this->form->renderField('maxdif');  ?>
	
	<button type="submit">Filter</button>
</form>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Difficulty</th>
            <th>Source</th>
            <th>Last Used</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $i => $row) : ?>
            <tr>
                <td><?php $url = Route::_($urlStr . $row->id); echo "<a href='$url'>$row->name</a>";?></td>
                <td><?php echo $row->category; ?></td>
                <td><?php echo $row->difficulty; ?></td>
                <td><?php echo $row->source; ?></td>
                <td><?php echo $row->lastUsed; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>

