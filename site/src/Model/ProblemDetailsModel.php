<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Uri\Uri;
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
class ProblemDetailsModel extends ItemModel
{
    /**
     * Returns a message for display
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    public function getItem($pk= null)
    {

        $db = Factory::getDbo();
        $uri = Uri::getInstance();
        $idvar = $uri->getVar('id');
        $query = $db->getQuery(true);

		if($idvar == NULL){
			return NULL;
		}

        // Select statement Name, Category, Difficulty, Source, Last Used
	    $query->select(array('problem.name', 'problem.difficulty', 'problem.id', 'problem.zip_url', 'problem.pdf_path'), array('name', 'difficulty', 'id', 'zipUrl', 'pdfPath'))
		    ->select($db->quoteName(array('category.name'), array('category')))
		    ->select($db->quoteName(array('source.name'), array('source')))
		    ->from($db->quoteName('problem'), 'problem')
		    ->join('INNER', $db->quoteName('category', 'category') . ' ON (' . $db->quoteName('problem.category') . ' = ' . $db->quoteName('category.id') . ')')
		    ->join('INNER', $db->quoteName('source', 'source') . ' ON (' . $db->quoteName('problem.source_id') . ' = ' . $db->quoteName('source.id') . ')')
		    ->where($db->quoteName('problem.id') . " = " . $db->quote($idvar));

        
        // Order by
        // $query->order('name');
	    $db->setQuery($query);
	    $result = $db->loadobject();

	    if($result == NULL){
		    return NULL;
	    }

	    $historyQuery = $db->getQuery(true);
		$historyQuery->select(array('history.date'))
			->from($db->quoteName('history', 'history'))
			->where($db->quoteName('history.problem_id') . " = " . $db->quote($idvar))
			->order('date DESC');

	    $db->setQuery($historyQuery);
		$historyResult = $db->loadObjectList();

		$result->history = $historyResult;

	    $SetsQuery = $db->getQuery(true);
	    $SetsQuery->select(array('sets.name'))
		    ->from($db->quoteName('sets', 'sets'))
		    ->join('INNER', $db->quoteName('problemset', 'problemset') . ' ON (' . $db->quoteName('problemset.problem_id') . ' = ' . $db->quote($idvar) . ')')
		    ->where($db->quoteName('sets.id') . " = " . $db->quoteName('problemset.set_id'))
		    ->order('name ASC');

	    $db->setQuery($SetsQuery);
	    $setResults = $db->loadObjectList();

		$result->sets = $setResults;
		return $result;
    }
}
