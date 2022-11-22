<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\FormModel;

class AddProblem_FormModel extends FormModel
{
	// Build the form object from "site/models/forms/add_problem_form.xml"
	// (see add_problem_form.xml for details on the various input fields)
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogsystem.addproblem', 'add_problem_form',
                       array(
                            'control' => 'jform',	// the name of the array for the POST parameters
                            'load_data' => $loadData	// will be TRUE
			             )
                    );
        return $form;
    }
    
	// This is the Joomla default for this function
	// The add_problem_form is always reset to blank after it is used
    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState(
			'com_catalogsystem.add_problem_form',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);

		return $data;
	}
}
