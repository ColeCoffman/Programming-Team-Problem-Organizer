<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\AddProblem;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * View for the user identity validation form
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    protected $form;
	protected $result;
    
    public function display($template = null)
    {
		// Add a new problem according to the POST data (if there is any)
		$this->result = $this->get('Item', 'AddProblem_Write');
		
        $this->form = $this->get('form', 'AddProblem_Form');
        parent::display($template);
    }
}
