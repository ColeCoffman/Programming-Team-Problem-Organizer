<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * Catalog System Component Controller
 * @since  0.0.2
 */
class DisplayController extends FormController
{
    public function display($cachable = false, $urlparams = array())
    {
        $document = Factory::getDocument();
        $viewName = $this->input->getCmd('view', 'login');
        $viewFormat = $document->getType();

        $view = $this->getView($viewName, $viewFormat);
        $view->setModel($this->getModel('Catalog'), true);
        $view->setModel($this->getModel('CatalogSearch'));
        $view->setModel($this->getModel('CatalogOp'));
        $view->setModel($this->getModel('AddProblem'));
        $view->setModel($this->getModel('ProblemDetails'));
        $view->setModel($this->getModel('Sets'));
        $view->setModel($this->getModel('SetSearch'));
        $view->setModel($this->getModel('SetOp'));

        $view->document = $document;
        $view->display();
    }
}
