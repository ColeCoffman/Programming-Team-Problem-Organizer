<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

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
        $app = Factory::getApplication();
        $user = $app->getIdentity();
        $guest = $user->guest;
        $groups = $user->getAuthorisedGroups();
        $coachid = 10;
        $isCoach = in_array($coachid, $groups);
        
        $document = Factory::getDocument();
        $viewName = $this->input->getCmd('view', 'login');
        
        if ($guest === 1){
            $returnRoute = base64_encode('index.php?option=com_catalogsystem&view=' . $viewName);
            $url = Route::_('index.php?option=com_users&view=login&return=' . $returnRoute);
            $app->redirect($url);
        }else if($isCoach){
            if ($viewName === 'catalog'){
                $url = Route::_('index.php?option=com_catalogsystem&view=catalogc');
                $app->redirect($url);
            }else if ($viewName === 'sets'){
                $url = Route::_('index.php?option=com_catalogsystem&view=setsc');
                $app->redirect($url);
            }else if ($viewName === 'problemdetails'){
                $viewName = 'editproblem';
            }
        }else{
            if ($viewName === 'catalogc'){
                $url = Route::_('index.php?option=com_catalogsystem&view=catalog');
                $app->redirect($url);
            }else if ($viewName === 'setsc'){
                $url = Route::_('index.php?option=com_catalogsystem&view=sets');
                $app->redirect($url);
            }else if ($viewName === 'editproblem'){
                $viewName = 'problemdetails';
            }else if ($viewName === 'addproblem'){
                $url = Route::_('index.php?option=com_catalogsystem&view=catalog');
                $app->redirect($url);
            }
        }
        
        $viewFormat = $document->getType();
		$view = $this->getView($viewName, $viewFormat);
		
		// Determine which models need to be loaded based on the view
		if($viewName==='addproblem')
		{
			$view->setModel($this->getModel('AddProblem_Form'));
			$view->setModel($this->getModel('AddProblem_Write'));
		}
		else if($viewName==='catalog')
		{
			$view->setModel($this->getModel('Catalog_List'), true);
			$view->setModel($this->getModel('Catalog_Form'));
		}
		else if($viewName==='catalogc')
		{
			$view->setModel($this->getModel('Catalog_List'), true);
			$view->setModel($this->getModel('Catalog_Form'));
			$view->setModel($this->getModel('CatalogOp_Form'));
			$view->setModel($this->getModel('CatalogOp_Write'));
		}
		else if($viewName==='editproblem')
		{
			$view->setModel($this->getModel('ProblemDetails_Item'));
			$view->setModel($this->getModel('ProblemHistory_List'));
			$view->setModel($this->getModel('ProblemSets_List'));
			$view->setModel($this->getModel('EditProblem_Form'));
			$view->setModel($this->getModel('EditProblem_Write'));
		}
		else if($viewName==='problemdetails')
		{
			$view->setModel($this->getModel('ProblemDetails_Item'));
			$view->setModel($this->getModel('ProblemHistory_List'));
			$view->setModel($this->getModel('ProblemSets_List'));
		}
		else if($viewName==='sets')
		{
			$view->setModel($this->getModel('Sets_List'));
			$view->setModel($this->getModel('Sets_Form'));
		}
		else if($viewName==='setsc')
		{
			$view->setModel($this->getModel('Sets_List'));
			$view->setModel($this->getModel('Sets_Form'));
			$view->setModel($this->getModel('SetsOp_Form'));
			$view->setModel($this->getModel('SetsOp_Write'));
		}
		else
		{
			echo "<br/><b>WARNING: Unknown viewName '$viewName'</b><br/>";
			echo "Please update site/src/Controller/DisplayController.php with a new \"else if(\$viewName==='$viewName')\" block<br/>";
		}

        $view->document = $document;
        $view->display();
    }
}
