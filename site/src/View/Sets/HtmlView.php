<?php
// This file holds the View information for the Student version of the Sets page
namespace ProgrammingTeam\Component\CatalogSystem\Site\View\Sets;
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

    // Overrides Joomla's HtmlView to read/write data before displaying the template file
	// (this function is called by "site/src/Controller/DisplayController.php")
    public function display($template = null)
    {
        // Get the set info that will be displayed in the sets table
        $this->items = $this->get('Items', 'Sets_List');
        // Get the pagination for the sets table
        $this->pagination = $this->get('Pagination', 'Sets_List');
        // This loads the form associated with the search panel at the top of the page
        $this->form = $this->get('form', 'Sets_Form');
        
        // These sorting variables are needed to use Joomla's Pagination functionality
        $state = $this->get('State', 'Sets_List');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        

        // Call the parent display to display the template file
		// Specifically, this View displays "site/tmpl/sets/default.php"
        parent::display($template);
    }
}
