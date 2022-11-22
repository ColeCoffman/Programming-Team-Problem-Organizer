<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\FormModel;

class EditProblem_FormModel extends FormModel
{
    // Build the form object from "site/models/forms/edit_problem_form.xml"
	// (see edit_problem_form.xml for details on the various input fields)
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogsystem.editproblem', 'edit_problem_form',
                       array(
                            'control' => 'jform',	// the name of the array for the POST parameters
                            'load_data' => $loadData	// will be TRUE
			             )
                    );
        return $form;
    }
    
	// This is the Joomla default for this function
	// The edit_problem_form is always prefilled with the data for the specific problem that is being edited
	// However, that operation is done in "site/tmpl/editproblem/default.php", not here.
    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState(
			'com_catalogsystem.editproblem',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);

		return $data;
	}
}
