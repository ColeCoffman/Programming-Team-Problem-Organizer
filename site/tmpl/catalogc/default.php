<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use ProgrammingTeam\Component\CatalogSystem\Site\Helper\ajaxCategories;
use Joomla\CMS\Router\Route;

require_once dirname(__FILE__).'/../functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper')
    ->useStyle('catalog');

// Take coaches from the catalogc to editproblem (instead of problemdetails)
$urlStr = "index.php?option=com_catalogsystem&view=editproblem&id=";

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

<?php
    $linkStr = Route::_("index.php?option=com_catalogsystem&view=addproblem");
    echo "<a href='$linkStr'><button class='return-button'><label class='add-label'>Add New Problem</label></button></a>";
?>

<form class= "search-box" action="index.php?option=com_catalogsystem&view=catalogc"
    method="post" name="searchForm" id="searchForm" enctype="multipart/form-data">
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
        <button class = "submit-button" type="submit">Filter</button>
        <button  id="filter_clear" name="filter_clear" class="submit-button" type="submit"> Reset </button>
</div>
   </div>
</form>

<form action="index.php?option=com_catalogsystem&view=catalogc"
    method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
              <?php
                        $xmlStr = '<field name="toggle" class= "toggle" type="checkbox" onclick= "toggleAll()" label=""/>';
                        $xml = new SimpleXMLElement($xmlStr);
                        $this->form2->setField($xml);
                    ?>
              <th id="checkcolumn">
                  <?php echo $this->form2->renderField("toggle");?>
              </th>
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
					$xmlStr = '<field name="' . $row->id . '" type="checkbox" label=" "/>';
                    $xml = new SimpleXMLElement($xmlStr);
                    $this->form2->setField($xml);
					echo '<td>' . $this->form2->renderField("$row->id") . '</td>';
					$url = Route::_($urlStr . $row->id);
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
    <div>
        <span>Rows Per Page: </span>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
    
	<div class="panel-box">
			<?php echo $this->form2->renderFieldset("opPanel"); ?>
			<div class= "end-content">
			<button class = "submit-button" type="submit">Confirm</button>
		  </div>
	</div>

</form>
