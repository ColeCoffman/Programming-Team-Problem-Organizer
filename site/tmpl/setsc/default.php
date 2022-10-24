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

require_once __DIR__ . '\\..\\functionLib.php';

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
		                    $columns = array('problem_id', 'date');
		                    $values  = array(sqlInt($problem['problem_id']), sqlDate($operation['useDate']));
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
    method="post" name="opForm" id="opForm" enctype="multipart/form-data">
    <table class="catalog_table" id="myTable">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="toggle" name="toggle" label=" " onclick="toggleAll()">
                </th>
                <th onclick="sortTable(1)">Name ↕</th>
                <th onclick="sortTable(2)">Number of Problems ↕</th>
                <th onclick="sortTable(3)">Zip URL ↕</th>
				<th onclick="sortTable(4)">First Used ↕</th>
				<th onclick="sortTable(5)">Last Used ↕</th>
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
					<td><?php echo $row->firstUsed; ?></td>
					<td><?php echo $row->lastUsed; ?></td>
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
