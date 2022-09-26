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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app  = Factory::getApplication();
    $data = $app->input->post->get('jform', array(), "array");
    
    // Get PDF upload
    $file = $app->input->files->get('jform', array(), "array");
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

    // Get ZIP Upload
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
    
    // Get a db connection.
    $db    = Factory::getContainer()->get('DatabaseDriver');
    // Create a new query object.
    $query = $db->getQuery(true);
    
    $problemColumns = array("source_id", "category_id", "name", "difficulty", "pdf_link", "zip_link");
    $problemValues = array($data['set_id'], $data['source_id'], $data['name'], $data['difficulty'], $data['category']);
    
    var_dump($data);
    var_dump($file);
}

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