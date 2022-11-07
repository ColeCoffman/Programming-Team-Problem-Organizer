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
    protected $pagination;
    protected $form;
	protected $result;
	protected $historyPagination;
	protected $setsPagination;

	/**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
		// Edit this problem according to the POST data (if there is any)
		$this->details = $this->get('Item', 'ProblemDetails_Item');
		$this->details->history = $this->get('Items', 'ProblemHistory_List');
		$this->details->sets = $this->get('Items', 'ProblemSets_List');
		$this->getModel('EditProblem_Write')->setState("details",$this->details);
		$this->result = $this->get('Item', 'EditProblem_Write');
		
		// Get the new details of this problem, after it was edited
		$this->details = $this->get('Item', 'ProblemDetails_Item');
		$this->details->history = $this->get('Items', 'ProblemHistory_List');
		$this->details->sets = $this->get('Items', 'ProblemSets_List');
		
		$this->historyPagination = $this->get('Pagination', 'ProblemHistory_List');
        $this->setsPagination = $this->get('Pagination', 'ProblemSets_List');
		
        $this->form = $this->get('form', 'EditProblem_Form');
		
        parent::display($template);
    }
}
