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

require __DIR__ . '\\..\\functionLib.php';

// Enable/disable Debug
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
	$inputCategory = arrGet($postData,'category');
	if(is_array($inputCategory)) $inputCategory = arrGet($inputCategory,0);
	$inputSource = arrGet($postData,'source');
	if(is_array($inputSource)) $inputSource = arrGet($inputSource,0);
	$inputDifficulty = arrGet($postData,'dif');
	$inputFirstUse = arrGet($postData,'firstUse');
	$inputSets = arrGet($postData,'set');
	$inputPdf = $pdfFilename;
	$inputZip = $zipFilename;
	
	
	
	// QUERY: Get the next id in the problem table
	$q_ProblemMaxId = $db->getQuery(true);
	$q_ProblemMaxId->select('MAX(p.id) AS "maxId"')
		->from('com_catalogsystem_problem AS p');
	$db->setQuery($q_ProblemMaxId);
	$r_ProblemMaxId = $db->loadObject();
	$newProbId = sqlInt(objGet($r_ProblemMaxId,'maxId'),0) + 1;
	
	// If a category string was provided, attach the new problem to that category
	$newProbCid = 'NULL';
	if(sqlString($inputCategory)!=='NULL')
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
	}
	
	// If a source string was provided, attach the new problem to that source
	$newProbSid = 'NULL';
	if(sqlString($inputSource)!=='NULL')
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
		
		// QUERY: insert the new history entry into the history table
		$q_InsertHistory = $db->getQuery(true);
		$q_InsertHistory->insert('com_catalogsystem_history')
			->values(sqlInt($newHistId)
			. ', ' . sqlInt($newProbId)
			. ', ' . 'NULL'
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
	
	// Display a temporary confirmation message
	echo '<br/><b>[Problem Added Successfully]</b><br/>';
	
}//End of 'if(POST)' section

?>

<form action="index.php?option=com_catalogsystem&view=addproblem"
    method="post" name="addForm" id="addForm" enctype="multipart/form-data">

	<?php echo $this->form->renderField('name');  ?>
	
	<?php echo $this->form->renderField('category');  ?>
	
	<?php echo $this->form->renderField('source');  ?>
    
    <?php echo $this->form->renderField('dif');  ?>
    
    <?php echo $this->form->renderField('firstUse');  ?>
    
    <?php echo $this->form->renderField('set');  ?>

    <?php echo $this->form->renderField('pdfupload');  ?>

    <?php echo $this->form->renderField('zipupload');  ?>
	
	<button type="submit">Add Problem</button>
</form>