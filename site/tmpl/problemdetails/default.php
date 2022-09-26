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

?>

<?php 
    if (is_null($this->item)){
        echo "<h2>Error: Problem does not exist</h2>";
        echo "<h3>Include a valid id in the URL to view problem details.</h3>";
    }else{
        $info = $this->item;
        echo "<h2>$info->name</h2>";
        echo "<h3>Category: $info->category</h3>";
        echo "<h3>Difficulty: $info->difficulty</h3>";
        echo "<h3>Source: $info->source</h3>";
        echo "<h4>Associated Resources:</h4>";
        echo "<p>Problem PDF: $info->pdf_path</p>";
        echo "<p>Link to ZIP: $info->zip_url</p>";
        echo "<h4>Use History:</h4>";
        
        echo "<table class='table table-striped table-hover'>
                <thead>
                    <tr>
                        <th>Date Used</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($info->history as $i => $row):
            echo "<tr>
                    <td>$row->date</td>
                </tr>";
        endforeach;
        
    echo "</tbody>
        </table>";
    echo $this->pagination->getListFooter();
        
    echo "<h4>Included in Sets:</h4>";
        
        echo "<table class='table table-striped table-hover'>
                <tbody>";
        
        foreach ($info->sets as $i => $row):
            echo "<tr>
                    <td>$row->name</td>
                </tr>";
        endforeach;
        
    echo "</tbody>
        </table>";
    echo $this->pagination->getListFooter();
    
    }
?>