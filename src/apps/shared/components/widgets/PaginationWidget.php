<?php

namespace EuroMillions\shared\components\widgets;

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Simple as ViewSimple;
use Phalcon\Mvc\ViewInterface;

class PaginationWidget extends \Phalcon\Mvc\User\Component {


    CONST PAGINATOR_VIEW = '_elements/';

    protected $total = 1;
    protected $page = 1;
    protected $before;
    protected $next;
    protected $total_items;
    protected $total_pages;
    protected $current;
    protected $last;
    protected $limit;
    protected $url = '';
    protected $_options = [];
    private $paginator;

    public function __construct(\Phalcon\Paginator\AdapterInterface $paginator, $queryArray, $options = []){

        $paginatorObj = $paginator->getPaginate();
        $this->paginator = $paginator;
        $this->total_pages = $paginatorObj->total_pages;
        $this->total_items = $paginatorObj->total_items;
        $this->current = $paginatorObj->current;
        $this->before       = $paginatorObj->before;
        $this->next         = $paginatorObj->next;
        $this->last         = $paginatorObj->last;
        $this->limit = ceil($this->total / $this->total_pages);

        // Create url
        if(isset($options['url'])) {
            $url = $options['url'];
        } else {
            $url = array_shift($queryArray);
        }
        $url .= '?';
        foreach($queryArray as $key => $val){
            if(!$val || $key == 'page')
                continue;
            $url .= $key . '=' . $val . '&';
        }
        $url .= 'page={page}';
        $this->url = $url;

        // Options
        if(isset($options['num_links']))
            $this->total = $options['num_links'];

        $this->setOptions($options);
    }

    public function render() {
        $pagination = [
            'limit' => $this->limit,
            'count' => $this->total_items,
            'total' => $this->total_pages,
            'current' => $this->current,
            'next_num' => $this->next,
            'next_url' => $this->getLink($this->next),
            'prev_num' => $this->before,
            'prev_url' => $this->getLink($this->before),
            'first_num' => 1,
            'first_url' => $this->getLink(1),
            'last_num' => $this->last,
            'last_url' => $this->getLink($this->last),
            'pages' => [],
            'isFirst' => 1 == $this->current,
            'isLast' => $this->last == $this->current,
        ];

        $dotted = false;
        $separator   = $this->getOptions('separator');
        $classActive = $this->getOptions('classActive');
        $c = $this->current;
        $t = $this->total_pages;
        $k = $this->getOptions('numPage');
        $pagination['pages'] = [];

       for ($i = 1; $i <= $t; $i++) {
            $page = [];
            $page['url'] = $this->getLink($i);
            $page['num'] = $i;
            $page['isSeparator'] = false;
            $page['isActive'] = ($this->current == $i) ? true : false;
            $page['class'] = ($this->current == $i) ? $classActive : null;
            if (($i > $k && $i <= ($c - $k)) || ($i >= ($c + $k) && $i <= ($t - $k))) {

                if (!$dotted) {
                    $page['num'] = $separator;
                    $page['isSeparator'] = true;
                    $pagination['pages'][] = $page;
                }
                $dotted = true;
                continue;
            }
            $dotted = false;
            $pagination['pages'][] = $page;
        }
        $params['pagination'] = $pagination;
        $this->getView();
       try {
            return $this->getView()->render('_elements/pagination',$params);
        } catch (\Exception $exc) {

        }
    }

    /**
     * Gets the view service
     *
     * @return ViewSimple|ViewInterface
     */
    public function getView()
    {

        $defaultViewsDir = $this->getDI()->get('view')->getViewsDir();
        $this->_view = new ViewSimple();
        $this->_view->setViewsDir($this->getOptions('viewsDir', $defaultViewsDir));
        return $this->_view;
    }

    /**
     * Get options for configuring widget
     *
     * @param string|null $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getOptions($key = null, $default = null)
    {
        if ($key !== null) {
            return isset($this->_options[$key]) ? $this->_options[$key] : $default;
        } else {
            return $this->_options;
        }
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'paramKey'     => 'page',
            'separator'    => '...',
            'classActive'  => 'active',
            'numVisible'   => 1,
            'numPage'      => 2
        ];
    }


    /**
     * @param $page
     *
     * @return string
     */
    protected function getLink($page)
    {
        $url = $this->getOptions('url');
        if ($url == '#') {
            return $url;
        }
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
        $_uri =  parse_url($url ?: $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        if (!empty($_uri['query'])) {
            parse_str($_uri['query'], $query);
            $query[$this->getOptions('paramKey')] = $page;
            return urldecode($_uri['path'] . '?' . http_build_query($query));
        } else {
            return urldecode($url . '?' . $this->getOptions('paramKey') . '=' . $page);
        }
    }

    /**
     * Set options for configuring widget
     *
     * @param array|null $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->_options = array_merge($this->getDefaultOptions(), (array)$options);
        return $this;
    }

}
?>