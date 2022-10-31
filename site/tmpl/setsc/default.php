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

require_once dirname(__FILE__).'\..\functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('catalog')
    ->useScript('catalogHelper');
//JHTML::script(Juri::base() . '/media/com_catalogsystem/js/categories.js');
$urlStr = "index.php?option=com_catalogsystem&view=catalogc&set=";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Retrieve POST input and file uploads
	$app  = Factory::getApplication();
	$postData = $app->input->post->get('jform2', array(), "array");
	if($postData)
	{
		$operation = $postData['op'];
		unset($postData['op']);
		$selected = array_keys($postData);
		$db    = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);
//        var_dump($operation);
		switch ($operation['desiredOp']){
            case 0: // Record Use
                if($selected){
                    if(!$operation['useDate']){
	                    $app->enqueueMessage("Please select a date", "error");
                        break;
                    }

	            foreach ($selected as $SetID){ // Each set
		            $query->clear();
                    $query->select($db->quoteName(array('problem_id', 'set_id', 'id')));
                    $query->from($db->quoteName('com_catalogsystem_problemset'));
                    $query->where($db->quoteName('set_id') . ' = ' . $db->quote($SetID));
		            $db->setQuery($query);
		            $problems = $db->loadAssocList();
//                    echo "SET ID: " . $SetID;
//                    var_dump($problems);

	                    foreach($problems as $problem)
	                    { // Record Use for each problem
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
                            
		                    $values  = array(sqlInt($problem['problem_id']), $teamID, sqlDate($operation['useDate']));
		                    $query->insert('com_catalogsystem_history')
			                    ->columns($db->quoteName($columns))
			                    ->values(implode(',', $values));
		                    $db->setQuery($query);
		                    $db->execute();
	                    }
                }
                } else {
	                $app->enqueueMessage("Please select at least one set", "error");
                    break;
                }
	            $app->enqueueMessage("Usage record updated successfully");
                break;
            case 2: // Delete
                if($selected)
                {
	                foreach ($selected as $SetID)
	                {
		                $query->clear();
		                $query->delete($db->quoteName('com_catalogsystem_set'));
		                $query->where($db->quoteName('id') . ' = ' . $db->quote($SetID));
		                $db->setQuery($query);
		                if ($db->execute() != 1)
		                {
			                $query->clear();
			                $query->select($db->quoteName(array('id', 'name')));
			                $query->from($db->quoteName('com_catalogsystem_set'));
			                $query->where($db->quoteName('id') . ' = ' . $db->quoteName($SetID));
			                $db->setQuery($query);
			                $setName = $db->loadResult();
			                $app->enqueueMessage("There was an error deleting set $setName}", "error");
			                $query->clear();
		                }
	                }
                } else {
	                $app->enqueueMessage("Please select at least one set", "error");
	                break;
                }
	            echo "<meta http-equiv='refresh' content='0'>";
	            break;
        }
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

<form class= "search-box" action="index.php?option=com_catalogsystem&view=setsc"
    method="post" name="setsForm" id="setsForm" enctype="multipart/form-data">
	<div>
		<?php echo $this->form->renderField('sets_name');  ?>
	</div>
	<div style = "display: flex; flex-wrap: wrap;">
        <?php echo $this->form->renderField('sets_date_after');  ?>
        <?php echo $this->form->renderField('sets_date_before');  ?>
    </div>
	<div style = "display: flex; flex-wrap: wrap;">
        <?php echo $this->form->renderField('sets_date_notbefore');  ?>
        <?php echo $this->form->renderField('sets_date_notafter');  ?>
    </div>

  <div class= "end-content">
  <button  id="filter_clear" class="submit-button" style="background-color: red"  type="button" onclick="window.location.reload();"> Reset </button>
     <button class = "submit-button" type="submit">Filter</button>
   </div>
</form>

<form action="index.php?option=com_catalogsystem&view=setsc"
    method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
              <th id="checkcolumn">
                  <input id="toggle" class="checkcolumn" type="checkbox"  name="toggle" label=" " onclick="toggleAll()">
              </th>
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
                    <?php
                        $xmlStr = '<field name="' . $row->set_id . '" type="checkbox" label=" "/>';
                        $xml = new SimpleXMLElement($xmlStr);
                        $this->form2->setField($xml);
                    ?>
                    <td><?php echo $this->form2->renderField("$row->set_id");  ?></td>
                    <td><?php $url = Route::_($urlStr . $row->set_id); echo "<a href='$url'>$row->name</a>";?></td>
                    <td><?php echo $row->numProblems; ?></td>
                    <td><?php echo "<a href='$row->zip'>Download</a>"; ?></td>
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
    
    <div class="search-box">
        <?php echo $this->form2->renderFieldset("opPanel"); ?>
        <button type="submit">Confirm</button>
    </div>

</form>
