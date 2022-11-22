<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

class ProblemDetails_ItemModel extends ItemModel
{
    // Overrides ItemModel, the View file calls this function to get an object (it can contain any kind of info)
	// This specific function returns all of the details for a specified problem id
	// This specific function is used by the problemdetails and editproblem pages
    public function getItem($pk= null)
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

        // Select statement Name, Category, Difficulty, Source, Last Used
	    $query->select($db->quoteName(array('com_catalogsystem_problem.name', 'com_catalogsystem_problem.difficulty', 'com_catalogsystem_problem.id', 'com_catalogsystem_problem.zip_link', 'com_catalogsystem_problem.pdf_link'), array('name', 'difficulty', 'id', 'zipUrl', 'pdfPath')))
		    ->select($db->quoteName(array('category.id','category.name'), array('cid','category')))
		    ->select($db->quoteName(array('source.id','source.name'), array('sid','source')))
		    ->from($db->quoteName('com_catalogsystem_problem'), 'problem')
		    ->join('LEFT', $db->quoteName('com_catalogsystem_category', 'category') . ' ON (' . $db->quoteName('com_catalogsystem_problem.category_id') . ' = ' . $db->quoteName('category.id') . ')')
		    ->join('LEFT', $db->quoteName('com_catalogsystem_source', 'source') . ' ON (' . $db->quoteName('com_catalogsystem_problem.source_id') . ' = ' . $db->quoteName('source.id') . ')')
		    ->where($db->quoteName('com_catalogsystem_problem.id') . " = " . $db->quote($idvar));

	    $db->setQuery($query);
	    $result = $db->loadobject();

	    if($result == NULL){
			echo '<br/>ERROR: SQL Query id not find a problem with id = '.$idvar.'<br/>';
		    return NULL;
	    }
		return $result;
    }
}
