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
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Difficulty</th>
            <th>Source</th>
            <th>Last Used</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->items as $i => $row) : ?>
            <tr>
                <td><?php echo $row->name; ?></td>
                <td><?php echo $row->category; ?></td>
                <td><?php echo $row->difficulty; ?></td>
                <td><?php echo $row->source; ?></td>
                <td><?php echo $row->lastUsed; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>