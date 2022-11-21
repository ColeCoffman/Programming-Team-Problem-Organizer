<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\EditProblem;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;

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
    
    protected $details;
    protected $form;
	protected $result;

	/**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
		// Make sure that a valid problem is being shown
		$this->details = $this->get('Item', 'ProblemDetails_Item');
		if (is_null($this->details))
		{
        echo "<h2>Error: Problem does not exist</h2>";
        echo "<h3>Include a valid id in the URL to edit problem.</h3>";
		return;
		}
		
		// EDIT THIS PROBLEM according to the POST data (if there is any)
		// NOTE: If the database was changed, this triggers a refresh to update the page
		$this->details->history = $this->get('Items', 'ProblemHistory_List');
		$this->details->sets = $this->get('Items','ProblemSets_List');
		$this->getModel('EditProblem_Write')->setState("details",$this->details);
		$this->result = $this->get('Item', 'EditProblem_Write');
		
        $this->form = $this->get('form', 'EditProblem_Form');
		
        parent::display($template);
    }
}
