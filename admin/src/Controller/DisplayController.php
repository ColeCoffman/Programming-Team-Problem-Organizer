<?php

namespace ProgrammingTeam\Component\CatalogSystem\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_catalogsystem
 *
 * @copyright
 * @license
 */

/**
 * Default Controller of Catalog System component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_catalogsystem
 */
class DisplayController extends BaseController
{
    /**
     * The default view for the display method.
     *
     * @var string
     */
    protected $default_view = 'main';

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }
}
