<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

class DisplayController extends BaseController
{
	// This function is called every time a page is displyed.
	// It determines what models should be used, based on the view.
	// It also reroutes coaches/students if they try to access the student/coach version of a page
    public function display($cachable = false, $urlparams = array())
    {		
        $app = Factory::getApplication();
		
		// -----IMPORTANT-----
		// When this component is installed onto a site, this number must be changed to match the groupid of the coach group.
		$coachid = 10;
		
		// Determine if the user is a guest, student, or coach
        $user = $app->getIdentity();
        $guest = $user->guest;
        $groups = $user->getAuthorisedGroups();
        $isCoach = in_array($coachid, $groups);
        
		// Get the current view
        $document = Factory::getDocument();
        $viewName = $this->input->getCmd('view', 'login');
        
		// If the user is a guest, redirect them to the login page
        if ($guest === 1){
            $returnRoute = base64_encode('index.php?option=com_catalogsystem&view=' . $viewName);
            $url = Route::_('index.php?option=com_users&view=login&return=' . $returnRoute);
            $app->redirect($url);
        }
		// If the user is a coach, redirect them from any student pages to the coach equivalents
		// The coach versions have all the same info, but they also have options for editing the database
		else if($isCoach){
            if ($viewName === 'catalog'){
                $url = Route::_('index.php?option=com_catalogsystem&view=catalogc');
                $app->redirect($url);
            }else if ($viewName === 'sets'){
                $url = Route::_('index.php?option=com_catalogsystem&view=setsc');
                $app->redirect($url);
            }else if ($viewName === 'problemdetails'){
                $viewName = 'editproblem';
            }
		// If the user is a student, redirect them from any coach pages to the student equivalents
		// The student versions have all the same info, but they cannot edit anything
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
        
		// Get the view object that the models will be assigned to
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
		// This error should never occur. If a URL is mistyped, Joomla will display its own error message
		else
		{
			echo "<br/><b>WARNING: Unknown viewName '$viewName'</b><br/>";
			echo "Please update site/src/Controller/DisplayController.php with a new \"else if(\$viewName==='$viewName')\" block<br/>";
		}

        $view->document = $document;
        $view->display();
    }
}
