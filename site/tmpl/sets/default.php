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

<script language="javascript" type="text/javascript">
    function tableOrdering( order, dir, task )
    {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
    }
</script>

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
	  <button  id="filter_clear" name="filter_clear" class="submit-button" type="submit"> Reset </button>
		<button class = "submit-button" type="submit">Filter</button>
	  </div>
</div>
</form>

<form id="adminForm" method="post" name="adminForm">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
                <th><?php echo JHTML::_( 'grid.sort', 'Name', 'name', $this->sortDirection, $this->sortColumn); ?></th>
                <th><?php echo JHTML::_( 'grid.sort', 'Number of Problems', 'numProblems', $this->sortDirection, $this->sortColumn); ?></th>
                <th id="Col2" class="unsorted">Zip Download</th>
                <th><?php echo JHTML::_( 'grid.sort', 'First Used', 'firstUsed', $this->sortDirection, $this->sortColumn); ?></th>
                <th><?php echo JHTML::_( 'grid.sort', 'Last Used', 'lastUsed', $this->sortDirection, $this->sortColumn); ?></th>
            </tr>
        </thead>
        <tbody>
         <?php foreach ($this->items as $i => $row) : ?>
                <tr>
                    <td><?php $url = Route::_($urlStr . $row->set_id); echo "<a href='$url'>$row->name</a>";?></td>
                    <td><?php echo $row->numProblems; ?></td>
                    <td><?php 
						if($row->zip!=null) echo "<a href='$row->zip' target='_blank' rel='noopener noreferrer'>Download</a>"; 
					?></td>
                        <td><?php echo $row->firstUsed; ?></td>
                        <td><?php echo $row->lastUsed; ?></td>
              </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->pagination->getListFooter(); ?>
    <div>
        <span>Rows Per Page: </span>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>