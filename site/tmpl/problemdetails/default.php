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

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper')
    ->useStyle('info');

$pdfExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/pdf/'.$this->item->pdfPath. '.pdf');
$zipExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/zip/'.$this->item->zipUrl. '.zip');
?>

<?php
	use Joomla\CMS\Uri\Uri;
	$uri = Uri::root();
    $urlStr = Route::_("index.php?option=com_catalogsystem&view=catalog");
    echo "<a href='$urlStr'>Back</a>";

    
        $info = $this->item;
		if($info->pdfPath != null && $pdfExists){
			$pdfDownload = $uri . "media/com_catalogsystem/uploads/pdf/" . $info->pdfPath;
		}
		if($info->zipUrl != null && $zipExists){
			$zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zipUrl;
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
		if($info->pdfPath != null && $pdfExists){
			echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <a class= 'title' href='$pdfDownload' target='_blank' rel='noopener noreferrer'>Download</a></div>";
		} else {
			echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <div class= 'title'>Not Available</div></div>";
		}
		if($info->zipUrl != null && $zipExists){
			echo "<div class= 'problem-header'><label id= 'zip'>Problem ZIP:</label> <a class= 'title' href='$zipDownload' target='_blank' rel='noopener noreferrer' download>Download</a></div>";
		} else {
			echo "<div class= 'problem-header'><label id= 'pdf'>Problem ZIP:</label> <div class= 'title'>Not Available</div></div>";
		}
echo "</div></div>";
		echo "<div class= 'tables'>";
		echo "<div class= 'history_table'>";
        echo "{$this->historyPagination->getLimitBox()}
			<table class='catalog_table'>
                <thead>
                    <tr>
                        <th>Date Used</th>
                        <th>Used By</th>
                    </tr>
                </thead>
                <tbody>";
				echo "</div>";
        foreach ($info->history as $i => $row):
            echo "<tr>
                    <td>$row->date</td>
                    <td>$row->teamName</td>
                </tr>";
        endforeach;

    echo "</tbody>
        </table>
		{$this->historyPagination->getListFooter()}
		 </div>";

		echo "<div class= 'sets_table'>";
        echo "{$this->setsPagination->getLimitBox()}
				<table class='catalog_table'>
					<thead>
							<tr>
									<th>Sets Included</th>
							</tr>
					</thead>
                <tbody>";

        foreach ($info->sets as $i => $row):
            $url = Route::_("index.php?option=com_catalogsystem&view=catalog&set=" . $row->id);
			echo "<tr>
                    <td> <a href='$url'>$row->name</a></td>
                </tr>";
        endforeach;

    echo "</tbody>

        </table>
		{$this->setsPagination->getListFooter()}
				</div>
					</div>";
    
?>
