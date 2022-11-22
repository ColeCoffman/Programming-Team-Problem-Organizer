<?php
// This file holds the View information for the Coach version of the Sets page
namespace ProgrammingTeam\Component\CatalogSystem\Site\View\SetsC;
// No direct access to this file
defined('_JEXEC') or die;

// Imports
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    // These variables will be used by the template to render the Sets page
    protected $items;
    protected $pagination;
    protected $form;
    protected $form2;
	protected $result;

    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
		// Update sets in database if POST commands were sent
		$this->result = $this->get('Item', 'SetsOp_Write');
        // Read Sets info for display
        $this->items = $this->get('Items', 'Sets_List');
        // This loads the Pagination functionality
        $this->pagination = $this->get('Pagination', 'Sets_List');
        // These load the forms associated with the search panel and Coach Operations panel, respectively
        $this->form = $this->get('form', 'Sets_Form');
        $this->form2 = $this->get('form', 'SetsOp_Form');
        
        // These variables are needed to use Joomla's Pagination functionality
        $state = $this->get('State', 'Sets_List');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
        // Call the parent display to display the layout file
        parent::display($template);
    }
}