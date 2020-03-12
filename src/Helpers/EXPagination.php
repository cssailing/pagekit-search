<?php
/**
 * @package     Pagekit.Search.Libraries
 * @subpackage  Pagination
 *
 * @copyright   Copyright (C) 2016 Pagekit, Inc. All rights reserved.
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */



/**
 * Pagination Class. Provides a common interface for content pagination for the Joomla! CMS.
 *
 * @package     Pagekit Extension
 * @based on old package     Joomla.Libraries
 * @subpackage  Pagination
 * @since       1.5
 */
namespace Pagekit\Search\Helpers;
use Pagekit\Search\Helpers\EXPaginationObject;
class EXPagination
{
	/**
	 * @var    integer  The record number to start displaying from.
	 * @since  1.5
	 */
	public $limitstart = null;

	/**
	 * @var    integer  Number of rows to display per page.
	 * @since  1.5
	 */
	public $limit = null;

	/**
	 * @var    integer  Total number of rows.
	 * @since  1.5
	 */
	public $total = null;

	/**
	 * @var    integer  Prefix used for request variables.
	 * @since  1.6
	 */
	public $prefix = null;

	/**
	 * @var    integer  Value pagination object begins at
	 * @since  3.0
	 */
	public $pagesStart;

	/**
	 * @var    integer  Value pagination object ends at
	 * @since  3.0
	 */
	public $pagesStop;

	/**
	 * @var    integer  Current page
	 * @since  3.0
	 */
	public $pagesCurrent;

	/**
	 * @var    integer  Total number of pages
	 * @since  3.0
	 */
	public $pagesTotal;

	/**
	 * @var    boolean  View all flag
	 * @since  3.0
	 */
	protected $viewall = false;

	/**
	 * Additional URL parameters to be added to the pagination URLs generated by the class.  These
	 * may be useful for filters and extra values when dealing with lists and GET requests.
	 *
	 * @var    array
	 * @since  3.0
	 */
	protected $additionalUrlParams = array();
	
	/**
	 * @var    string  current url
	 * @since  0.1
	 */
	protected $route;

	/**
	 * Constructor.
	 *
	 * @param   integer  $total       The total number of items.
	 * @param   integer  $limitstart  The offset of the item to start at.
	 * @param   integer  $limit       The number of items to display per page.
	 * @param   string   $prefix      The prefix used for request variables.
	 *
	 * @since   1.5
	 */
	public function __construct($total, $limitstart, $limit, $prefix = '', $InAdditionalUrlParams ='', $route ='')
	{
		// Value/type checking.
		$this->total = (int) $total;
		$this->limitstart = (int) max($limitstart, 0);
		$this->limit = (int) max($limit, 0);
		$this->prefix = $prefix;

		if ($this->limit > $this->total)
		{
			$this->limitstart = 0;
		}

		if (!$this->limit)
		{
			$this->limit = $total;
			$this->limitstart = 0;
		}

		/*
		 * If limitstart is greater than total (i.e. we are asked to display records that don't exist)
		 * then set limitstart to display the last natural page of results
		 */
		if ($this->limitstart > $this->total - $this->limit)
		{
			$this->limitstart = max(0, (int) (ceil($this->total / $this->limit) - 1) * $this->limit);
		}

		// Set the total pages and current page values.
		if ($this->limit > 0)
		{
			$this->pagesTotal = ceil($this->total / $this->limit);
			$this->pagesCurrent = ceil(($this->limitstart + 1) / $this->limit);
		}

		// Set the pagination iteration loop values.
		$displayedPages = 10;
		$this->pagesStart = $this->pagesCurrent - ($displayedPages / 2);

		if ($this->pagesStart < 1)
		{
			$this->pagesStart = 1;
		}

		if ($this->pagesStart + $displayedPages > $this->pagesTotal)
		{
			$this->pagesStop = $this->pagesTotal;

			if ($this->pagesTotal < $displayedPages)
			{
				$this->pagesStart = 1;
			}
			else
			{
				$this->pagesStart = $this->pagesTotal - $displayedPages + 1;
			}
		}
		else
		{
			$this->pagesStop = $this->pagesStart + $displayedPages - 1;
		}

		// If we are viewing all records set the view all flag to true.
		if ($limit == 0)
		{
			$this->viewall = true;
		}
		
		if ($InAdditionalUrlParams != '')
			{
			
				foreach ($InAdditionalUrlParams as $key => $value)
				{
					$this->setAdditionalUrlParam($key, $value);
				}
			
			}
		if ($route !='') 
			{
			$this->route = $route;
			}
	}

	/**
	 * Method to set an additional URL parameter to be added to all pagination class generated
	 * links.
	 *
	 * @param   string  $key    The name of the URL parameter for which to set a value.
	 * @param   mixed   $value  The value to set for the URL parameter.
	 *
	 * @return  mixed  The old value for the parameter.
	 *
	 * @since   1.6
	 */
	public function setAdditionalUrlParam($key, $value)
	{
		// Get the old value to return and set the new one for the URL parameter.
		$result = isset($this->additionalUrlParams[$key]) ? $this->additionalUrlParams[$key] : null;

		// If the passed parameter value is null unset the parameter, otherwise set it to the given value.
		if ($value === null)
		{
			unset($this->additionalUrlParams[$key]);
		}
		else
		{
			$this->additionalUrlParams[$key] = $value;
		}

		return $result;
	}

	/**
	 * Method to get an additional URL parameter (if it exists) to be added to
	 * all pagination class generated links.
	 *
	 * @param   string  $key  The name of the URL parameter for which to get the value.
	 *
	 * @return  mixed  The value if it exists or null if it does not.
	 *
	 * @since   1.6
	 */
	public function getAdditionalUrlParam($key)
	{
		$result = isset($this->additionalUrlParams[$key]) ? $this->additionalUrlParams[$key] : null;

		return $result;
	}

	/**
	 * Return the rationalised offset for a row with a given index.
	 *
	 * @param   integer  $index  The row index
	 *
	 * @return  integer  Rationalised offset for a row with a given index.
	 *
	 * @since   1.5
	 */
	public function getRowOffset($index)
	{
		return $index + 1 + $this->limitstart;
	}

	/**
	 * Return the pagination data object, only creating it if it doesn't already exist.
	 *
	 * @return  object   Pagination data object.
	 *
	 * @since   1.5
	 */
	public function getData()
	{
		static $data;

		if (!is_object($data))
		{
			$data = $this->_buildDataObject();
		}

		return $data;
	}

	/**
	 * Create and return the pagination pages counter string, ie. Page 2 of 4.
	 *
	 * @return  string   Pagination pages counter string.
	 *
	 * @since   1.5
	 */
	public function getPagesCounter()
	{
		$html = null;

		if ($this->pagesTotal > 1)
		{
			$s = __('Page %s of %s');
			$html .= sprintf($s, $this->pagesCurrent, $this->pagesTotal);
		}

		return $html;
	}

	/**
	 * Create and return the pagination result set counter string, e.g. Results 1-10 of 42
	 *
	 * @return  string   Pagination result set counter string.
	 *
	 * @since   1.5
	 */
	public function getResultsCounter()
	{
		$html = null;
		$fromResult = $this->limitstart + 1;

		// If the limit is reached before the end of the list.
		if ($this->limitstart + $this->limit < $this->total)
		{
			$toResult = $this->limitstart + $this->limit;
		}
		else
		{
			$toResult = $this->total;
		}

		// If there are results found.
		if ($this->total > 0)
		{
			$s = __('Results %s - %s of %s');
			$msg = sprintf($s, $fromResult, $toResult, $this->total);
			$html .= "\n" . $msg;
		}
		else
		{
			$html .= "\n" . __('No record found');
		}

		return $html;
	}

	/**
	 * Create and return the pagination page list string, ie. Previous, Next, 1 2 3 ... x.
	 *
	 * @return  string  Pagination page list string.
	 *
	 * @since   1.5
	 */
	public function getPagesLinks()
	{
		//$app = JFactory::getApplication();

		// Build the page navigation list.
		$data = $this->_buildDataObject();

		$list = array();
		$list['prefix'] = $this->prefix;

		$itemOverride = false;
		$listOverride = false;

		//$chromePath = JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';

		// if (file_exists($chromePath))
		// {
			// include_once $chromePath;

			// if (function_exists('pagination_item_active') && function_exists('pagination_item_inactive'))
			// {
				// $itemOverride = true;
			// }

			// if (function_exists('pagination_list_render'))
			// {
				// $listOverride = true;
			// }
		// }

		// Build the select list
		if ($data->all->base !== null)
		{
			$list['all']['active'] = true;
			$list['all']['data'] = ($itemOverride) ? pagination_item_active($data->all) : $this->_item_active($data->all);
		}
		else
		{
			$list['all']['active'] = false;
			$list['all']['data'] = ($itemOverride) ? pagination_item_inactive($data->all) : $this->_item_inactive($data->all);
		}

		if ($data->start->base !== null)
		{
			$list['start']['active'] = true;
			$list['start']['data'] = ($itemOverride) ? pagination_item_active($data->start) : $this->_item_active($data->start);
		}
		else
		{
			$list['start']['active'] = false;
			$list['start']['data'] = ($itemOverride) ? pagination_item_inactive($data->start) : $this->_item_inactive($data->start);
		}

		if ($data->previous->base !== null)
		{
			$list['previous']['active'] = true;
			$list['previous']['data'] = ($itemOverride) ? pagination_item_active($data->previous) : $this->_item_active($data->previous);
		}
		else
		{
			$list['previous']['active'] = false;
			$list['previous']['data'] = ($itemOverride) ? pagination_item_inactive($data->previous) : $this->_item_inactive($data->previous);
		}

		// Make sure it exists
		$list['pages'] = array();

		foreach ($data->pages as $i => $page)
		{
			if ($page->base !== null)
			{
				$list['pages'][$i]['active'] = true;
				$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_active($page) : $this->_item_active($page);
			}
			else
			{
				$list['pages'][$i]['active'] = false;
				$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_inactive($page) : $this->_item_inactive($page);
			}
		}

		if ($data->next->base !== null)
		{
			$list['next']['active'] = true;
			$list['next']['data'] = ($itemOverride) ? pagination_item_active($data->next) : $this->_item_active($data->next);
		}
		else
		{
			$list['next']['active'] = false;
			$list['next']['data'] = ($itemOverride) ? pagination_item_inactive($data->next) : $this->_item_inactive($data->next);
		}

		if ($data->end->base !== null)
		{
			$list['end']['active'] = true;
			$list['end']['data'] = ($itemOverride) ? pagination_item_active($data->end) : $this->_item_active($data->end);
		}
		else
		{
			$list['end']['active'] = false;
			$list['end']['data'] = ($itemOverride) ? pagination_item_inactive($data->end) : $this->_item_inactive($data->end);
		}

		if ($this->total > $this->limit)
		{
			return ($listOverride) ? pagination_list_render($list) : $this->_list_render($list);
		}
		else
		{
			return '';
		}
	}

	/**
	 * Create and return the pagination page list string, ie. Previous, Next, 1 2 3 ... x.
	 *
	 * @return  string  Pagination page list string.
	 *
	 * @since   3.3
	 */
	public function getPaginationPages()
	{
		$list = array();

		if ($this->total > $this->limit)
		{
			// Build the page navigation list.
			$data = $this->_buildDataObject();

			// All
			$list['all']['active'] = (null !== $data->all->base);
			$list['all']['data']   = $data->all;

			// Start
			$list['start']['active'] = (null !== $data->start->base);
			$list['start']['data']   = $data->start;

			// Previous link
			$list['previous']['active'] = (null !== $data->previous->base);
			$list['previous']['data']   = $data->previous;

			// Make sure it exists
			$list['pages'] = array();

			foreach ($data->pages as $i => $page)
			{
				$list['pages'][$i]['active'] = (null !== $page->base);
				$list['pages'][$i]['data']   = $page;
			}

			$list['next']['active'] = (null !== $data->next->base);
			$list['next']['data']   = $data->next;

			$list['end']['active'] = (null !== $data->end->base);
			$list['end']['data']   = $data->end;
		}

		return $list;
	}

	/**
	 * Creates a dropdown box for selecting how many records to show per page.
	 *
	 * @return  string  The HTML for the limit # input box.
	 *
	 * @since   1.5
	 */
	public function getLimitBox()
	{
		$limits = array();

		// Make the option list.
		for ($i = 5; $i <= 30; $i += 5)
		{
			$limits += array( $i => $i);
		}

		$limits += array( '50' => '50');
		$limits += array('100' => '100');
		$limits += array('0'=> __('All'));
		
		$selected = $this->viewall ? 0 : $this->limit;
		
		$html	 = "<select name=\"search[limit]\" class=\"uk-select uk-form-width-small uk-form-small\" id=\"limit\"  v-model=\"search.limit\" onchange=\"this.form.submit()\">";
		foreach ($limits as $key => $name)
			{
			$html_2 	 = null;	
			$html_2	 	.= "<option ";
			if ($key == $selected) {$html_2	.= "selected=\"selected\"";}
			$html_2		.= " value=\"".$key . "\">". $name . "</option>";
			//$orders[] 	 = $html_2;
			$html 		.= $html_2;
			}
		$html	.= "</select>";
		//$lists['ordering'] = $html;
	
		return $html;
	}
	


	/**
	 * Create the html for a list footer
	 *
	 * @param   array  $list  Pagination list data structure.
	 *
	 * @return  string  HTML for a list start, previous, next,end
	 *
	 * @since   1.5
	 */
	protected function _list_render($list)
	{
		// Reverse output rendering for right-to-left display.
/* 		$html = '<ul>';
		$html .= '<li class="pagination-start">' . $list['start']['data'] . '</li>';
		$html .= '<li class="pagination-prev">' . $list['previous']['data'] . '</li>';

		foreach ($list['pages'] as $page)
		{
			$html .= '<li>' . $page['data'] . '</li>';
		}

		$html .= '<li class="pagination-next">' . $list['next']['data'] . '</li>';
		$html .= '<li class="pagination-end">' . $list['end']['data'] . '</li>';
		$html .= '</ul>';

		return $html; */
		$current = 1;
		$range   = 1;
		$step    = 3;

		foreach ($list['pages'] as $i => $page) {
			if (!$page['active']) $current = $i;
		}

		if ($current >= $step) {
			$range = ($current % $step == 0) ? ceil($current / $step) + 1 : ceil($current / $step);
		}

		$html = array('<ul class="uk-pagination">');


		if ($list['previous']['active']==1) $html[] = $list['previous']['data'];


		foreach ($list['pages'] as $i => $page) {

			$item = ($i != $current) ? $page['data'] : str_replace('<li class="uk-disabled">', '<li class="uk-active">', $page['data']);

			if (in_array($i, range($range * $step - ($step + 1), $range * $step))) {

				if (($i % $step == 0 || $i == $range * $step - ($step + 1)) && $i != $current && $i != $range * $step - $step) {
					$item = $page['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $page['data']);
				}
			}

			$html[] = $item;
		}

		if ($list['next']['active']==1) $html[] = $list['next']['data'];

		$html[] = '</ul>';

		return implode("\n", $html);
	}

	/**
	 * Method to create an active pagination link to the item
	 *
	 * @param   JPaginationObject  $item  The object with which to make an active link.
	 *
	 * @return  string  HTML link
	 *
	 * @since   1.5
	 */
	protected function _item_active(EXPaginationObject $item)
	{
		
		$cls = '';
		$title = '';

	    if ($item->text == __('Next')) { $item->text = '<i class="uk-icon-arrow-right"></i>'; $cls = "next tm-pagination-next"; $title = __('Next'); }
	    if ($item->text == __('Prev')) { $item->text = '<i class="uk-icon-arrow-left"></i>'; $cls = "previous tm-pagination-previous"; $title = __('Prev'); }
		if ($item->text == __('Start')) { $cls = "first"; }
	    if ($item->text == __('End')) { $cls = "last"; }

	    return '<li><a class="'.$cls.'" href="'.$item->link.'" title="'.$title.'">'.$item->text.'</a></li>';
		
	}

	/**
	 * Method to create an inactive pagination string
	 *
	 * @param   JPaginationObject  $item  The item to be processed
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	protected function _item_inactive(EXPaginationObject $item)
	{
		return '<li class="uk-disabled"><span>'.$item->text.'</span></li>';
	}

	/**
	 * Create and return the pagination data object.
	 *
	 * @return  object  Pagination data object.
	 *
	 * @since   1.5
	 */
	protected function _buildDataObject()
	{
		$data = new \stdClass;

		// Build the additional URL parameters string.
		$params = '';
		if (!empty($this->additionalUrlParams))
		{
			$params_arr = array_reverse($this->additionalUrlParams, true);
			//foreach ($params_arr as $key => $value)
			//{
			//	$params .= '&' . $key . '=' . $value;
			//}
			$params = http_build_query($params_arr);
			
			// ----- 	for ensure compatible w metod "$this->redirect('@search/site', $post);"	 -------
			//$params = '?'. substr($params, 1); 
			$params = '?'.$params;
			//  substitute first char "&" on "?"
		}

		$data->all = new EXPaginationObject(__('All'), $this->prefix);

		if (!$this->viewall)
		{
			$data->all->base = '0';
			$data->all->link = $this->route . $params . '&' . $this->prefix . 'limitstart=';
		}

		// Set the start and previous data objects.
		$data->start 	= new EXPaginationObject(__('Start'), $this->prefix);
		$data->previous = new EXPaginationObject(__('Prev'), $this->prefix);

		if ($this->pagesCurrent > 1)
		{
			$page = ($this->pagesCurrent - 2) * $this->limit;

			// Set the empty for removal from route
			// @todo remove code: $page = $page == 0 ? '' : $page;

			$data->start->base = '0';
			$data->start->link = $this->route . $params . '&' . $this->prefix . 'limitstart=0';
			
			$data->previous->base = $page;
			$data->previous->link = $this->route . $params . '&' . $this->prefix . 'limitstart=' . $page;
			
		}

		// Set the next and end data objects.
		$data->next = new EXPaginationObject(__('Next'), $this->prefix);
		$data->end 	= new EXPaginationObject(__('End'), $this->prefix);

		if ($this->pagesCurrent < $this->pagesTotal)
		{
			$next = $this->pagesCurrent * $this->limit;
			$end = ($this->pagesTotal - 1) * $this->limit;

			$data->next->base = $next;
			$data->next->link =  $this->route . $params . '&' . $this->prefix . 'limitstart=' . $next;
			$data->end->base = $end;
			$data->end->link = $this->route . $params . '&' . $this->prefix . 'limitstart=' . $end;
		}

		$data->pages = array();
		$stop = $this->pagesStop;

		for ($i = $this->pagesStart; $i <= $stop; $i++)
		{
			$offset = ($i - 1) * $this->limit;

			$data->pages[$i] = new EXPaginationObject($i, $this->prefix);

			if ($i != $this->pagesCurrent || $this->viewall)
			{
				$data->pages[$i]->base = $offset;
				$data->pages[$i]->link = $this->route . $params . '&' . $this->prefix . 'limitstart=' . $offset;
			}
			else
			{
				$data->pages[$i]->active = true;
			}
		}

		return $data;
	}

	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $value     The value of the property to set.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 * @deprecated  4.0  Access the properties directly.
	 */
	public function set($property, $value = null)
	{
		//JLog::add('JPagination::set() is deprecated. Access the properties directly.', JLog::WARNING, 'deprecated');

		if (strpos($property, '.'))
		{
			$prop = explode('.', $property);
			$prop[1] = ucfirst($prop[1]);
			$property = implode($prop);
		}

		$this->$property = $value;
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $default   The default value.
	 *
	 * @return  mixed    The value of the property.
	 *
	 * @since   3.0
	 * @deprecated  4.0  Access the properties directly.
	 */
	public function get($property, $default = null)
	{
		//JLog::add('JPagination::get() is deprecated. Access the properties directly.', JLog::WARNING, 'deprecated');

		if (strpos($property, '.'))
		{
			$prop = explode('.', $property);
			$prop[1] = ucfirst($prop[1]);
			$property = implode($prop);
		}

		if (isset($this->$property))
		{
			return $this->$property;
		}

		return $default;
	}
}
