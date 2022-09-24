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
class CatalogModel extends ListModel
{
    /**
     * Returns a message for display
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    protected function getListQuery()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);



        // -- Name, Category, Difficulty, Source, Last Used

        /*
		SELECT problem.id AS 'id', problem.name AS 'name', problem.difficulty AS 'difficulty', category.name AS 'category', source.name AS 'source', MAX(history.date) AS 'lastUsed'
		FROM problem
		LEFT JOIN category ON problem.category = category.id
		LEFT JOIN source ON problem.source_id = source.id
		LEFT JOIN history ON problem.id = history.problem_id
		GROUP BY problem.id
		*/


        // Select statement Name, Category, Difficulty, Source, Last Used
        $query->select(array('problem.name', 'problem.difficulty', 'problem.id'), array('name', 'difficulty', 'id'))
            ->select($db->quoteName(array('category.name'), array('category')))
            ->select($db->quoteName(array('source.name'), array('source')))
            ->select('MAX('.$db->quoteName('history.date').') AS lastUsed')
            ->from($db->quoteName('problem'), 'problem')
            ->join('LEFT', $db->quoteName('category', 'category') . ' ON (' . $db->quoteName('problem.category') . ' = ' . $db->quoteName('category.id') . ')')
            ->join('LEFT', $db->quoteName('source', 'source') . ' ON (' . $db->quoteName('problem.source_id') . ' = ' . $db->quoteName('source.id') . ')')
            ->join('LEFT', $db->quoteName('history', 'history') . ' ON (' . $db->quoteName('problem.id') . ' = ' . $db->quoteName('history.problem_id') . ')')
			->group($db->quoteName('problem.id'));

        // Order by
        // $query->order('name');
        return $query;
    }

    // public function getCategoryTags()
    // {
    //     $db = Factory::getDbo();
    //     $query = $db->getQuery(true);
    //     $query->select("name")->from($db->quoteName('category'));
    //     $db->setQuery($query);
    //     $results = $db->loadColumn();
    //     // $results = $db->loadResult();
    //     return $results;
    // }
}
