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
    
    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
        // This loads the problem
        $this->item = $this->get('Item', 'ProblemDetails_Item');
        // Check if a valid problem is being shown
		if (is_null($this->item)){
			echo "<h2>Error: Problem does not exist</h2>";
			echo "<h3>Include a valid id in the URL to view problem details.</h3>";
			return;
		}
        // This loads the history and set information associated with the problem
		$this->item->history = $this->get('Items', 'ProblemHistory_List');
		$this->item->sets = $this->get('Items', 'ProblemSets_List');
		
        // Call the parent to display the layout file
        parent::display($template);
    }
}
