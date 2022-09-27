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
$urlStr = "index.php?option=com_catalogsystem&view=catalog&set=";

?>

<form action="index.php?option=com_catalogsystem&view=sets"
    method="post" name="setsForm" id="setsForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('sets_name');  ?>
	
	<button type="submit">Filter</button>
</form>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Number of Problems</th>
            <th>Zip URL</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $i => $row) : ?>
            <tr>
                <td><?php $url = Route::_($urlStr . $row->set_id); echo "<a href='$url'>$row->name</a>";?></td>
                <td><?php echo $row->numProblems; ?></td>
                <td><?php echo $row->zip; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>

