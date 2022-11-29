<?php
// This file holds the HTML and other display information for the Student version of the Sets Page
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Imports
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

// Imports using WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');

// This will be used to generate links back to the catalog page to see all the problems associated with a given set
$urlStr = "index.php?option=com_catalogsystem&view=catalog&set=";
?>

<!--This script must be included to make Joomla's built in pagination functional-->
<script language="javascript" type="text/javascript">
    function tableOrdering( order, dir, task )
    {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
    }
</script>

<!--This form holds the search panel-->
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
	  <button class = "submit-button" onClick= "onLoad()" type="submit">Filter</button>
	  <button  id="filter_clear" name="filter_clear" class="submit-button" onClick="onLoad()" type="submit"> Reset </button>
	  </div>
	</div>
</form>

<!--This generates the Pagination limit selector so users can decide how many results show per page-->
<div>
    <span>Rows Per Page: </span>
    <?php echo $this->pagination->getLimitBox(); ?>
</div>
<!--This form holds the results table. It must be wrapped in a form for Joomla Pagination-->
<form id="adminForm" method="post" name="adminForm">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
                <!--JHTML is used with Pagination to achieve sort by column functionality-->
                <th><?php echo JHTML::_( 'grid.sort', 'Name', 'name', $this->sortDirection, $this->sortColumn); ?></th>
                <th><?php echo JHTML::_( 'grid.sort', 'Number of Problems', 'numProblems', $this->sortDirection, $this->sortColumn); ?></th>
                <th><?php echo JHTML::_( 'grid.sort', 'First Used', 'firstUsed', $this->sortDirection, $this->sortColumn); ?></th>
                <th><?php echo JHTML::_( 'grid.sort', 'Last Used', 'lastUsed', $this->sortDirection, $this->sortColumn); ?></th>
				<th id="zip">Zip Link</th>
            </tr>
        </thead>
        <tbody>
         <?php foreach ($this->items as $i => $row) : ?>
            <tr>
                <!--Here we generate the link back to the Catalog Page to view all problems associated with the set-->
                    <td><?php $url = Route::_($urlStr . $row->set_id); echo "<a onClick= 'onLoad()' href='$url'>$row->name</a>";?></td>
                    <td><?php echo $row->numProblems; ?></td>
                    <td><?php echo $row->firstUsed; ?></td>
                    <td><?php echo $row->lastUsed; ?></td>
					<td><?php 
						if($row->zip!=null) echo "<a href='$row->zip' target='_blank' rel='noopener noreferrer'>Link</a>"; 
					?></td>
              </tr>
            <?php endforeach; ?>
=        </tbody>
    </table>
    <!--This generates the Pagination footer. The hidden inputs are required by Joomla-->
    <?php echo $this->pagination->getListFooter(); ?>
	<!--This generates the loading screen-->
	<div id= "pageloader">
		<svg  class="loader" viewBox="0 0 50 50">
			<circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
		</svg>
	</div>
    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>