<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\ProblemDetails;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * View for the user identity validation form
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    protected $item;

    public function display($template = null)
    {
        $this->item = $this->get('Item', 'ProblemDetails_Item');
		if (is_null($this->item)){
			echo "<h2>Error: Problem does not exist</h2>";
			echo "<h3>Include a valid id in the URL to view problem details.</h3>";
			return;
		}
		$this->item->history = $this->get('Items', 'ProblemHistory_List');
		$this->item->sets = $this->get('Items', 'ProblemSets_List');
		
        parent::display($template);
    }
}
