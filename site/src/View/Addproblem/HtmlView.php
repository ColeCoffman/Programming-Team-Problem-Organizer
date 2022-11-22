<?php
// This file holds the View information for the Add Problem page
namespace ProgrammingTeam\Component\CatalogSystem\Site\View\AddProblem;
// No direct access to this file
defined('_JEXEC') or die;
// Imports
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    // These are the variables the template will use to render the Add Problem page
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
		// Add a new problem according to the POST data (if there is any)
		$this->result = $this->get('Item', 'AddProblem_Write');
		
        // The form for adding problems
        $this->form = $this->get('form', 'AddProblem_Form');
        // Call the parent display to display the layout file
        parent::display($template);
    }
}
