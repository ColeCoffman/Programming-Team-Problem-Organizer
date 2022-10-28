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
$wa->useScript('catalogHelper')
    ->useStyle('info');

$pdfExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/pdf/'.$this->item->pdf_link. '.pdf');
$zipExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/zip/'.$this->item->zip_link. '.zip');
?>

<?php
	use Joomla\CMS\Uri\Uri;
	$uri = Uri::root();
    if (is_null($this->item)){
        echo "<h2>Error: Problem does not exist</h2>";
        echo "<h3>Include a valid id in the URL to view problem details.</h3>";
    }else{
        $info = $this->item;
		if($info->zip_link != null && $zipExists){
			$zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zip_link.".zip";
		}
		if($info->pdf_link != null && $pdfExists){
			$pdfDownload = $uri . "media/com_catalogsystem/uploads/pdf/" . $info->pdf_link.".pdf";
		}
		echo "<div class= 'info-box'>";
        echo "<div class='problem-title'>$info->name</div>
				<div class='details'>";
        echo "<div class= 'problem-header'>
				<label id= 'category'>Category:</label>
    		<div class='title'>$info->category</div></div>";
        echo "<div class= 'problem-header'>
				<label id= 'difficulty'>Difficulty:</label>
						<div class='title'> $info->difficulty</div></div>";
        echo "<div class= 'problem-header'>
						<label id= 'source'>Source:</label>
							<div class= 'title'> $info->source</div></div>";
		if($info->pdf_link != null && $pdfExists){
			echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <a class= 'title' href='$pdfDownload'>Download</a></div>";
		} else {
			echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <div class= 'title'>Not Available</div></div>";
		}
		if($info->zip_link != null && $zipExists){
			echo "<div class= 'problem-header'><label id= 'zip'>Problem ZIP:</label> <a class= 'title' href='$zipDownload' download>Download</a></div>";
		} else {
			echo "<div class= 'problem-header'><label id= 'pdf'>Problem ZIP:</label> <div class= 'title'>Not Available</div></div>";
		}
echo "</div></div>";
		echo "<div class= 'tables'>";
		echo "<div class= 'history_table'>";
        echo "<table class='catalog_table'>
                <thead>
                    <tr>
                        <th class= 'unsorted'>Use History</th>
                    </tr>
                </thead>
                <tbody>";
				echo "</div>";
        foreach ($info->history as $i => $row):
            echo "<tr>
                    <td>$row->date</td>
                </tr>";
        endforeach;

    echo "</tbody>
        </table>
		 </div>";

		echo "<div class= 'sets_table'>";
        echo "<table class='catalog_table'>
					<thead>
							<tr>
									<th class= 'unsorted'>Sets Included</th>
							</tr>
					</thead>
                <tbody>";

        foreach ($info->sets as $i => $row):
            echo "<tr>
                    <td>$row->name</td>
                </tr>";
        endforeach;

    echo "</tbody>
        </table>
				</div>
					</div>";

    }
?>
