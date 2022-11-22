<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\FormModel;

class Catalog_FormModel extends FormModel
{
    // Build the form object from "site/models/forms/catalog_search_form.xml"
	// (see catalog_search_form.xml for details on the various input fields)
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogsystem.catalogsearch', 'catalog_search_form',
                       array(
                            'control' => 'jform',	// the name of the array for the POST parameters
                            'load_data' => $loadData	// will be TRUE
			             )
                    );
        return $form;
    }
    
	// This function prefills the catalog_search_form with any previous search filters.
	// This preserves the fields across searches and prevents them from being wiped clean every time
    protected function loadFormData()
	{
		// Retrieve the current POST data
		$app  = Factory::getApplication();
		$data = $app->input->post->get('jform', array(), "array");
		
		// Retrive URL data and format the data into an array of keys and values
		$queryStrings = explode('&',$_SERVER['QUERY_STRING']);
		$urlData = array();
		foreach($queryStrings as $queryStr)
		{
			$queryStrParts = explode('=',$queryStr);
			if(count($queryStrParts) == 2) $urlData[$queryStrParts[0]] = $queryStrParts[1];
		}
		// If there is a URL set, use it to overwrite the POST set
		if(array_key_exists('set',$urlData) && $urlData['set'] !== '')
		{
			$data['catalog_set'] = array($urlData['set']);
		}
        
        $data = $app->getUserState(
			'com_catalogsystem.catalogsearch',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);
		
		// If any of the POST keys are valid, they will be used to initialize this form
		// (Invalid or missing keys will be ignored without issue)
		return $data;
	}
}
