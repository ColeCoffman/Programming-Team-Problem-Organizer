<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

require_once dirname(__FILE__).'/../../tmpl/functionLib.php';

class Sets_ListModel extends ListModel
{
    // Overrides ListModel, Joomla calls this function to get the SQL query for a list
	// This specific function returns all of the set info that is displayed in the sets and setsc pages
    protected function getListQuery()
    {
        // enable/disable debug displays for this method
		$localDebug = false;
		
		// Retrieve the POST data
		$app  = Factory::getApplication();
		$data = $app->input->post->get('jform', array(), "array");
        
        if (!isset($_POST['filter_clear'])){
			if (empty($data))
				$data = $app->getUserState('com_catalogsystem.setsearch', array());
		} else {
			$data = array();
		}
		
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
		
		// Get all of the sets and their related info, filtered by the generated WHERE and HAVING clauses
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
        
         $setsQuery->order($db->escape($this->getState('list.ordering', 'name')).' '.
		  $db->escape($this->getState('list.direction', 'ASC')));
        
        $app->setUserState('com_catalogsystem.setsearch', $data);
		
		return $setsQuery;
    }
    
	// Overrides ListModel, Joomla calls this function to get the default sorting info
    protected function populateState($ordering = null, $direction = null) {
	   parent::populateState('name', 'ASC');
    }
    
	// Overrides ListModel, Joomla calls this function to get the names of the columns that should be sorted
    public function __construct($config = array())
	{   
		$config['filter_fields'] = array(
			'name',
            'numProblems',
            'firstUsed',
            'lastUsed'
		);
		parent::__construct($config);
	}
}
