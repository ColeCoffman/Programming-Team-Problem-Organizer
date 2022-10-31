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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Router\Route;

require_once dirname(__FILE__).'/../functionLib.php';

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper');

use Joomla\CMS\Uri\Uri;
$uri = Uri::root();
$pdfExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/pdf/'.$this->item->pdf_link. '.pdf');
$zipExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/zip/'.$this->item->zip_link. '.zip');
?>

<?php 
    if (is_null($this->item)){
        echo "<h2>Error: Problem does not exist</h2>";
        echo "<h3>Include a valid id in the URL to edit problem.</h3>";
    }else{
		
		// ------------
		// EDIT PROBLEM
		// ------------
		
		$info = $this->item;
		
		$localDebug = false;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
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
			$inputPdf = $pdfFilename;
			$inputZip = $zipFilename;
			$inputCategory = arrGet($postData,'category');
			if(is_array($inputCategory)) $inputCategory = arrGet($inputCategory,0);
			$inputSource = arrGet($postData,'source');
			if(is_array($inputSource)) $inputSource = arrGet($inputSource,0);
			$inputAddUse = arrGet($postData,'add_use');
            $inputUseTeam = arrGet($postData,'useTeam');
			$inputAddSets = arrGet($postData,'add_sets');
			
			
			// If a different name was provided, change the name of this problem
			if(sqlString($inputName) !== 'NULL' && $inputName !== $info->name)
			{
				// QUERY: Update p.name
				$q_UpdateProblemName = $db->getQuery(true);
				$q_UpdateProblemName->update('com_catalogsystem_problem AS p')
					->set('p.name = ' . sqlString($inputName))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemName);
				$db->execute();
				if($localDebug) echo '<br/> Changing name to' . sqlString($inputName);
			}
			
			// If a different difficulty was provided, change the difficulty of this problem
			// (NOTE: This is comparing a string to an integer, so the '!=' is essential)
			if(sqlInt($inputDifficulty) !== 'NULL' && $inputDifficulty != $info->difficulty)
			{
				// QUERY: Update p.difficulty
				$q_UpdateProblemDifficulty = $db->getQuery(true);
				$q_UpdateProblemDifficulty->update('com_catalogsystem_problem AS p')
					->set('p.difficulty = ' . sqlInt($inputDifficulty))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemDifficulty);
				$db->execute();
				if($localDebug) echo '<br/> Changing difficulty to' . sqlInt($inputDifficulty);
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
				if($localDebug) echo '<br/> Changing pdf_link to' . sqlString($inputPdf);
			}
			
			// If a different zip_link was provided, change the pdf_link of this problem
			if(sqlString($inputZip) !== 'NULL' && $inputZip !== $info->zipUrl)
			{
				// QUERY: Update p.zip_link
				$q_UpdateProblemZip = $db->getQuery(true);
				$q_UpdateProblemZip->update('com_catalogsystem_problem AS p')
					->set('p.zip_link = ' . sqlString($inputZip))
					->where('p.id = ' . sqlInt($info->id));
				$db->setQuery($q_UpdateProblemZip);
				$db->execute();
				if($localDebug) echo '<br/> Changing zip_link to' . sqlString($inputZip);
			}
			
			// If a different category was provided, attach this problem to that category
			$newProbCid = 'NULL';
			if(sqlString($inputCategory)!=='NULL' && $inputCategory !== $info->category)
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
				if($localDebug) echo '<br/> Changing category_id to' . sqlInt($newProbCid);
			}
			
			// If a different source was provided, attach this problem to that source
			$newProbSid = 'NULL';
			if(sqlString($inputSource)!=='NULL' && $inputSource !== $info->source)
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
				if($localDebug) echo '<br/> Changing source_id to' . sqlInt($newProbSid);
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
                
                $teamID = 'NULL';
                $q_teamID = $db->getQuery(true);
                $q_teamID->select('t.id AS tId')
                    ->from('com_catalogsystem_team AS t')
                    ->where('t.name = ' . sqlString($inputUseTeam));
                $db->setQuery($q_teamID);
                $r_teamID = $db->loadObject();
                $teamID = sqlInt(objGet($r_teamID, 'tId'), 1);
				
				// QUERY: insert the new history entry into the history table
				$q_InsertHistory = $db->getQuery(true);
				$q_InsertHistory->insert('com_catalogsystem_history')
					->values(sqlInt($newHistId)
					. ', ' . sqlInt($info->id)
					. ', ' . sqlInt($teamID)
					. ', ' . sqlDate($inputAddUse)
					);
				$db->setQuery($q_InsertHistory);
				$db->execute();
				if($localDebug) echo '<br/> Adding history entry with id='.sqlInt($newHistId).' and date=' . sqlDate($inputAddUse);
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
						if($localDebug) echo '<br/>Added ProblemSet relationship:<br/>' . $q_InsertProblemSet->__toString();
					}
				}
			}
			
			// Display a temporary confirmation message
			echo '<br/><b>[Problem Updated Successfully]</b><br/>';
			
		}//End of 'if(POST)' section
		
		
		
		
		
		
		
		
		
		// ------------
		// DISPLAY HTML
		// ------------
		$urlStr = Route::_("index.php?option=com_catalogsystem&view=catalogc");
        echo "<a href='$urlStr'>Back</a>";
        echo "<h2>Edit Problem: $info->name</h2>";
        
        echo "<form action='index.php?option=com_catalogsystem&view=editproblem&id=$info->id'
                method='post' name='editForm' id='editForm' enctype='multipart/form-data'>";
        
            $this->form->setValue("name", "", $info->name);
            $this->form->setFieldAttribute("source", "default", $info->source);
            $this->form->setFieldAttribute("category", "default", $info->category);
            $this->form->setValue("dif", "", $info->difficulty);
            echo $this->form->renderFieldset("details");
            
			if($info->pdf_link != null && $pdfExists){
				$pdfDownload = $uri . "media/com_catalogsystem/uploads/pdf/" . $info->pdf_link . ".pdf";
				echo "<p>Problem PDF: <a href='$pdfDownload'>Download</a></p>";
			} else {
				echo "<p>Problem PDF: N/A</p>";
			}
			echo $this->form->renderField("pdfupload");
			
			if($info->zip_link != null && $zipExists){
				$zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zip_link . ".zip";
				echo "<p>Problem ZIP: <a href='$zipDownload' download>Download</a></p>";
			} else {
				echo "<p>Problem ZIP: N/A</p>";
			}
			echo $this->form->renderField("zipupload");

            echo $this->form->renderField("add_sets");
            echo $this->form->renderField("add_use");
            echo $this->form->renderField("useTeam");

            echo "<h4>Remove Uses?</h4>";
            echo "<table class='table table-striped table-hover' id='myTable2'>
                    <thead>
                        <tr>
                            <th>
                                <input type='checkbox' id='toggle2' name='toggle2' label=' ' onclick='toggleAll(\"myTable2\", \"toggle2\")'>";
                        echo "</th>
                            <th>Date Used</th>
                            <th>Used By</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($info->history as $i => $row):
                echo "<tr>";
                $xmlStr = '<field name="' . 'delUse_' . $row->id . '" type="checkbox" label=" "/>';
                $xml = new SimpleXMLElement($xmlStr);
                $this->form->setField($xml);
                echo "<td>";
                    echo $this->form->renderField("delUse_$row->id");
                echo "</td>
                        <td>$row->date</td>
                        <td>$row->teamName</td>
                    </tr>";
            endforeach;

            echo "</tbody>
                </table>";



            echo "<h4>Remove from Sets?</h4>";
            echo "<table class='table table-striped table-hover' id='myTable'>
                    <thead>
                        <tr>
                            <th>
                                <input type='checkbox' id='toggle' name='toggle' label=' ' onclick='toggleAll(\"myTable\", \"toggle\")'>";
                        echo "</th>
                            <th>Set Name</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($info->sets as $i => $row):
                echo "<tr>";
                $xmlStr = '<field name="' . 'delSet_' . $row->id . '" type="checkbox" label=" "/>';
                $xml = new SimpleXMLElement($xmlStr);
                $this->form->setField($xml);
                echo "<td>";
                    echo $this->form->renderField("delSet_$row->id");
                echo "</td> 
                    <td>$row->name</td>
                </tr>";
            endforeach;
            echo "</tbody>
                </table>";

            echo "<button type='submit'>Confirm Changes</button>";
        echo "</form>";
    }
?>