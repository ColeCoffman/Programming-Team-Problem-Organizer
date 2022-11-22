<?php


// This is a default admin view.
// Since our component only adds site pages, this is unused


namespace JohnSmith\Component\HelloWorld\Administrator\View\Catalog;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView {
    function display($tpl = null) {
        parent::display($tpl);
    }
}