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
		// enable/disable debug displays for this method
		$localDebug = false;
		
		// Retrieve the POST data
		$app  = Factory::getApplication();
		$data = $app->input->post->get('jform', array(), "array");
		
		
		// Build the WHERE clauses for the SQL Query:
		// (This defaults to a true statement because the '->where()' function always requires a parameter)
		$catalogWhere = '1=1'; 
		$catalogOrder = 'p.id';
		if(array_key_exists('name',$data) && $data['name'] !== '')
		{
			$catalogWhere .= ' AND p.name LIKE "%' . $data['name'] . '%"';
		}
		if(array_key_exists('category',$data) && count($data['category']) > 0)
		{
			$catalogWhere .= ' AND (0=1';
			foreach ($data['category'] as $cid)
			{
				$catalogWhere .= ' OR c.id = "' . $cid . '"';
			}
			$catalogWhere .= ')';
		}
		if(array_key_exists('source',$data) && count($data['source']) > 0)
		{
			$catalogWhere .= ' AND (0=1';
			foreach ($data['source'] as $sid)
			{
				$catalogWhere .= ' OR s.id = "' . $sid . '"';
			}
			$catalogWhere .= ')';
		}
		if(array_key_exists('mindif',$data) && $data['mindif'] !== '')
		{
			$catalogWhere .= ' AND p.difficulty >= "' . $data['mindif'] . '"';
		}
		if(array_key_exists('maxdif',$data) && $data['maxdif'] !== '')
		{
			$catalogWhere .= ' AND p.difficulty <= "' . $data['maxdif'] . '"';
		}
		if(array_key_exists('sortby',$data) && $data['sortby'] !== '')
		{
			$catalogOrder = $data['sortby'];
		}
		
		/* SQL Query:
		SELECT p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, h.date AS lastUsed
		FROM com_catalogsystem_problem AS p
		LEFT JOIN com_catalogsystem_category AS c ON p.category_id = c.id
		LEFT JOIN com_catalogsystem_source AS s ON p.source_id = s.id
		LEFT JOIN com_catalogsystem_history AS h ON p.id = h.problem_id
		WHERE {procedurally generated conditions}
		GROUP BY p.id
		*/
		$db = Factory::getContainer()->get('DatabaseDriver');
		$catalogQuery = $db->getQuery(true);
		
		// TODO: Known error: 'h.date AS lastUsed' uses the first id, which is the oldest date, not the newest
		
		$catalogQuery->select('p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, h.date AS lastUsed')
		->from('com_catalogsystem_problem AS p')
		->join('LEFT','com_catalogsystem_category AS c ON p.category_id = c.id')
		->join('LEFT','com_catalogsystem_source AS s ON p.source_id = s.id')
		->join('LEFT','com_catalogsystem_history AS h ON p.id = h.problem_id')
		->where($catalogWhere)
		->group('p.id')
		->order($catalogOrder);
		
		if($localDebug) echo '<br/> Catalog SQL Query: <br/>' . $catalogQuery->__toString();
		
		return $catalogQuery;
		
		
		
		
		/*
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true);

		/* SQL Query:
		SELECT p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, h.date AS lastUsed
		FROM com_catalogsystem_problem AS p
		LEFT JOIN com_catalogsystem_category AS c ON p.category_id = c.id
		LEFT JOIN com_catalogsystem_source AS s ON p.source_id = s.id
		LEFT JOIN com_catalogsystem_history AS h ON p.id = h.problem_id
		GROUP BY p.id
		/
		
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
		*/
    }
}
