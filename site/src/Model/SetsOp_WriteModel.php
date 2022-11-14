<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Text;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;

require_once dirname(__FILE__).'/../../tmpl/functionLib.php';

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * Catalog System Message Model
 * @since 0.0.5
 */
class SetsOp_WriteModel extends ItemModel
{
    /**
     * 
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    public function getItem($pk= null)
    {
		// If true, debug will be echoed to the webpage
		$localDebug = false;
		// If true, the edit page will allow NULL inputs
		// (ex1: leaving 'Category' blank will delete the existing category and replace it with NULL)
		// (ex2: leaving 'Name' blank will delete the existing name and replace it with an empty string)
		$acceptNullInputs = true;
		
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
		
		if($postData == NULL)
		{
			$result->msg = 'No POST request';
			$result->state = 2;
		}
		else
		{
			$result->msg = 'No operation preformed';
			$result->state = 3;
			
			$operation = $postData['op'];
			unset($postData['op']);
			$selected = array_keys($postData);
			$db    = Factory::getContainer()->get('DatabaseDriver');
			$query = $db->getQuery(true);
			switch ($operation['desiredOp']){
				case 0: // Record Use
					if($selected){
						if(!$operation['useDate']){
							break;
						}
	
						foreach ($selected as $SetID){ // Each set
							$query->clear();
							$query->select($db->quoteName(array('problem_id', 'set_id', 'id')));
							$query->from($db->quoteName('com_catalogsystem_problemset'));
							$query->where($db->quoteName('set_id') . ' = ' . $db->quote($SetID));
							$db->setQuery($query);
							$problems = $db->loadAssocList();
		
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
						break;
					}
					$result->msg = 'Usage record updated successfully';
					$result->state = 0;
					break;
					
					
				case 1: // Create new set
					
					// Get POST inputs
					$inputSet = arrGet($operation,'newSet');
					$inputZip = arrGet($operation,'newZip');
					
					// If a name was given, try to create the new set	
					if(sqlString($inputSet)!=='NULL')
					{
						// QUERY: Check if the given name is already taken by another set
						$q_SetCheckName = $db->getQuery(true);
						$q_SetCheckName->select('MAX(e.id) AS "maxId"')
							->from('com_catalogsystem_set AS e')
							->where('e.name=' . sqlString($inputSet));
						$db->setQuery($q_SetCheckName);
						$r_SetCheckName = $db->loadObject();
						
						// If the set name is already taken, report the error and abort
						if(sqlInt(objGet($r_SetCheckName,'maxId'),0)!='NULL')
						{
							$result->msg = "Could not create set '$inputSet' because the name is already taken";
							$result->state = -1;
						}
						else
						{
							// QUERY: Get the next id in the set table
							$q_SetMaxId = $db->getQuery(true);
							$q_SetMaxId->select('MAX(e.id) AS "maxId"')
								->from('com_catalogsystem_set AS e');
							$db->setQuery($q_SetMaxId);
							$r_SetMaxId = $db->loadObject();
							$newSetEid = sqlInt(objGet($r_SetMaxId,'maxId'),0,NULL,0) + 1;
							
							// QUERY: insert the new set entry into the set table
							$q_InsertSet = $db->getQuery(true);
							$q_InsertSet->insert('com_catalogsystem_set')
								->values(sqlInt($newSetEid)
								. ', ' . sqlString($inputSet)
								. ', ' . sqlString($inputZip)
								);
							$db->setQuery($q_InsertSet);
							$db->execute();
							if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertSet->__toString();
							
							$result->msg = 'New set created successfully';
							$result->state = 0;
						}
					}
					
					
				case 2: // Delete
					if($selected)
					{
						$numDeleted = 0;
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
							else {
								$numDeleted += 1;
							}
						}
						if($numDeleted>0)
						{
							$result->msg = "Successfully deleted $numDeleted sets";
							$result->state = 0;
						}
					} else {
						break;
					}
					echo "<meta http-equiv='refresh' content='0'>";
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
		}//*/
		
		return $result;
    }
}
