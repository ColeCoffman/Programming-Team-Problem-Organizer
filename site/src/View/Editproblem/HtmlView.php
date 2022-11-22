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

	/**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    public function display($template = null)
    {
		// Make sure that a valid problem is being shown
		$this->details = $this->get('Item', 'ProblemDetails_Item');
		if (is_null($this->details))
		{
            echo "<h2>Error: Problem does not exist</h2>";
            echo "<h3>Include a valid id in the URL to edit problem.</h3>";
            return;
		}
		
		// EDIT THIS PROBLEM according to the POST data (if there is any)
		// NOTE: If the database was changed, this triggers a refresh to update the page
		$this->details->history = $this->get('Items', 'ProblemHistory_List');
		$this->details->sets = $this->get('Items','ProblemSets_List');
		$this->getModel('EditProblem_Write')->setState("details",$this->details);
		$this->result = $this->get('Item', 'EditProblem_Write');
		
        // This loads the form for editing the problem
        $this->form = $this->get('form', 'EditProblem_Form');
		
        // Call the parent to display the layout file
        parent::display($template);
    }
}
