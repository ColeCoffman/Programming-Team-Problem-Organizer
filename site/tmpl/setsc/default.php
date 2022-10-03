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
$urlStr = "index.php?option=com_catalogsystem&view=catalogc&set=";

?>

<form class= "search-box" action="index.php?option=com_catalogsystem&view=setsc"
    method="post" name="setsForm" id="setsForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('sets_name');  ?>

  <div class= "end-content">
     <button class = "submit-button" type="submit">Filter</button>
   </div>
</form>

<form action="index.php?option=com_catalogsystem&view=setsc"
    method="post" name="opForm" id="opForm" enctype="multipart/form-data">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="toggle" name="toggle" label=" " onclick="toggleAll()">
                </th>
                <th onclick="sortTable(1)">Name</th>
                <th onclick="sortTable(2)">Number of Problems</th>
                <th onclick="sortTable(3)">Zip URL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $row) : ?>
                <tr>
                    <?php
                        $xmlStr = '<field name="' . $row->set_id . '" type="checkbox" label=" "/>';
                        $xml = new SimpleXMLElement($xmlStr);
                        $this->form2->setField($xml);
                    ?>
                    <td><?php echo $this->form2->renderField("$row->set_id");  ?></td>
                    <td><?php $url = Route::_($urlStr . $row->set_id); echo "<a href='$url'>$row->name</a>";?></td>
                    <td><?php echo $row->numProblems; ?></td>
                    <td><?php echo $row->zip; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->pagination->getListFooter(); ?>
    <div class="search-box">
        <?php echo $this->form2->renderFieldset("opPanel"); ?>
        <button type="submit">Confirm</button>
    </div>
    
</form>
