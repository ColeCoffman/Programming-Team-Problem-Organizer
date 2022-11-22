<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

class ProblemSets_ListModel extends ListModel
{
    // Overrides ListModel, Joomla calls this function to get the SQL query for a list
	// This specific function returns all of the set info that is displayed in the problemdetails and editproblem pages
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

		// Retreive all of the set entries that are associated with the specified problem id
	    $SetsQuery = $db->getQuery(true);
	    $SetsQuery->select('sets.id AS id, sets.name AS name')
		    ->from($db->quoteName('com_catalogsystem_set', 'sets'))
		    ->join('INNER', $db->quoteName('com_catalogsystem_problemset', 'problemset') . ' ON (' . $db->quoteName('problemset.problem_id') . ' = ' . $db->quote($idvar) . ')')
		    ->where($db->quoteName('sets.id') . " = " . $db->quoteName('problemset.set_id'))
		    ->order('name ASC');
		
		return $SetsQuery;
    }
}