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
class ProblemSets_ListModel extends ListModel
{
    
    public function getListQuery()
    {
		
        $db = Factory::getContainer()->get('DatabaseDriver');
        $uri = Uri::getInstance();
        $idvar = $uri->getVar('id');
        $query = $db->getQuery(true);

		if($idvar == NULL){
			echo '<br/>ERROR: URL id is missing<br/>';
			return NULL;
		}

	    $SetsQuery = $db->getQuery(true);
	    $SetsQuery->select('sets.id AS id, sets.name AS name')
		    ->from($db->quoteName('com_catalogsystem_set', 'sets'))
		    ->join('INNER', $db->quoteName('com_catalogsystem_problemset', 'problemset') . ' ON (' . $db->quoteName('problemset.problem_id') . ' = ' . $db->quote($idvar) . ')')
		    ->where($db->quoteName('sets.id') . " = " . $db->quoteName('problemset.set_id'))
		    ->order('name ASC');
		
		return $SetsQuery;
    }
}