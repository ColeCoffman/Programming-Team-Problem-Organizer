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

?>

<?php 
    if (is_null($this->item)){
        echo "<h2>Error: Problem does not exist</h2>";
        echo "<h3>Include a valid id in the URL to edit problem.</h3>";
    }else{
        $info = $this->item;
        echo "<h2>Edit Problem: $info->name</h2>";
        
        echo "<form action='index.php?option=com_catalogsystem&view=problemdetails&id=$info->id'
                method='post' name='editForm' id='editForm' enctype='multipart/form-data'>";
        
            $this->form->setValue("name", "", $info->name);
            $this->form->setFieldAttribute("source", "default", $info->source);
            $this->form->setFieldAttribute("category", "default", $info->category);
            $this->form->setValue("dif", "", $info->difficulty);
            echo $this->form->renderFieldset("details");
            

            echo $this->form->renderField("add_sets");
            echo $this->form->renderField("add_use");

            echo "<h4>Remove Uses?</h4>";
            echo "<table class='table table-striped table-hover' id='myTable2'>
                    <thead>
                        <tr>
                            <th>";
?>
                                <input type="checkbox" id="toggle2" name="toggle2" label=" " onclick="toggleAll('myTable2', 'toggle2')">
        
<?php
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
            echo $this->pagination->getListFooter();



            echo "<h4>Remove from Sets?</h4>";
            echo "<table class='table table-striped table-hover' id='myTable'>
                    <thead>
                        <tr>
                            <th>";
?>
                                <input type="checkbox" id="toggle" name="toggle" label=" " onclick="toggleAll('myTable', 'toggle')">
        
<?php
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
            echo $this->pagination->getListFooter();

            echo "<button type='submit'>Confirm Changes</button>";
        echo "</form>";
    }
?>