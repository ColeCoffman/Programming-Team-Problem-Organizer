<?php
// This file holds the View information for the Coach version of the Catalog page
namespace ProgrammingTeam\Component\CatalogSystem\Site\View\CatalogC;
// No direct access to this file
defined('_JEXEC') or die; 

// Imports
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    // These are the variables the template will use to render the Catalog page
    protected $items;
    protected $pagination;
    protected $form;
    protected $form2;

    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
		// Update problems in database if POST commands were sent
		// NOTE: If the database was changed, this triggers a refresh to update the page
		$this->result = $this->get('Item', 'CatalogOp_Write');
        // Read problem info for display
        $this->items = $this->get('Items', 'Catalog_List');
        // This loads the Pagination functionality
        $this->pagination = $this->get('Pagination', 'Catalog_List');
        // This loads the forms associated with the search panel and Coach Operation panel, respectively
        $this->form = $this->get('form', 'Catalog_Form');
        $this->form2 = $this->get('form', 'CatalogOp_Form');
        
        // These variables are needed to use Joomla's Pagination functionality
        $state = $this->get('State', 'Catalog_List');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
        // Call the parent to display the layout file
        parent::display($template);
    }
}
