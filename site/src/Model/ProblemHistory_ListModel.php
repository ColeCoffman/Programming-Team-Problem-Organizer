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
class ProblemHistory_ListModel extends ListModel
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
		
	    $historyQuery = $db->getQuery(true);
		$historyQuery->select('history.id AS id, history.date AS date, team.name AS teamName')
			->from($db->quoteName('com_catalogsystem_history', 'history'))
            ->join('LEFT', $db->quoteName('com_catalogsystem_team', 'team') . ' ON (' . $db->quoteName('history.team_id') . ' = ' . $db->quoteName('team.id') . ')')
			->where($db->quoteName('history.problem_id') . " = " . $db->quote($idvar))
			->order('date DESC');

	    return $historyQuery;
    }
}
