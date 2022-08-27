<?php

namespace ProgrammingTeam\Component\CatalogSystem\Administrator\View\Main;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_catalogsystem
 *
 * @copyright
 * @license
 */

/**
 * Main "CatalogSystem" Admin View
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Display the main "CatalogSystem" view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     * @return  void
     */

    public $form;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form'); // Get the form from the model

        $this->addToolBar(); // Add the toolbar

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        ToolbarHelper::title(Text::_('COM_CATALOGSYSTEM_TITLE'), 'wrench'); // Set the title and icon
        ToolbarHelper::back('Back'); // Cancel Button
        ToolbarHelper::apply('catalogsystem.apply'); // Apply Button
    }
}
