<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\FormModel;

class Sets_FormModel extends FormModel
{
    // Build the form object from "site/models/forms/set_search_form.xml"
	// (see set_search_form.xml for details on the various input fields)
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogsystem.setsearch', 'set_search_form',
                       array(
                            'control' => 'jform',	// the name of the array for the POST parameters
                            'load_data' => $loadData	// will be TRUE
			             )
                    );
        return $form;
    }
    
	// This function prefills the set_search_form with any previous search filters.
	// This preserves the fields across searches and prevents them from being wiped clean every time
    protected function loadFormData()
	{
		// Retrieve the current POST data
		$app  = Factory::getApplication();
		$data = $app->input->post->get('jform', array(), "array");
        
        $data = $app->getUserState(
			'com_catalogsystem.setsearch',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);
		
		// If any of the POST keys are valid, they will be used to initialize this form
		// (Invalid or missing keys will be ignored without issue)
		return $data;
	}
}
