<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

require_once dirname(__FILE__).'/../../tmpl/functionLib.php';

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
		//echo '<br/><b>SetsModel:getListQuery()</b><br/>';
        // enable/disable debug displays for this method
		$localDebug = false;
		
		// Retrieve the POST data
		$app  = Factory::getApplication();
		$data = $app->input->post->get('jform', array(), "array");
		
		// Build the WHERE and HAVING clauses for the SQL Query:
		// (This defaults to a true statement because the '->where()' function always requires a parameter)
		$setsWhere = '1=1';
		$setsHaving = '1=1';
		if(array_key_exists('sets_name',$data) && sqlStringLike($data['sets_name']) !== 'NULL')
		{
			$setsWhere .= ' AND e.name LIKE ' . sqlStringLike($data['sets_name']);
		}
		
		if(array_key_exists('sets_date_notbefore',$data) && sqlDate($data['sets_date_notbefore']) !== 'NULL')
		{
			$setsHaving .= ' AND MIN(h.date) >= ' . sqlDate($data['sets_date_notbefore']);
		}
		// Prevent the WHERE clause from interfering with the HAVING MIN
		else
		{
			if(array_key_exists('sets_date_after',$data) && sqlDate($data['sets_date_after']) !== 'NULL')
			{
				$setsWhere .= ' AND h.date >= ' . sqlDate($data['sets_date_after']);
			}
		}
		// Prevent the WHERE clause from interfering with the HAVING MAX
		if(array_key_exists('sets_date_notafter',$data) && sqlDate($data['sets_date_notafter']) !== 'NULL')
		{
			$setsHaving .= ' AND MAX(h.date) <= ' . sqlDate($data['sets_date_notafter']);
		}
		else
		{
			if(array_key_exists('sets_date_before',$data) && sqlDate($data['sets_date_before']) !== 'NULL')
			{
				$setsWhere .= ' AND h.date <= ' . sqlDate($data['sets_date_before']);
			}
		}
		
		$db = Factory::getContainer()->get('DatabaseDriver');
		$setsQuery = $db->getQuery(true);
		/*
		SELECT e.id AS set_id, e.name AS name, e.zip_link AS zip, COUNT(ps.problem_id) AS numProblems, MIN(h.date) AS firstUsed, MAX(h.date) AS lastUsed
		FROM com_catalogsystem_set AS e
		LEFT JOIN com_catalogsystem_problemset AS ps ON e.id = ps.set_id
		LEFT JOIN com_catalogsystem_problem AS p ON ps.problem_id = p.id
		LEFT JOIN com_catalogsystem_history AS h ON p.id = h.problem_id
		WHERE {Procedurally Generated}
		GROUP BY e.id
		HAVING {Procedurally generated}
		*/
        $setsQuery->select('e.id AS set_id, e.name AS name, e.zip_link AS zip, COUNT(DISTINCT ps.problem_id) AS numProblems, MIN(h.date) AS firstUsed, MAX(h.date) AS lastUsed')
		->from('com_catalogsystem_set AS e')
		->join('LEFT','com_catalogsystem_problemset AS ps ON e.id = ps.set_id')
		->join('LEFT','com_catalogsystem_problem AS p ON ps.problem_id = p.id')
		->join('LEFT','com_catalogsystem_history AS h ON p.id = h.problem_id')
		->where($setsWhere)
		->group('e.id')
		->having($setsHaving);
		
		if($localDebug) echo '<br/> Sets SQL Query: <br/>' . $setsQuery->__toString();
		
		return $setsQuery;
    }
}
