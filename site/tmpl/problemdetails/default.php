<?php
// This file holds the HTML and other display information for the Problem Details page associated with a given problem
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Imports
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// Imports through WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('catalogHelper')
    ->useStyle('info');

// Checking if the PDF and Zip for the file exist in the system
$pdfExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/pdf/'.$this->item->pdfPath);
$zipExists = file_exists(dirname(__FILE__).'/../../../../media/com_catalogsystem/uploads/zip/'.$this->item->zipUrl);

// This will be used to generate links to the PDF and Zip associated with the problem
$uri = Uri::root();
// Holds the information associated with the problem
$info = $this->item;
?>

<?php
    // Link back to the catalog
    $urlStr = Route::_("index.php?option=com_catalogsystem&view=catalog");
    echo "<a href='$urlStr'><button class='return-button'><label class='return-label'>Back</label></button></a>";
    
    echo "<div class= 'info-box'>";
        echo "<div class='problem-title'>$info->name</div>
        <div class='details'>";
            echo "<div class= 'problem-header'>
                <label id= 'category'>Category:</label>
                    <div class='title'>$info->category</div>
            </div>";
            echo "<div class= 'problem-header'>
                <label id= 'difficulty'>Difficulty:</label>
                        <div class='title'> $info->difficulty</div>
            </div>";
            echo "<div class= 'problem-header'>
                <label id= 'source'>Source:</label>
                    <div class= 'title'> $info->source</div>
            </div>";
        // Display link to PDF/Zip for the problem if it exists, otherwise display NA
        if($info->pdfPath != null && $pdfExists){
            $pdfDownload = $uri . "media/com_catalogsystem/uploads/pdf/" . $info->pdfPath;
            echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <a class= 'title' href='$pdfDownload' target='_blank' rel='noopener noreferrer'>Download</a></div>";
        } else {
            echo "<div class= 'problem-header'><label id= 'pdf'>Problem PDF:</label> <div class= 'title'>Not Available</div></div>";
        }
        if($info->zipUrl != null && $zipExists){
            $zipDownload = $uri . "media/com_catalogsystem/uploads/zip/" . $info->zipUrl;
            echo "<div class= 'problem-header'><label id= 'zip'>Problem ZIP:</label> <a class= 'title' href='$zipDownload' target='_blank' rel='noopener noreferrer' download>Download</a></div>";
        } else {
            echo "<div class= 'problem-header'><label id= 'pdf'>Problem ZIP:</label> <div class= 'title'>Not Available</div></div>";
        }
        echo "</div>
    </div>";

    echo "<div class= 'tables'>";
        echo "<div class= 'history_table'>";
            echo "<table id='myTableHist' class='catalog_table'>
                <thead>
                    <tr>
                        <th onclick='sortTable(0, \"myTableHist\")'>Date Used</th>
                        <th onclick='sortTable(1, \"myTableHist\")'>Used By</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($info->history as $i => $row):
                    echo "<tr>
                            <td>$row->date</td>
                            <td>$row->teamName</td>
                        </tr>";
                endforeach;
                echo "</tbody>
            </table>";
        echo "</div>";

        echo "<div class= 'sets_table'>";
            echo "<table id='myTableSets' class='catalog_table'>
                <thead>
                    <tr>
                        <th onclick='sortTable(0, \"myTableSets\")'>Sets Included</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($info->sets as $i => $row):
                    // This code generates the link to the given problem set that the problem is associated with
                    $url = Route::_("index.php?option=com_catalogsystem&view=catalog&set=" . $row->id);
                    echo "<tr>
                        <td> <a href='$url'>$row->name</a></td>
                    </tr>";
                endforeach;

                echo "</tbody>

            </table>";
        echo "</div>
    </div>"; 
?>
