<?php


// This is the default admin controller.
// Since our component only adds site pages, this is unused


namespace ProgrammingTeam\Component\CatalogSystem\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController
{
    protected $default_view = 'catalog';
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }
}
