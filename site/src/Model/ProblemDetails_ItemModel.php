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
class ProblemDetails_ItemModel extends ItemModel
{
    /**
     * Returns a message for display
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    public function getItem($pk= null)
    {
		//echo '<br/><b>ProblemDetailsModel:getItem()</b><br/>';
        $db = Factory::getContainer()->get('DatabaseDriver');
        $uri = Uri::getInstance();
        $idvar = $uri->getVar('id');
        $query = $db->getQuery(true);

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

        
        // Order by
        // $query->order('name');
	    $db->setQuery($query);
	    $result = $db->loadobject();

	    if($result == NULL){
			echo '<br/>ERROR: SQL Query id not find a problem with id = '.$idvar.'<br/>';
		    return NULL;
	    }
		return $result;
    }
}
