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

    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
        // This holds all the result sets from the user query
        $this->items = $this->get('Items', 'Sets_List');
        // This loads the Pagintaion functionality
        $this->pagination = $this->get('Pagination', 'Sets_List');
        // This loads the form associated with the search panel at the top of the page
        $this->form = $this->get('form', 'Sets_Form');
        
        // These variables are needed to use Joomla's Pagination functionality
        $state = $this->get('State', 'Sets_List');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
        // Call the parent display to display the layout file
        parent::display($template);
    }
}
