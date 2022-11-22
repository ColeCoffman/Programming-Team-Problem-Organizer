<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\Catalog;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    protected $items;
    protected $pagination;
    protected $form;

	// Overrides Joomla's HtmlView to read/write data before displaying the template file
	// (this function is called by "site/src/Controller/DisplayController.php")
    public function display($template = null)
    {
        // Get the problem info that will be displayed in the catalog table
        $this->items = $this->get('Items', 'Catalog_List');
		// Get the pagination for the catalog table
        $this->pagination = $this->get('Pagination', 'Catalog_List');
		// Get the form that will accept the catalog search filters
        $this->form = $this->get('form', 'Catalog_Form');
		// These category tags were used in an older version of the table
        $this->categories = $this->get('CategoryTags');
        // Get the sorting information for the catalog table
        $state = $this->get('State', 'Catalog_List');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
		
		// Call the parent display to display the template file
		// Specifically, this View displays "site/tmpl/catalog/default.php"
        parent::display($template);
    }
}
