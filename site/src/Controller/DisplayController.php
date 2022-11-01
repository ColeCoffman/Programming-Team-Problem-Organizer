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
class DisplayController extends BaseController
{
    public function display($cachable = false, $urlparams = array())
    {
		$localDebug = true;
		
        $document = Factory::getDocument();
        $viewName = $this->input->getCmd('view', 'login');
        $viewFormat = $document->getType();
		
		if($localDebug)
		{
			echo "<br/>Display Controller: viewName=$viewName, viewFormat=$viewFormat<br/>";
		}
		
		$view = $this->getView($viewName, $viewFormat);
		
		// Determine which models need to be loaded based on the view
		if($viewName==='addproblem')
		{
			$view->setModel($this->getModel('AddProblem'));
		}
		else if($viewName==='catalog')
		{
			$view->setModel($this->getModel('Catalog'), true);
			$view->setModel($this->getModel('CatalogSearch'));
		}
		else if($viewName==='catalogc')
		{
			$view->setModel($this->getModel('Catalog'), true);
			$view->setModel($this->getModel('CatalogSearch'));
			$view->setModel($this->getModel('CatalogOp'));
		}
		else if($viewName==='editproblem')
		{
			$view->setModel($this->getModel('ProblemDetails'));
			$view->setModel($this->getModel('EditProblem_Form'));
			$view->setModel($this->getModel('EditProblem_Write'));
		}
		else if($viewName==='problemdetails')
		{
			$view->setModel($this->getModel('ProblemDetails'));
		}
		else if($viewName==='sets')
		{
			$view->setModel($this->getModel('Sets'));
			$view->setModel($this->getModel('SetSearch'));
		}
		else if($viewName==='setsc')
		{
			$view->setModel($this->getModel('Sets'));
			$view->setModel($this->getModel('SetSearch'));
			$view->setModel($this->getModel('SetOp'));
		}
		else
		{
			echo "<br/><b>WARNING: Unknown viewName '$viewName'</b><br/>";
			echo "Please update site/src/Controller/DisplayController.php with a new 'else if(\$viewName===$viewName)' block<br/>";
		}

        $view->document = $document;
        $view->display();
    }
}
