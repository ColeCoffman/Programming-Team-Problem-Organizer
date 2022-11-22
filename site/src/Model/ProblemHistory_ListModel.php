<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

class ProblemHistory_ListModel extends ListModel
{
    // Overrides ListModel, Joomla calls this function to get the SQL query for a list
	// This specific function returns all of the history info that is displayed in the problemdetails and editproblem pages
    public function getListQuery()
    {
		
        $db = Factory::getContainer()->get('DatabaseDriver');
        $uri = Uri::getInstance();
        $idvar = $uri->getVar('id');
        $query = $db->getQuery(true);
		
		// Make sure the problem id is valid
		if($idvar == NULL){
			echo '<br/>ERROR: URL id is missing<br/>';
			return NULL;
		}
		
		// Retreive all of the history entries that are associated with the specified problem id
	    $historyQuery = $db->getQuery(true);
		$historyQuery->select('history.id AS id, history.date AS date, team.name AS teamName')
			->from($db->quoteName('com_catalogsystem_history', 'history'))
            ->join('LEFT', $db->quoteName('com_catalogsystem_team', 'team') . ' ON (' . $db->quoteName('history.team_id') . ' = ' . $db->quoteName('team.id') . ')')
			->where($db->quoteName('history.problem_id') . " = " . $db->quote($idvar))
			->order('date DESC');

	    return $historyQuery;
    }
}
