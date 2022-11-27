<?php
// This file holds the HTML and other display information for the Edit Problem page associated with a given problem
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Imports
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// Imports through WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper')
	->useStyle('catalog')
	->useStyle('info-add');

// This will be used to generate links to the PDF and Zip associated with the problem
$uri = Uri::root();
// Holds the information associated with the problem
$info = $this->details;

// Checking if the PDF and Zip for the file exist in the system
$pdfExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/pdf/'.$info->pdfPath);
$zipExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/zip/'.$info->zipUrl);
?>

<?php
    // If a problem was edited successfully, display a temporary confirmation message
    if($this->result->state === 0)
	{
		JFactory::getApplication()->enqueueMessage('Problem Updated Successfully', 'success');
	}
	// If a problem failed to edit, display the error message
	else if($this->result->state < 0)
	{
		JFactory::getApplication()->enqueueMessage($this->result->msg, 'error');
	}
    // Auto populating the edit fields with existing problem information
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

    // Link back to the catalog
    $urlStr = Route::_("index.php?option=com_catalogsystem&view=catalogc");
    echo "<a onclick= 'onLoad()' href='$urlStr'><button class='return-button'><label class='return-label'>Back</label></button></a>";
		
    echo "<div class= 'info-box'>";
        echo "<div class='problem-title'>Edit Problem: $info->name</div>";
        // This form holds all the edit fields associated with the problem
        echo "<form action='index.php?option=com_catalogsystem&view=editproblem&id=$info->id'
                method='post' name='editForm' id='editForm' enctype='multipart/form-data'>";
            echo "<div class= 'info-box'>";
                echo "<div class='details'>";
                    echo "<div class= 'problem-header'>";
                        echo $this->form->renderFieldset("details");
                    echo "</div>";

                    // Displays N/A or a link to the existing problem PDF
                    if($info->pdfPath != null && $pdfExists){
                        $pdfDownload = $uri . "media/com_catalogsystem/uploads/pdf/" . $info->pdfPath;
                        echo "<div class= 'problem-header' style='display: flex;'><label id= 'pdf' class= 'upload-label'>Problem PDF:</label> <a class= 'title' href='$pdfDownload' target='_blank' rel='noopener noreferrer'>Download</a></div>";
                    } else {
                        echo "<div class= 'problem-header' style='display: flex;'><label id= 'pdf' class= 'upload-label'>Problem PDF:</label> <div class= 'title'>Not Available</div></div>";
                    }

                    echo "<div class= 'problem-header'>";
                        echo $this->form->renderField("pdfupload");
                    echo "</div>";

                    // Displays N/A or a link to the existing problem Zip
                    if($info->zipUrl != null && $zipExists){
                        $zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zipUrl;
                        echo "<div class= 'problem-header' style='display: flex;'><label class= 'upload-label' id= 'zip'>Problem ZIP:</label> <a class= 'title' href='$zipDownload' target='_blank' rel='noopener noreferrer' download>Download</a></div>";
                    } else {
                        echo "<div class= 'problem-header' style='display: flex;'><label class= 'upload-label' id= 'zip'>Problem ZIP:</label> <div class= 'title'>Not Available</div></div>";
                    }
                    echo "<div class= 'problem-header'>";
                        echo $this->form->renderField("zipupload");
                    echo "</div>";
                    echo "<div class= 'problem-header'>";
                        echo $this->form->renderField("add_sets");
                    echo "</div>";
                    echo "<div class= 'schedulers inputuse'>";
                        echo $this->form->renderField("add_use");
                    echo "</div>";
                    echo "<div class= 'problem-header'>";
                        echo $this->form->renderField("useTeam");
                    echo "</div>";
                echo "</div>";
            echo "</div>";

            echo "<div class= 'tables'>";
                echo "<div class= 'history_table'>";
                    echo "<div class= 'problem-header' style='text-align: center'><label id= 'remove_uses' class= 'upload-label'>Remove Uses?</label></div>";
                    echo "<table id= 'myTableHist' class='catalog_table'>
                        <thead>";
                            // This code generates the select/deselect all box at the top of the history table
                            $xmlStr = '<field name="toggle" class= "toggle" type="checkbox" onclick= "toggleAll(tableName=\'myTableHist\', toggleName=\'toggle\')" label=""/>';
                            $xml = new SimpleXMLElement($xmlStr);
                            $this->form->setField($xml);

                            echo "<tr>";
                                echo "<th id='checkcolumn'>";
                                    echo $this->form->renderField("toggle");
                                echo "</th> 
                                      <th onclick='sortTable(1, \"myTableHist\")'>Date Used";
                                echo "</th> 
                                      <th onclick='sortTable(2, \"myTableHist\")'>Used By";
                                echo "</th>
                                  </tr>
                        </thead>
                        <tbody>";

                        foreach ($info->history as $i => $row):
                            // This code generates the checkbox for each row of the table
                            $xmlStr = '<field name="' . 'delUse_' . $row->id . '" type="checkbox" label=" "/>';
                            $xml = new SimpleXMLElement($xmlStr);
                            $this->form->setField($xml);
                            echo "<tr>";
                                echo "<td>";
                                    echo $this->form->renderField("delUse_$row->id");
                                echo "</td>
                                      <td>$row->date</td>
                                      <td>$row->teamName</td>
                            </tr>";
                        endforeach;

                        echo "</tbody>
                    </table>";
                echo "</div>";

                echo "<div class= 'sets_table'>";
                    echo "<div class= 'problem-header' style='text-align: center'><label id= 'remove_sets' class= 'upload-label'>Remove from Sets?</label></div>";
                    echo "<table id= 'myTableSets' class='catalog_table'>
                        <thead>";
                            // This code generates the select/deselect all box at the top of the sets table
                            $xmlStr = '<field name="toggle2" class= "toggle2" type="checkbox" onclick= "toggleAll(tableName=\'myTableSets\', toggleName=\'toggle2\')" label=""/>';
                            $xml = new SimpleXMLElement($xmlStr);
                            $this->form->setField($xml);
                            echo "<tr>";
                                echo "<th id='checkcolumn'>";
                                    echo $this->form->renderField("toggle2");
                                echo "</th> 
                                      <th onclick='sortTable(1, \"myTableSets\")'>Set Name";
                                echo "</th>
                            </tr>
                        </thead>
                        <tbody>";

                        foreach ($info->sets as $i => $row):
                            // This code generates the checkbox for each row of the table
                            $xmlStr = '<field name="' . 'delSet_' . $row->id . '" type="checkbox" label=""/>';
                            $xml = new SimpleXMLElement($xmlStr);
                            $this->form->setField($xml);
                            echo "<tr>";
                                echo "<td>";
                                    echo $this->form->renderField("delSet_$row->id");
                                echo "</td> 
                                      <td>$row->name</td>
                            </tr>";
                        endforeach;
                        echo "</tbody>
                    </table>";
                echo "</div>
            </div>";

            echo "<div class= 'end-content'>";
                echo "<button type='submit' onclick= 'onLoad()' class='submit-button'>Confirm Changes</button>";
        //This generates the loading page
		echo "</div>
		<div id= 'pageloader'>
			<svg  class='loader' viewBox='0 0 50 50'>
				<circle class='path' cx='25' cy='25' r='20' fill='none' stroke-width='5'></circle>
			</svg>
		</div>
		</form>
    </div>;"
?>