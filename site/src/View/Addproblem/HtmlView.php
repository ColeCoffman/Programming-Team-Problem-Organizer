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
    
    // Overrides Joomla's HtmlView to read/write data before displaying the template file
	// (this function is called by "site/src/Controller/DisplayController.php")
    public function display($template = null)
    {
		// Add a new problem according to the POST data (if there is any)
		$this->result = $this->get('Item', 'AddProblem_Write');
		
        // Get the form that will be accept the data for creating a new problem
        $this->form = $this->get('form', 'AddProblem_Form');
		
		
        // Call the parent display to display the template file
		// Specifically, this View displays "site/tmpl/addproblem/default.php"
        parent::display($template);
    }
}
