<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\FormModel;

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * Catalog System Message Model
 * @since 0.0.5
 */
class CatalogOpModel extends FormModel
{
    /**
     * Returns a message for display
     * @param integer $pk Primary key of the "message item", currently unused
     * @return object Message object
     */
    
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogsystem.catalogop', 'catalog_op_form',
                       array(
                            'control' => 'jform2',	// the name of the array for the POST parameters
                            'load_data' => $loadData	// will be TRUE
			             )
                    );
        return $form;
    }
    
    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState(
			'com_catalogsystem.catalogop',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);

		return $data;
	}
}
