<?php
// This file holds the View information for the Problem Details page
namespace ProgrammingTeam\Component\CatalogSystem\Site\View\ProblemDetails;
// No direct access to this file
defined('_JEXEC') or die;

// Imports
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    // This variable will be used by the template to render the Problem Details page
    protected $item;
    
    // Overrides Joomla's HtmlView to read/write data before displaying the template file
	// (this function is called by "site/src/Controller/DisplayController.php")
    public function display($template = null)
    {
        // Get the basic details of the current problem
        $this->item = $this->get('Item', 'ProblemDetails_Item');
		
        // If the details are null, then this is not a valid problem id (display error and return)
		// NOTE: This usually happens after timing out and logging back in while on the editproblem page
		if (is_null($this->item)){
			echo "<h2>Error: Problem does not exist</h2>";
			echo "<h3>Include a valid id in the URL to view problem details.</h3>";
			return;
		}
		
		// Build the rest of the details (after verifying that "$this->item" is not null)
		$this->item->history = $this->get('Items', 'ProblemHistory_List');
		$this->item->sets = $this->get('Items', 'ProblemSets_List');
		
		
        // This loads the history and set information associated with the problem
		$this->item->history = $this->get('Items', 'ProblemHistory_List');
		$this->item->sets = $this->get('Items', 'ProblemSets_List');
		
		
        // Call the parent display to display the template file
		// Specifically, this View displays "site/tmpl/problemdetails/default.php"
        parent::display($template);
    }
}
