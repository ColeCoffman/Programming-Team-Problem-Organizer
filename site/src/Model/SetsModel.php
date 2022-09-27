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
class SetsModel extends ListModel
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
		$setsWhere = '1=1';
		if(array_key_exists('sets_name',$data) && $data['sets_name'] !== '')
		{
			$setsWhere .= ' AND e.name LIKE "%' . $data['sets_name'] . '%"';
		}

		
		$db = Factory::getContainer()->get('DatabaseDriver');
		$setsQuery = $db->getQuery(true);
		/*
		SELECT e.id AS set_id, e.name AS name, e.zip_link AS zip, COUNT(ps.problem_id) AS numProblems
		FROM com_catalogsystem_set AS e
		LEFT JOIN com_catalogsystem_problemset AS ps ON e.id = ps.set_id
		WHERE {procedurally generated}
		GROUP BY e.id
		*/
        $setsQuery->select('e.id AS set_id, e.name AS name, e.zip_link AS zip, COUNT(ps.problem_id) AS numProblems')
		->from('com_catalogsystem_set AS e')
		->join('LEFT','com_catalogsystem_problemset AS ps ON e.id = ps.set_id')
		->where($setsWhere)
		->group('e.id');
		
		if($localDebug) echo '<br/> Catalog SQL Query: <br/>' . $setsQuery->__toString();
		
		return $setsQuery;
    }
}
