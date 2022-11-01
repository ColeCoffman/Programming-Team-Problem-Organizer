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
use Joomla\CMS\Router\Route;

require_once dirname(__FILE__).'/../functionLib.php';

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
		
		$pdfExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/pdf/'.$info->pdfPath. '.pdf');
		$zipExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/zip/'.$info->zipUrl. '.zip');
		
		// If a problem was edited successfully, display a temporary confirmation message
		if($this->result->state === 0)
		{
			echo '<br/><b>[Problem Updated Successfully]</b><br/>';
		}
		// If a problem failed to edit, display the error message
		else if($this->result->state < 0)
		{
			echo "<br/><b>Problem Failed to Update:</b><br/>$this->result->msg<br/>";
		}
		
		
		$urlStr = Route::_("index.php?option=com_catalogsystem&view=catalogc");
        echo "<a href='$urlStr'>Back</a>";

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
            echo $this->form->renderField("useTeam");

            echo "<h4>Remove Uses?</h4>";
            echo "{$this->historyPagination->getLimitBox()}
				<table class='table table-striped table-hover' id='myTable2'>
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
                </table>
				{$this->historyPagination->getListFooter()}";

            echo "<h4>Remove from Sets?</h4>";
            echo "{$this->setsPagination->getLimitBox()}
				<table class='table table-striped table-hover' id='myTable'>
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
                </table>
				{$this->setsPagination->getListFooter()}";

            echo "<button type='submit'>Confirm Changes</button>";
        echo "</form>";
    }
?>