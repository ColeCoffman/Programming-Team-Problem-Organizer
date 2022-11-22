<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Text;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;

require_once dirname(__FILE__).'/../../tmpl/functionLib.php';

class CatalogOp_WriteModel extends ItemModel
{
    // Overrides ItemModel, the View file calls this function to preform an operation
	// This specific function is used in the catalogc page to preform operations on a group of problems
    public function getItem($pk= null)
    {
		// If true, debug will be echoed to the webpage
		$localDebug = false;
		
		$result = new \stdClass();
		$result->msg = 'Unknown';
		$result->state = 1;
		
		// Retrieve POST data
		$postData = NULL;
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$app  = Factory::getApplication();
			$postData = $app->input->post->get('jform2', array(), "array");
			
		}
		
		// If there is no POST data, do nothing
		if($postData == NULL)
		{
			$result->msg = 'No POST request';
			$result->state = 2;
		}
		// If there is POST data, preform the selected operation
		else
		{
			$result->msg = 'No operation preformed';
			$result->state = 3;
			
			// Read the fieldset
			$operation = $postData['op'];
			unset($postData['op']);
			// Read the problem ids of the checkboxes that are selected
			$selected = array_keys($postData);
	
			$db    = Factory::getContainer()->get('DatabaseDriver');
			$query = $db->getQuery(true);
			
			// Determine what operation to perform
			switch ($operation['desiredOp'])
			{
				case 0: // Record Use
					foreach ($selected as $id)
					{ // ID = Problem ID
						if(sqlInt($id)==='NULL') continue;
						
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
					// After the database has been changed, refresh the page.
					// This forces Joomla to reload its ListModels, since it only allows the SQL to run once
					echo "<meta http-equiv='refresh' content='0'>";
					break;
				case 1: // Add to Set
					$error = false;
					foreach ($selected as $id) // problem id
					{
						if(sqlInt($id)==='NULL') continue;
						
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
					// After the database has been changed, refresh the page.
					// This forces Joomla to reload its ListModels, since it only allows the SQL to run once
					echo "<meta http-equiv='refresh' content='0'>";
					break;
				case 2: // Delete
					foreach ($selected as $id) // problem id
					{
						if(sqlInt($id)==='NULL') continue;
						
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
					// After the database has been changed, refresh the page.
					// This forces Joomla to reload its ListModels, since it only allows the SQL to run once
					echo "<meta http-equiv='refresh' content='0'>";
					break;
				default:
					$app->enqueueMessage("There seems to have been an error, Please try again", "error");
					break;
			}
		}
		
		
        // If the database attempted to run, display the resulting message
		if($result->state <= 0)
		{
			// If there was an error, display the app message as an error
			if($result->state<0)
			{
				$app->enqueueMessage($result->msg, "error");
			}
			else
			{
				$app->enqueueMessage($result->msg, "success");
			}
		}
		
		return $result;
    }
}
