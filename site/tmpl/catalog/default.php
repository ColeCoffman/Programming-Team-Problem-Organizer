<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use ProgrammingTeam\Component\CatalogSystem\Site\Helper\ajaxCategories;
use Joomla\CMS\Router\Route;

require_once dirname(__FILE__).'/../functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');
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
      <button id="filter_submit" name="filter_submit" class = "submit-button" type="submit">Filter</button>
      <button id="filter_clear" name="filter_clear" class="submit-button" type="submit"> Reset </button>
	
</div>
   </div>
</form>
<div>
        <span>Rows Per Page: </span>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
<form id="adminForm" method="post" name="adminForm">
    <table class="catalog_table" id="myTable">
        <thead>
          <tr>
            <th><?php echo JHTML::_( 'grid.sort', 'Name', 'name', $this->sortDirection, $this->sortColumn); ?></th>
            <th><?php echo JHTML::_( 'grid.sort', 'Category', 'category', $this->sortDirection, $this->sortColumn); ?></th>
            <th><?php echo JHTML::_( 'grid.sort', 'Difficulty', 'difficulty', $this->sortDirection, $this->sortColumn); ?></th>
            <th><?php echo JHTML::_( 'grid.sort', 'Source', 'source', $this->sortDirection, $this->sortColumn); ?></th>
            <th><?php echo JHTML::_( 'grid.sort', 'First Used', 'firstUsed', $this->sortDirection, $this->sortColumn); ?></th>
            <th><?php echo JHTML::_( 'grid.sort', 'Last Used', 'lastUsed', $this->sortDirection, $this->sortColumn); ?></th>
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
    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>