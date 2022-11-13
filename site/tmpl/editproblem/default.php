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
$wa->useScript('catalogHelper')
	->useStyle('catalog')
	->useStyle('info-add');

use Joomla\CMS\Uri\Uri;
$uri = Uri::root();
?>

<?php 
echo "<script language='javascript' type='text/javascript'>
    function tableOrdering( order, dir, task )
    {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
    }
</script>";

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
		echo "<div class= 'info-box'>";
		echo "<div class='problem-title'>Edit Problem: $info->name</div>";
        
        echo "<form action='index.php?option=com_catalogsystem&view=editproblem&id=$info->id'
                method='post' name='editForm' id='editForm' enctype='multipart/form-data'>";
			
			echo "<div class='details'>
				<div class= 'problem-header'>";
            $this->form->setValue("name", "", $info->name);
			echo "</div>";
			
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
				echo "<div class= 'problem-header' style='display: flex;'><label id= 'pdf' class= 'upload-label'>Problem PDF:</label> <a class= 'title' href='$pdfDownload'>Download</a></div>";
			} else {
				echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <div class= 'title'>Not Available</div></div>";
			}
			
			echo "<div class= 'problem-header'>";
			echo $this->form->renderField("pdfupload");
			echo "</div>";
			
			if($info->zipUrl != null){
				$zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zipUrl;
				echo "<div class= 'problem-header' style='display: flex;'><label class= 'upload-label' id= 'zip'>Problem ZIP:</label> <a class= 'title' href='$zipDownload' download>Download</a></div>";
			} else {
				echo "<div class= 'problem-header'><label id= 'pdf'>Problem ZIP:</label> <div class= 'title'>Not Available</div>";
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
			echo "</div>
				</div>";
			echo "<div class= 'tables'>";
			echo "<div class= 'history_table'>";
            echo "<div class= 'problem-header' style='text-align: center'><label id= 'remove_uses' class= 'upload-label'>Remove Uses</label></div>";
            echo "<form action='index.php?option=com_catalogsystem&view=editproblem&id=$info->id'
                method='post' name='adminForm' id='adminForm' enctype='multipart/form-data'>
				<table class='catalog_table'>
                    <thead>";
						$xmlStr = '<field name="toggle" class= "toggle" type="checkbox" onclick= "toggleAll()" label=""/>';
                        $xml = new SimpleXMLElement($xmlStr);
                        $this->form->setField($xml);
                        echo "<tr>";
                        echo "<th id='checkcolumn'>";
						echo $this->form->renderField("toggle");
                        echo "</th> <th>";
						echo JHTML::_( 'grid.sort', 'Date Used', 'dateUsed', $this->sortDirection, $this->sortColumn);
                        echo "</th> <th>";
						echo JHTML::_( 'grid.sort', 'Used By', 'usedBy', $this->sortDirection, $this->sortColumn);
						echo" </th>
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
				</form>
				{$this->historyPagination->getListFooter()}
				{$this->historyPagination->getLimitBox()}
				</div>";
			echo "<div class= 'sets_table'>";
            echo "<div class= 'problem-header' style='text-align: center'><label id= 'remove_sets' class= 'upload-label'>Remove from Sets?</label></div>";
            echo "<form action='index.php?option=com_catalogsystem&view=editproblem&id=$info->id'
                method='post' name='adminForm' id='adminForm' enctype='multipart/form-data'>
				<table id= 'myTable2' class='catalog_table'>
                    <thead>";
					  $xmlStr = '<field name="toggle2" class= "toggle2" type="checkbox" onclick= "toggleAll(tableName=myTable2, toggleName=toggle2)" label=""/>';
                      $xml = new SimpleXMLElement($xmlStr);
                      $this->form->setField($xml);
					  echo "<tr>";
                      echo "<th id='checkcolumn'>";
                      echo $this->form->renderField("toggle");
                      echo "</th> <th>";
                          echo JHTML::_( 'grid.sort', 'Set Name', 'setName', $this->sortDirection, $this->sortColumn);
                        echo "</th></tr>
                    </thead>
                    <tbody>";

            foreach ($info->sets as $i => $row):
                echo "<tr>";
                $xmlStr = '<field name="' . 'delSet_' . $row->id . '" type="checkbox" label=""/>';
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
				</form>
				<input type='hidden' name='filter_order' value='<?php echo $this->sortColumn; ?>' />
				<input type='hidden' name='filter_order_Dir' value='<?php echo $this->sortDirection; ?>' />
				{$this->setsPagination->getListFooter()}
				{$this->setsPagination->getLimitBox()}
				</div>
					</div>";
					echo "<div class= 'end-content'>";
            echo "<button type='submit' class='submit-button'>Confirm Changes</button>";
        echo "</div></form>";
    }
?>