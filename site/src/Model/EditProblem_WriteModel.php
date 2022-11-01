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
class EditProblem_WriteModel extends ItemModel
{
    /**
     * 
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    public function getItem($pk= null)
    {
		// If true, debug will be echoed to the webpage
		$localDebug = true;
		// If true, the edit page will allow NULL inputs
		// (ex1: leaving 'Category' blank will delete the existing category and replace it with NULL)
		// (ex2: leaving 'Name' blank will delete the existing name and replace it with an empty string)
		$acceptNullInputs = true;
		
		$result = new \stdClass();
		$result->msg = 'Unknown';
		$result->state = 1;
		
		$info = $this->getState("details");
		if($localDebug)
		{
			echo '<br/>Recieved state "details" with info:<br/>';
			var_dump($info);
			echo '<br/>----------<br/>';
		}
		
		if($_SERVER['REQUEST_METHOD'] !== 'POST')
		{
			$result->msg = 'No POST request';
			$result->state = 2;
		}
		else if(is_null($info))
		{
			$result->msg = 'FAILED: getState("details") was NULL';
			$result->state = -1;
		}
		else
		{
			$result->msg = 'Problem updated successfully';
			$result->state = 0;
			
			// Retrieve POST input and file uploads
			$app  = Factory::getApplication();
			$postData = $app->input->post->get('jform', array(), "array");
			$file = $app->input->files->get('jform', array(), "array");
			
			// Process PDF upload
			$pdfFilename = NULL;
			if ($file['pdfupload']['size'] > 0) {
				//         Clean Filename
				$pdfFilename = File::makeSafe($file['pdfupload']['name']);
				// Setup the source and destination of the File
				$src         = $file['pdfupload']['tmp_name'];
				$dest        = JPATH_ROOT . "/media/com_catalogsystem/uploads/pdf/" . $pdfFilename;
				// Verify file type
				if (strtolower(File::getExt($pdfFilename)) != "pdf") {
					$app->enqueueMessage("File must be a PDF", "error");
				} else if (File::upload($src, $dest)) {
					$app->enqueueMessage("File uploaded successfully", "success");
				} else {
					$app->enqueueMessage("File upload failed", "error");
				}
			}
			
			// Process ZIP Upload
			$zipFilename = NULL;
			if ($file['zipupload']['size'] > 0) {
				//         Clean Filename
				$zipFilename = File::makeSafe($file['zipupload']['name']);
				// Setup the source and destination of the File
				$src         = $file['zipupload']['tmp_name'];
				$dest        = JPATH_ROOT . "/media/com_catalogsystem/uploads/zip/" . $zipFilename;
				// Verify file type
				if (strtolower(File::getExt($zipFilename)) != "zip") {
					$app->enqueueMessage("File must be a zip", "error");
				} else if (File::upload($src, $dest)) {
					$app->enqueueMessage("File uploaded successfully", "success");
				} else {
					$app->enqueueMessage("File upload failed", "error");
				}
			}
			
			// Get a DatabaseDriver object.
			$db = Factory::getContainer()->get('DatabaseDriver');
			
			// Organize user input
			$inputName = arrGet($postData,'name');
			$inputDifficulty = arrGet($postData,'dif');
			$inputPdf = $pdfFilename;
			$inputZip = $zipFilename;
			$inputAddUse = arrGet($postData,'add_use');
			$inputTeam = arrGet($postData,'useTeam');
			$inputAddSets = arrGet($postData,'add_sets');
			
			$inputCatToggle = arrGet($postData,'cattoggle');
			$inputCategory = arrGet($postData,'category');
			if($inputCatToggle == '1') $inputCategory = arrGet($postData,'newcategory');
			if(is_array($inputCategory)) $inputCategory = arrGet($inputCategory,0);
			
			
			$inputSourceToggle = arrGet($postData,'sourcetoggle');
			$inputSource = arrGet($postData,'source');
			if($inputSourceToggle == '1') $inputSource = arrGet($postData,'newsource');
			if(is_array($inputSource)) $inputSource = arrGet($inputSource,0);
			
			// Retrieve user input for history and set deletions (auto-generated fields)
			$inputDelUses = array();
			foreach ($info->history as $i => $row)
			{
				$inputDelUse = arrGet($postData,"delUse_$row->id");
				if($inputDelUse === '1') array_push($inputDelUses,$row->id);
			}
			$inputDelSets = array();
			foreach ($info->sets as $i => $row)
			{
				$inputDelSet = arrGet($postData,"delSet_$row->id");
				if($inputDelSet === '1') array_push($inputDelSets,$row->id);
			}
			
			
			// If a different name was provided, change the name of this problem
			if(sqlString($inputName) !== 'NULL' ? $inputName !== $info->name : ($info->name !== NULL && $acceptNullInputs))
			{
				// QUERY: Update p.name
				$q_UpdateProblemName = $db->getQuery(true);
				$q_UpdateProblemName->update('com_catalogsystem_problem AS p')
					->set('p.name = ' . sqlString($inputName))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemName);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_UpdateProblemName->__toString();
			}
			
			// If a different difficulty was provided, change the difficulty of this problem
			// (NOTE: This is comparing a string to an integer, so the '!=' is essential)
			if(sqlInt($inputDifficulty) !== 'NULL' ? $inputDifficulty != $info->difficulty : ($info->difficulty !== NULL && $acceptNullInputs))
			{
				// QUERY: Update p.difficulty
				$q_UpdateProblemDifficulty = $db->getQuery(true);
				$q_UpdateProblemDifficulty->update('com_catalogsystem_problem AS p')
					->set('p.difficulty = ' . sqlInt($inputDifficulty))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemDifficulty);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_UpdateProblemDifficulty->__toString();
			}
			
			// If a different pdf_link was provided, change the pdf_link of this problem
			if(sqlString($inputPdf) !== 'NULL' && $inputPdf !== $info->pdfPath)
			{
				// QUERY: Update p.pdf_link
				$q_UpdateProblemPdf = $db->getQuery(true);
				$q_UpdateProblemPdf->update('com_catalogsystem_problem AS p')
					->set('p.pdf_link = ' . sqlString($inputPdf))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemPdf);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_UpdateProblemPdf->__toString();
			}
			
			// If a different zip_link was provided, change the zip_link of this problem
			if(sqlString($inputZip) !== 'NULL' && $inputZip !== $info->zipUrl)
			{
				// QUERY: Update p.zip_link
				$q_UpdateProblemZip = $db->getQuery(true);
				$q_UpdateProblemZip->update('com_catalogsystem_problem AS p')
					->set('p.zip_link = ' . sqlString($inputZip))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemZip);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_UpdateProblemZip->__toString();
			}
			
			// If a different category was provided, attach this problem to that category
			$newProbCid = 'NULL';
			if(sqlString($inputCategory)!=='NULL' ? $inputCategory !== $info->category : ($info->category !== NULL && $acceptNullInputs))
			{
				if($inputCatToggle == '0')
				{
					// QUERY: Get the category id that matches the given category name
					$q_CategoryName = $db->getQuery(true);
					$q_CategoryName->select('c.id AS "cid"')
						->from('com_catalogsystem_category AS c')
						->where('c.name = ' . sqlString($inputCategory));
					$db->setQuery($q_CategoryName);
					$r_CategoryName = $db->loadObject();
					$newProbCid = sqlInt(objGet($r_CategoryName,'cid'),0);
				}
				if($inputCatToggle == '1')
				{
					// QUERY: Get the next id in the category table
					$q_CategoryMaxId = $db->getQuery(true);
					$q_CategoryMaxId->select('MAX(c.id) AS "maxId"')
						->from('com_catalogsystem_category AS c');
					$db->setQuery($q_CategoryMaxId);
					$r_CategoryMaxId = $db->loadObject();
					$newProbCid = sqlInt(objGet($r_CategoryMaxId,'maxId'),0) + 1;
					
					// QUERY: insert the new category entry into the category table
					$q_InsertCategory = $db->getQuery(true);
					$q_InsertCategory->insert('com_catalogsystem_category')
						->values(sqlInt($newProbCid)
						. ', ' . sqlString($inputCategory)
						);
					$db->setQuery($q_InsertCategory);
					$db->execute();
					if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertCategory->__toString();
				}
				
				// QUERY: Update p.category_id
				$q_UpdateProblemCategory = $db->getQuery(true);
				$q_UpdateProblemCategory->update('com_catalogsystem_problem AS p')
					->set('p.category_id = ' . sqlInt($newProbCid))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemCategory);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_UpdateProblemCategory->__toString();
			}
			
			// If a different source was provided, attach this problem to that source
			$newProbSid = 'NULL';
			if(sqlString($inputSource)!=='NULL' ? $inputSource !== $info->source : ($info->source !== NULL && $acceptNullInputs))
			{
				if($inputSourceToggle == '0')
				{
					// QUERY: Get the source id that matches the given source name
					$q_SourceName = $db->getQuery(true);
					$q_SourceName->select('s.id AS "sid"')
						->from('com_catalogsystem_source AS s')
						->where('s.name = ' . sqlString($inputSource));
					$db->setQuery($q_SourceName);
					$r_SourceName = $db->loadObject();
					$newProbSid = sqlInt(objGet($r_SourceName,'sid'),0);
				}
				if($inputSourceToggle == '1')
				{
					// QUERY: Get the next id in the source table
					$q_SourceMaxId = $db->getQuery(true);
					$q_SourceMaxId->select('MAX(s.id) AS "maxId"')
						->from('com_catalogsystem_source AS s');
					$db->setQuery($q_SourceMaxId);
					$r_SourceMaxId = $db->loadObject();
					$newProbSid = sqlInt(objGet($r_SourceMaxId,'maxId'),0) + 1;
					
					// QUERY: insert the new source entry into the source table
					$q_InsertSource = $db->getQuery(true);
					$q_InsertSource->insert('com_catalogsystem_source')
						->values(sqlInt($newProbSid)
						. ', ' . sqlString($inputSource)
						);
					$db->setQuery($q_InsertSource);
					$db->execute();
					if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertSource->__toString();
				}
				
				// QUERY: Update p.source_id
				$q_UpdateProblemSource = $db->getQuery(true);
				$q_UpdateProblemSource->update('com_catalogsystem_problem AS p')
					->set('p.source_id = ' . sqlInt($newProbSid))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemSource);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_UpdateProblemSource->__toString();
			}
			
			// If a valid date was provided, create the history entry
			if(sqlDate($inputAddUse)!=='NULL')
			{
				
				// QUERY: Get the next id in the history table
				$q_HistoryMaxId = $db->getQuery(true);
				$q_HistoryMaxId->select('MAX(h.id) AS "maxId"')
					->from('com_catalogsystem_history AS h');
				$db->setQuery($q_HistoryMaxId);
				$r_HistoryMaxId = $db->loadObject();
				$newHistId = sqlInt(objGet($r_HistoryMaxId,'maxId'),0) + 1;
				
				$newHistTid = 'NULL';
				if(sqlString($inputTeam)!=='NULL')
				{
					// QUERY: Get the team id that matches the given team name
					$q_TeamName = $db->getQuery(true);
					$q_TeamName->select('t.id AS "tid"')
						->from('com_catalogsystem_team AS t')
						->where('t.name = ' . sqlString($inputTeam));
					$db->setQuery($q_TeamName);
					$r_TeamName = $db->loadObject();
					$newHistTid = sqlInt(objGet($r_TeamName,'tid'),0);
				}
				
				// QUERY: insert the new history entry into the history table
				$q_InsertHistory = $db->getQuery(true);
				$q_InsertHistory->insert('com_catalogsystem_history')
					->values(sqlInt($newHistId)
					. ', ' . sqlInt($info->id)
					. ', ' . sqlInt($newHistTid)
					. ', ' . sqlDate($inputAddUse)
					);
				$db->setQuery($q_InsertHistory);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertHistory->__toString();
			}
			
			// If a valid list of sets was provided, create a problemset relationship for each set
			if(is_array($inputAddSets))
			{
				foreach($inputAddSets as $inputSet)
				{
					if(sqlString($inputSet)!=='NULL')
					{
						// QUERY: Get the next id in the problemset table
						$q_ProblemSetMaxId = $db->getQuery(true);
						$q_ProblemSetMaxId->select('MAX(ps.id) AS "maxId"')
							->from('com_catalogsystem_problemset AS ps');
						$db->setQuery($q_ProblemSetMaxId);
						$r_ProblemSetMaxId = $db->loadObject();
						$newProbSetId = sqlInt(objGet($r_ProblemSetMaxId,'maxId'),0) + 1;
						
						// QUERY: Get the set id that matches the given set name
						$q_SetName = $db->getQuery(true);
						$q_SetName->select('e.id AS "eid"')
							->from('com_catalogsystem_set AS e')
							->where('e.name = ' . sqlString($inputSet));
						$db->setQuery($q_SetName);
						$r_SetName = $db->loadObject();
						$newProbSetEid = sqlInt(objGet($r_SetName,'eid'),0);
						
						if($newProbSetEid === 'NULL')
						{
							// TODO: Create a new set
							echo '<br/><br/> CREATE NEW SET <br/> name = ' . sqlString($inputSet) . '<br/>';
						}
						
						// QUERY: insert the new relationship into the problemset table
						$q_InsertProblemSet = $db->getQuery(true);
						$q_InsertProblemSet->insert('com_catalogsystem_problemset')
							->values(sqlInt($newProbSetId)
							. ', ' . sqlInt($newProbSetEid)
							. ', ' . sqlInt($info->id)
							);
						$db->setQuery($q_InsertProblemSet);
						$db->execute();
						if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertProblemSet->__toString();
					}
				}
			}
			
			// Delete any history uses that were marked for deletion
			if(count($inputDelUses)>0)
			{
				$delUsageWhere = "1=0";
				foreach($inputDelUses as $delHid)
				{
					$delUsageWhere .= " OR id = $delHid";
				}
				
				$q_DeleteUsage = $db->getQuery(true);
				$q_DeleteUsage->delete('com_catalogsystem_history')
					->where($delUsageWhere);
				$db->setQuery($q_DeleteUsage);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_DeleteUsage->__toString();
			}
			
			// Delete any set relationships that were marked for deletion
			if(count($inputDelSets)>0)
			{
				$delSetWhere = "1=0";
				foreach($inputDelSets as $delEid)
				{
					$delSetWhere .= " OR (problem_id = $info->id AND set_id = $delEid)";
				}
				
				$q_DeleteSet = $db->getQuery(true);
				$q_DeleteSet->delete('com_catalogsystem_problemset')
					->where($delSetWhere);
				$db->setQuery($q_DeleteSet);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_DeleteSet->__toString();
			}
			
			
			
		}//End of 'if(POST)' section
        
		return $result;
    }
}
