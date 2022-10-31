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

require_once dirname(__FILE__).'/../functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper')
    ->useStyle('catalog');

// Take coaches from the catalogc to editproblem (instead of problemdetails)
$urlStr = "index.php?option=com_catalogsystem&view=editproblem&id=";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST input and file uploads
    $app  = Factory::getApplication();
    $postData = $app->input->post->get('jform2', array(), "array");
    if($postData != null)
    {
	    $operation = $postData['op'];
	    unset($postData['op']);
	    $selected = array_keys($postData);

	    $db    = Factory::getContainer()->get('DatabaseDriver');
	    $query = $db->getQuery(true);
	    switch ($operation['desiredOp'])
	    {
		    case 0: // Record Use
			    foreach ($selected as $id)
			    { // ID = Problem ID
				    $query->clear();
				    $columns = array('problem_id', 'team_id', 'date');
                    
                    $teamID = 'NULL';
                    $q_teamID = $db->getQuery(true);
                    $q_teamID->select('t.id AS tId')
                        ->from('com_catalogsystem_team AS t')
                        ->where('t.name = ' . sqlString($operation['useTeam']));
                    $db->setQuery($q_teamID);
                    $r_teamID = $db->loadObject();
                    $teamID = sqlInt(objGet($r_teamID, 'tId'), 1);
                    
				    $values  = array(sqlInt($id), $teamID, sqlDate($operation['useDate']));
				    $query->insert('com_catalogsystem_history')
					    ->columns($db->quoteName($columns))
					    ->values(implode(',', $values));
				    $db->setQuery($query);
				    $db->execute();
			    }
			    echo "<meta http-equiv='refresh' content='0'>";
			    break;
		    case 1: // Add to Set
			    $error = false;
			    foreach ($selected as $id) // problem id
			    {
				    foreach ($operation['setName'] as $setID)
				    {
					    $query->clear();
					    $columns = array('set_id', 'problem_id');
					    $values  = array(sqlInt($setID), sqlInt($id));
					    $query->insert('com_catalogsystem_problemset')
						    ->columns($db->quoteName($columns))
						    ->values(implode(',', $values));
					    $db->setQuery($query);
					    if ($db->execute() != 1)
					    { // If there was an error / executes
						    $query->clear();
						    $query->select($db->quoteName(array('id', 'name')));
						    $query->from($db->quoteName('com_catalogsystem_problem'));
						    $query->where($db - quoteName('id') . ' LIKE ' . $db->quoteName('$id'));
						    $db->setQuery($query);
						    $problemName = $db->loadResult();
						    $query->clear();
						    $query->select($db->quoteName(array('id', 'name')));
						    $query->from($db->quoteName('com_catalogsystem_set'));
						    $query->where($db - quoteName($id) . ' LIKE ' . $db->quoteName($setID));
						    $db->setQuery($query);
						    $setName = $db->loadResult();
						    $app->enqueueMessage("There was an issue adding problem {$problemName} to set {$setName}", "error");
						    $query->clear();
					    }

				    }

			    }
			    echo "<meta http-equiv='refresh' content='0'>";
			    break;
		    case 2: // Delete
			    foreach ($selected as $id) // problem id
			    {
				    $query->clear();
				    $query->delete($db->quoteName('com_catalogsystem_problem'));
				    $query->where($db->quoteName('id') . ' = ' . $db->quote($id));
				    $db->setQuery($query);
				    if ($db->execute() != 1)
				    {
					    $query->clear();
					    $query->select($db->quoteName(array('id', 'name')));
					    $query->from($db->quoteName('com_catalogsystem_problem'));
					    $query->where($db->quoteName('id') . ' = ' . $db->quoteName($id));
					    $db->setQuery($query);
					    $problemName = $db->loadResult();
					    $app->enqueueMessage("There was an error deleting problem {$problemName}", "error");
					    $query->clear();
				    }

			    }
			    echo "<meta http-equiv='refresh' content='0'>";
			    break;
		    default:
			    $app->enqueueMessage("There seems to have been an error, Please try again", "error");
			    break;
	    }
	    //     var_dump($operation);
        //     var_dump($selected);
    }

}
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
    echo "<a href='$linkStr'>Add New Problem</a>";
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
	<button  id="filter_clear" class="submit-button" type="button" onclick="window.location.reload();"> Reset </button>
	<button class = "submit-button" type="submit">Filter</button>
</div>
   </div>
</form>

<form action="index.php?option=com_catalogsystem&view=catalogc"
    method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
              <th id="checkcolumn">
                  <input id="toggle" class="checkcolumn" type="checkbox"  name="toggle" label=" " onclick="toggleAll()">
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
        <button class = "op-button" type="submit">Confirm</button>
      </div>
    </div>

</form>
