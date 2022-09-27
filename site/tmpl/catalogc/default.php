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
JHTML::script(Juri::base() . '/media/com_catalogsystem/js/catalogHelper.js');
$urlStr = "index.php?option=com_catalogsystem&view=problemdetails&id=";

?>

<form action="index.php?option=com_catalogsystem&view=catalogc"
    method="post" name="searchForm" id="searchForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('catalog_name');  ?>
	
	<?php echo $this->form->renderField('catalog_category');  ?>
	
	<?php echo $this->form->renderField('catalog_source');  ?>
    
    <?php echo $this->form->renderField('catalog_mindif');  ?>
    
    <?php echo $this->form->renderField('catalog_maxdif');  ?>
	
	<button type="submit">Filter</button>
</form>

<form action="index.php?option=com_catalogsystem&view=catalogc"
    method="post" name="opForm" id="opForm" enctype="multipart/form-data">
    <table class="table table-striped table-hover" id="myTable">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="toggle" name="toggle" label=" " onclick="toggleAll()">
                </th>
                <th onclick="sortTable(1)">Name</th>
                <th onclick="sortTable(2)">Category</th>
                <th onclick="sortTable(3)">Difficulty</th>
                <th onclick="sortTable(4)">Source</th>
                <th onclick="sortTable(5)">Last Used</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $row) : ?>
                <tr>
                    <?php
                        $xmlStr = '<field name="' . $row->id . '" type="checkbox" label=" "/>';
                        $xml = new SimpleXMLElement($xmlStr);
                        $this->form2->setField($xml);
                    ?>
                    <td><?php echo $this->form2->renderField("$row->id");  ?></td>
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
    <?php echo $this->form2->renderFieldset("opPanel"); ?>
    <button type="submit">Confirm</button>
</form>

