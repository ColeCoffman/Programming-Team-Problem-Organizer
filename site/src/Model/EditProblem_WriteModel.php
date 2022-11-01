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
		$result->state = 0;
		
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
			$result->state = 1;
		}
		else if(is_null($info))
		{
			$result->msg = 'FAILED: getState("details") was NULL';
			$result->state = 2;
		}
		else
		{
			$result->msg = 'Database edited successfully';
			$result->state = 3;
			
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
			$inputCategory = arrGet($postData,'category');
			if(is_array($inputCategory)) $inputCategory = arrGet($inputCategory,0);
			$inputSource = arrGet($postData,'source');
			if(is_array($inputSource)) $inputSource = arrGet($inputSource,0);
			$inputAddUse = arrGet($postData,'add_use');
			$inputAddSets = arrGet($postData,'add_sets');
			// Retrieve user input for history and set deletions (auto-generated fields)
			$inputDelUses = array();
			foreach ($info->history as $i => $row)
			{
				$inputDelUse = arrGet($postData,"delUse_$row->id");
				echo "<br/>Reading delUse_$row->id as '$inputDelUse'";
				if($inputDelUse === '1') array_push($inputDelUses,$row->id);
			}
			echo "<br/>Del uses array:".print_r($inputDelUses, true);
			$inputDelSets = array();
			foreach ($info->sets as $i => $row)
			{
				$inputDelSet = arrGet($postData,"delSet_$row->id");
				echo "<br/>Reading delSet_$row->id as '$inputDelSet'";
				if($inputDelSet === '1') array_push($inputDelSets,$row->id);
			}
			echo "<br/>Del sets array:".print_r($inputDelSets, true);
			
			
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
				if($localDebug) echo '<br/> - Changing name to ' . sqlString($inputName);
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
				if($localDebug) echo '<br/> - Changing difficulty to ' . sqlInt($inputDifficulty);
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
				if($localDebug) echo '<br/> - Changing pdf_link to ' . sqlString($inputPdf);
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
				if($localDebug) echo '<br/> - Changing zip_link to ' . sqlString($inputZip);
			}
			
			// If a different category was provided, attach this problem to that category
			$newProbCid = 'NULL';
			if(sqlString($inputCategory)!=='NULL' ? $inputCategory !== $info->category : ($info->category !== NULL && $acceptNullInputs))
			{
				// QUERY: Get the category id that matches the given category name
				$q_CategoryName = $db->getQuery(true);
				$q_CategoryName->select('c.id AS "cid"')
					->from('com_catalogsystem_category AS c')
					->where('c.name = ' . sqlString($inputCategory));
				$db->setQuery($q_CategoryName);
				$r_CategoryName = $db->loadObject();
				$newProbCid = sqlInt(objGet($r_CategoryName,'cid'),0);
				
				// If there is no cateogry with a matching name, create one
				if($newProbCid === 'NULL')
				{
					// TODO: Create a new category
					echo '<br/><br/> CREATE NEW CATEGORY <br/> name = ' . sqlString($inputCategory) . '<br/>';
				}
				
				// QUERY: Update p.category_id
				$q_UpdateProblemCategory = $db->getQuery(true);
				$q_UpdateProblemCategory->update('com_catalogsystem_problem AS p')
					->set('p.category_id = ' . sqlInt($newProbCid))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemCategory);
				$db->execute();
				if($localDebug) echo '<br/> - Changing category_id to' . sqlInt($newProbCid);
			}
			
			// If a different source was provided, attach this problem to that source
			$newProbSid = 'NULL';
			if(sqlString($inputSource)!=='NULL' ? $inputSource !== $info->source : ($info->source !== NULL && $acceptNullInputs))
			{
				// QUERY: Get the source id that matches the given source name
				$q_SourceName = $db->getQuery(true);
				$q_SourceName->select('s.id AS "sid"')
					->from('com_catalogsystem_source AS s')
					->where('s.name = ' . sqlString($inputSource));
				$db->setQuery($q_SourceName);
				$r_SourceName = $db->loadObject();
				$newProbSid = sqlInt(objGet($r_SourceName,'sid'),0);
				
				// If there is no source with a matching name, create one
				if($newProbSid === 'NULL')
				{
					// TODO: Create a new source
					echo '<br/><br/> CREATE NEW SOURCE <br/> name = ' . sqlString($inputSource) . '<br/>';
				}
				
				// QUERY: Update p.source_id
				$q_UpdateProblemSource = $db->getQuery(true);
				$q_UpdateProblemSource->update('com_catalogsystem_problem AS p')
					->set('p.source_id = ' . sqlInt($newProbSid))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemSource);
				$db->execute();
				if($localDebug) echo '<br/> - Changing source_id to' . sqlInt($newProbSid);
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
				
				// QUERY: insert the new history entry into the history table
				$q_InsertHistory = $db->getQuery(true);
				$q_InsertHistory->insert('com_catalogsystem_history')
					->values(sqlInt($newHistId)
					. ', ' . sqlInt($info->id)
					. ', ' . 'NULL'
					. ', ' . sqlDate($inputAddUse)
					);
				$db->setQuery($q_InsertHistory);
				$db->execute();
				if($localDebug) echo '<br/> - Adding history entry with id='.sqlInt($newHistId).' and date=' . sqlDate($inputAddUse);
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
						if($localDebug) echo '<br/> - Added problem '.sqlString($inputName).' to set '.sqlString($inputSet);
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
				if($localDebug) echo "<br/> - Deleting history uses: ".print_r($inputDelUses, true);
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
				if($localDebug) echo "<br/> - Deleting problemset rels with problem_id $info->id and set_id: ".print_r($inputDelSets, true);
			}
			
			
			
		}//End of 'if(POST)' section
        
		return $result;
    }
}
