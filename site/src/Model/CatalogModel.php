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
		
		// Retrive URL data and format the data into an array of keys and values
		$queryStrings = explode('&',$_SERVER['QUERY_STRING']);
		$urlData = array();
		foreach($queryStrings as $queryStr)
		{
			$queryStrParts = explode('=',$queryStr);
			if(count($queryStrParts) == 2) $urlData[$queryStrParts[0]] = $queryStrParts[1];
		}
		// If there is a URL set, use it to overwrite the POST set
		if(array_key_exists('set',$urlData) && $urlData['set'] !== '')
		{
			$data['catalog_set'] = array($urlData['set']);
		}
		
		// Build the WHERE clauses for the SQL Query:
		// (This defaults to a true statement because the '->where()' function always requires a parameter)
		$catalogWhere = '1=1';
		if(array_key_exists('catalog_name',$data) && $data['catalog_name'] !== '')
		{
			$catalogWhere .= ' AND p.name LIKE "%' . $data['catalog_name'] . '%"';
		}
		if(array_key_exists('catalog_set',$data) && count($data['catalog_set']) > 0)
		{
			$catalogWhere .= ' AND (0=1';
			foreach ($data['catalog_set'] as $eid)
			{
				$catalogWhere .= ' OR ps.set_id = "' . $eid . '"';
			}
			$catalogWhere .= ')';
		}
		if(array_key_exists('catalog_category',$data) && count($data['catalog_category']) > 0)
		{
			$catalogWhere .= ' AND (0=1';
			foreach ($data['catalog_category'] as $cid)
			{
				$catalogWhere .= ' OR c.id = "' . $cid . '"';
			}
			$catalogWhere .= ')';
		}
		if(array_key_exists('catalog_source',$data) && count($data['catalog_source']) > 0)
		{
			$catalogWhere .= ' AND (0=1';
			foreach ($data['catalog_source'] as $sid)
			{
				$catalogWhere .= ' OR s.id = "' . $sid . '"';
			}
			$catalogWhere .= ')';
		}
		if(array_key_exists('catalog_mindif',$data) && $data['catalog_mindif'] !== '')
		{
			$catalogWhere .= ' AND p.difficulty >= "' . $data['catalog_mindif'] . '"';
		}
		if(array_key_exists('catalog_maxdif',$data) && $data['catalog_maxdif'] !== '')
		{
			$catalogWhere .= ' AND p.difficulty <= "' . $data['catalog_maxdif'] . '"';
		}
		
		
		$db = Factory::getContainer()->get('DatabaseDriver');
		$catalogQuery = $db->getQuery(true);
		/*
				SELECT p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, MAX(h.date) AS lastUsed
		FROM com_catalogsystem_problem AS p
		LEFT JOIN com_catalogsystem_category AS c ON p.category_id = c.id
		LEFT JOIN com_catalogsystem_source AS s ON p.source_id = s.id
		LEFT JOIN com_catalogsystem_history AS h ON p.id = h.problem_id
        LEFT JOIN com_catalogsystem_problemset AS ps ON p.id = ps.problem_id
		WHERE ps.set_id=3
		GROUP BY p.id
		*/
		$catalogQuery->select('p.name AS name, p.difficulty AS difficulty, p.id AS id, c.name AS category, s.name AS source, MAX(h.date) AS lastUsed')
		->from('com_catalogsystem_problem AS p')
		->join('LEFT','com_catalogsystem_category AS c ON p.category_id = c.id')
		->join('LEFT','com_catalogsystem_source AS s ON p.source_id = s.id')
		->join('LEFT','com_catalogsystem_history AS h ON p.id = h.problem_id')
		->join('LEFT','com_catalogsystem_problemset AS ps ON p.id = ps.problem_id')
		->where($catalogWhere)
		->group('p.id');
		
		if($localDebug) echo '<br/> Catalog SQL Query: <br/>' . $catalogQuery->__toString();
		
		return $catalogQuery;
    }
}
