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
class AddProblem_WriteModel extends ItemModel
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
		
		$result = new \stdClass();
		$result->msg = 'Unknown';
		$result->state = 1;
		
		if($_SERVER['REQUEST_METHOD'] !== 'POST')
		{
			$result->msg = 'No POST request';
			$result->state = 2;
		}
		else 
		{
			$result->msg = 'Problem added successfully';
			$result->state = 0;
			
			// Retrieve POST input and file uploads
			$app  = Factory::getApplication();
			$postData = $app->input->post->get('jform', array(), "array");
			$file = $app->input->files->get('jform', array(), "array");
		
			// Debug POST input
			if($localDebug) {
				echo '<br/> Post input:<br/>';
				var_dump($postData);
				echo '<br/>';
			}
		
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
			$inputFirstUse = arrGet($postData,'firstUse');
			$inputTeam = arrGet($postData,'firstUseTeam');
			$inputSets = arrGet($postData,'set');
			$inputPdf = $pdfFilename;
			$inputZip = $zipFilename;
			
			$inputCatToggle = arrGet($postData,'cattoggle');
			$inputCategory = arrGet($postData,'category');
			if(is_array($inputCategory)) $inputCategory = arrGet($inputCategory,0);
			$inputNewCategory = arrGet($postData,'newcategory');
			
			$inputSourceToggle = arrGet($postData,'sourcetoggle');
			$inputSource = arrGet($postData,'source');
			if(is_array($inputSource)) $inputSource = arrGet($inputSource,0);
			$inputNewSource = arrGet($postData,'newsource');
			
		
		
		
			// QUERY: Get the next id in the problem table
			$q_ProblemMaxId = $db->getQuery(true);
			$q_ProblemMaxId->select('MAX(p.id) AS "maxId"')
				->from('com_catalogsystem_problem AS p');
			$db->setQuery($q_ProblemMaxId);
			$r_ProblemMaxId = $db->loadObject();
			$newProbId = sqlInt(objGet($r_ProblemMaxId,'maxId'),0) + 1;
		
			// If an existing category name was provided, attach the new problem to that category
			$newProbCid = 'NULL';
			if($inputCatToggle == '0' && sqlString($inputCategory)!=='NULL')
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
			// If a new category name was provided, create the new category
			if($inputCatToggle == '1' && sqlString($inputNewCategory)!=='NULL')
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
					. ', ' . sqlString($inputNewCategory)
					);
				$db->setQuery($q_InsertCategory);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertCategory->__toString();
			}
		
			// If an existing source name was provided, attach the new problem to that source
			$newProbSid = 'NULL';
			if($inputSourceToggle == '0' && sqlString($inputSource)!=='NULL')
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
			}
			// If a new source name was provided, create the new source
			if($inputSourceToggle == '1' && sqlString($inputNewSource)!=='NULL')
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
					. ', ' . sqlString($inputNewSource)
					);
				$db->setQuery($q_InsertSource);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertSource->__toString();
			}
		
			// QUERY: insert the new problem into the problem table
			$q_InsertProblem = $db->getQuery(true);
			$q_InsertProblem->insert('com_catalogsystem_problem')
				->values(sqlInt($newProbId)
				. ', ' . sqlInt($newProbSid)
				. ', ' . sqlInt($newProbCid)
				. ', ' . sqlString($inputName)
				. ', ' . sqlInt($inputDifficulty)
				. ', ' . sqlString($inputPdf)
				. ', ' . sqlString($inputZip)
				);
			$db->setQuery($q_InsertProblem);
			$db->execute();
			if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertProblem->__toString();
		
			// If a valid date was provided, create the history entry
			if(sqlDate($inputFirstUse)!=='NULL')
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
					. ', ' . sqlInt($newProbId)
					. ', ' . sqlInt($newHistTid)
					. ', ' . sqlDate($inputFirstUse)
					);
				$db->setQuery($q_InsertHistory);
				$db->execute();
				if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertHistory->__toString();
		
			}
		
			// If a valid list of sets was provided, create a problemset relationship for each set
			if(is_array($inputSets))
			{
				foreach($inputSets as $inputSet)
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
							. ', ' . sqlInt($newProbId)
							);
						$db->setQuery($q_InsertProblemSet);
						$db->execute();
						if($localDebug) echo '<br/><br/>Exeucted SQL Query:<br/>' . $q_InsertProblemSet->__toString();
					}
				}
			}
		
		}//End of 'if(POST)' section
        
		return $result;
    }
}
