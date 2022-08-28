<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * Catalog System Message Model
 * @since 0.0.5
 */
class CatalogModel extends ListModel
{
    /**
     * Returns a message for display
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    protected function getListQuery()
    {

        $db = Factory::getDbo();
        $query = $db->getQuery(true);
    
        // Select statement
        $query->select('*')
            ->from($db->quoteName('d8bcc_problem'));

        // Order by
        // $query->order('name');
        return $query;
    }
}
