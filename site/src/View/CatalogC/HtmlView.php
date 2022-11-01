<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\CatalogC;

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
    protected $items;
    protected $pagination;
    protected $form;
    protected $form2;

    public function display($template = null)
    {
        // Call the parent display to display the layout file
        $this->items = $this->get('Items', 'Catalog');
        $this->pagination = $this->get('Pagination', 'Catalog');
        $this->form = $this->get('form', 'CatalogSearch');
        $this->form2 = $this->get('form', 'CatalogOp');
        $this->categories = $this->get('CategoryTags');
        
        $state = $this->get('State');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
        parent::display($template);
    }
}
