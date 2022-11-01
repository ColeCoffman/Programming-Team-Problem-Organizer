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

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper');

use Joomla\CMS\Uri\Uri;
$uri = Uri::root();
?>

<?php 
    if (is_null($this->details)){
        echo "<h2>Error: Problem does not exist</h2>";
        echo "<h3>Include a valid id in the URL to edit problem.</h3>";
    }
	else
	{
		$info = $this->details;
		
		// If a problem was edited, display a temporary confirmation message
		if($this->result->state === 3)
		{
			echo '<br/><b>[Problem Updated Successfully]</b><br/>';
		}
		
        echo "<h2>Edit Problem: $info->name</h2>";
        
        echo "<form action='index.php?option=com_catalogsystem&view=editproblem&id=$info->id'
                method='post' name='editForm' id='editForm' enctype='multipart/form-data'>";
        
            $this->form->setValue("name", "", $info->name);
			if($info->source !== NULL)
			{
				$this->form->setFieldAttribute("source", "default", $info->source);
			}
            if($info->category !== NULL)
			{
				$this->form->setFieldAttribute("category", "default", $info->category);
			}
            if($info->difficulty !== NULL)
			{
				$this->form->setValue("dif", "", $info->difficulty);
			}
            echo $this->form->renderFieldset("details");
            
			if($info->pdfPath != null){
				$pdfDownload = $uri . "media/com_catalogsystem/uploads/pdf/" . $info->pdfPath;
				echo "<p>Problem PDF: <a href='$pdfDownload'>Download</a></p>";
			} else {
				echo "<p>Problem PDF: N/A</p>";
			}
			echo $this->form->renderField("pdfupload");
			
			if($info->zipUrl != null){
				$zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zipUrl;
				echo "<p>Problem ZIP: <a href='$zipDownload' download>Download</a></p>";
			} else {
				echo "<p>Problem ZIP: N/A</p>";
			}
			echo $this->form->renderField("zipupload");

            echo $this->form->renderField("add_sets");
            echo $this->form->renderField("add_use");

            echo "<h4>Remove Uses?</h4>";
            echo "<table class='table table-striped table-hover' id='myTable2'>
                    <thead>
                        <tr>
                            <th>
                                <input type='checkbox' id='toggle2' name='toggle2' label=' ' onclick='toggleAll(\"myTable2\", \"toggle2\")'>";
                        echo "</th>
                            <th>Use Date</th>
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
                    </tr>";
            endforeach;

            echo "</tbody>
                </table>";
            //echo $this->pagination->getListFooter();



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
            //echo $this->pagination->getListFooter();

            echo "<button type='submit'>Confirm Changes</button>";
        echo "</form>";
    }
?>