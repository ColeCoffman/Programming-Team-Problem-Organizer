<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\Sets;

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
        // Get the set info that will be displayed in the sets table
        $this->items = $this->get('Items', 'Sets_List');
		// Get the pagination for the sets table
        $this->pagination = $this->get('Pagination', 'Sets_List');
		// Get the form that will accept the sets search filters
        $this->form = $this->get('form', 'Sets_Form');
        // Get the sorting information for the sets table
        $state = $this->get('State', 'Sets_List');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
		
		// Call the parent display to display the template file
		// Specifically, this View displays "site/tmpl/sets/default.php"
        parent::display($template);
    }
}
