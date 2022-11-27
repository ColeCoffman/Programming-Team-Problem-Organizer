<?php

// This file holds the HTML and other display information for the Coach version of the Catalog Page
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Imports
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

// Imports through WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper')
    ->useStyle('catalog');

// This is used to generate the link to each problem's edit page
$urlStr = "index.php?option=com_catalogsystem&view=editproblem&id=";

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

<!--Code for the Add Problem button-->
<?php
    $linkStr = Route::_("index.php?option=com_catalogsystem&view=addproblem");
    echo "<a onclick= 'onLoad()' href='$linkStr'><button class='return-button'><label class='add-label'>Add New Problem</label></button></a>";
?>

<!--This form holds the search panel-->
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
	<button  id="filter_clear" name="filter_clear" onclick= "onLoad()" class="submit-button" type="submit"> Reset </button>
	<button class = "submit-button" onclick= "onLoad()" type="submit">Filter</button>
</div>
   </div>
</form>

<!--This form holds the results table and Coach Operations Panel-->
<form action="index.php?option=com_catalogsystem&view=catalogc"
    method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <!--This generates the Pagination limit selector so users can decide how many results show per page-->
    <div>
        <span>Rows Per Page: </span>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
              <!--This code generates the select/deselect all box at the top of the results table-->
              <?php
                $xmlStr = '<field name="toggle" class= "toggle" type="checkbox" onclick= "toggleAll()" label=""/>';
                $xml = new SimpleXMLElement($xmlStr);
                $this->form2->setField($xml);
              ?>
              <th id="checkcolumn">
                  <?php echo $this->form2->renderField("toggle");?>
              </th>
              <!--JHTML is used with Pagination to achieve sort by column functionality-->
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
                    // This code generates the checkbox for each row of the table
					$xmlStr = '<field name="' . $row->id . '" type="checkbox" label=" "/>';
                    $xml = new SimpleXMLElement($xmlStr);
                    $this->form2->setField($xml);
					echo '<td>' . $this->form2->renderField("$row->id") . '</td>';
                    // Here we generate the link to each problem's edit page
					$url = Route::_($urlStr . $row->id);
					echo "<td><a onclick='onLoad()' href='$url'>$row->name</a></td>";
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
    <!--This generates the Pagination footer. The hidden inputs are required by Joomla-->
    <?php echo $this->pagination->getListFooter(); ?>
    <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
    
    <!--This generates the Coach Operations Panel-->
	<div id="overlay" class="panel-box">
			<?php echo $this->form2->renderFieldset("opPanel"); ?>
			<div class= "end-content">
			<button class = "submit-button" onClick= "onLoad()" type="submit">Confirm</button>
		  </div>
	</div>
	<button id= "overlay-button" class= "edit-button" type="button" onclick="operation()"><label class="edit-label"/></button>
	<!--This generates the loading screen-->
	<div id= "pageloader">
		<svg  class="loader" viewBox="0 0 50 50">
			<circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
		</svg>
	</div>
</form>
