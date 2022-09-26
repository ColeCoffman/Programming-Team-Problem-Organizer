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
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true);

		/* SQL Query:
		SELECT p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, h.date AS lastUsed
		FROM com_catalogsystem_problem AS p
		LEFT JOIN com_catalogsystem_category AS c ON p.category_id = c.id
		LEFT JOIN com_catalogsystem_source AS s ON p.source_id = s.id
		LEFT JOIN com_catalogsystem_history AS h ON p.id = h.problem_id
		GROUP BY p.id
		*/
		
		// NOTES:
		// To make a variable safe to use in the string, use:
		// 'text' . $db->quoteName(variable) . 'more text'
		// After the rest of the query, use this to order the results:
		// ->order('name');
		
		$query->select('p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, h.date AS lastUsed')
		->from('com_catalogsystem_problem AS p')
		->join('LEFT','com_catalogsystem_category AS c ON p.category_id = c.id')
		->join('LEFT','com_catalogsystem_source AS s ON p.source_id = s.id')
		->join('LEFT','com_catalogsystem_history AS h ON p.id = h.problem_id')
		->group('p.id');
		
        return $query;
    }
}
