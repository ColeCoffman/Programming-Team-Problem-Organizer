<?php
// This file holds the View information for the Edit Problem page
namespace ProgrammingTeam\Component\CatalogSystem\Site\View\EditProblem;
// No direct access to this file
defined('_JEXEC') or die;

// Imports
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;

class HtmlView extends BaseHtmlView
{
    // These are the variables the template will use to render the Edit Problem page
    protected $details;
    protected $form;
	protected $result;

	// Overrides Joomla's HtmlView to read/write data before displaying the template file
	// (this function is called by "site/src/Controller/DisplayController.php")
    public function display($template = null)
    {
		// Get the basic details of the current problem
		$this->details = $this->get('Item', 'ProblemDetails_Item');
		
		// If the details are null, then this is not a valid problem id (display error and return)
		// NOTE: This usually happens after timing out and logging back in while on the editproblem page
		if (is_null($this->details))
		{
            echo "<h2>Error: Problem does not exist</h2>";
            echo "<h3>Include a valid id in the URL to edit problem.</h3>";
            return;
		}
		
		// Build the rest of the details (after verifying that "$this->details" is not null)
		$this->details->history = $this->get('Items', 'ProblemHistory_List');
		$this->details->sets = $this->get('Items','ProblemSets_List');
		
		// Pass the problem details along to the WriteModel, so that it can tell if a field was changed
		$this->getModel('EditProblem_Write')->setState("details",$this->details);
		// Edit this problem according to the POST data (if there is any)
		// NOTE: If the database was changed, this triggers a refresh to update the page
		$this->result = $this->get('Item', 'EditProblem_Write');
		
		
		// Get the form that will be accept the data for editing a problem
        $this->form = $this->get('form', 'EditProblem_Form');
        // Get the form that will be accept the data for editing a problem
        $this->form = $this->get('form', 'EditProblem_Form');
		
		
        // Call the parent display to display the template file
		// Specifically, this View displays "site/tmpl/editproblem/default.php"
        parent::display($template);
    }
}
