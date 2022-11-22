<?php


// This is a default admin view.
// Since our component only adds site pages, this is unused


namespace ProgrammingTeam\Component\CatalogSystem\Administrator\View\Main;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
    public $form;
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->addToolBar();
        parent::display($tpl);
    }
    protected function addToolBar()
    {
        ToolbarHelper::title(Text::_('COM_CATALOGSYSTEM_TITLE'), 'wrench');
        ToolbarHelper::back('Back');
        ToolbarHelper::apply('catalogsystem.apply');
    }
}
