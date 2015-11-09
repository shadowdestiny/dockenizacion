<?php

namespace EuroMillions\sharecomponents\widgets;

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Simple as ViewSimple;

class PaginationWidget extends \Phalcon\Mvc\User\Component {


    CONST PAGINATOR_VIEW = '_elements/';

    public $total = 1;
    public $page = 1;
    public $before;
    public $next;
    public $last;
    public $limit = 20;
    public $num_links = 10;
    public $url = '';
    public $text = 'Showing from {start} to {end} of {total} ({pages} Page{s})';
    public $text_first = '|&lt;';
    public $text_last = '&gt;|';
    public $text_next = '&gt;';
    public $text_prev = '&lt;';
    public $style_links = 'links';
    public $style_results = 'results';
    protected $_options = [];
    private $paginator;

    public function __construct(\Phalcon\Paginator\AdapterInterface $paginator, $queryArray, $options = []){

        $paginatorObj = $paginator->getPaginate();
        $this->paginator = $paginator;
        $this->total = $paginator->getPaginate()->total_items;
        $this->limit = $paginator->getLimit();
        $this->page = $paginator->getPaginate()->current;
        $this->before       = $paginatorObj->before;
        $this->next         = $paginatorObj->next;
        $this->last         = $paginatorObj->last;

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
    }

    public function render() {
        $total = $this->total;
        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }
        if (!(int)$this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }
        $num_links = $this->num_links;
        $num_pages = ceil($total / $limit);


        $pagination = [
            'limit' => $this->limit,
            'count' => $this->total_items,
            'total' => $this->total_pages,
            'current' => $this->page,
            'next_num' => $this->next,
            'next_url' => $this->getLink($this->next),
            'prev_num' => $this->before,
            'prev_url' => $this->getLink($this->before),
            'first_num' => 1,
            'first_url' => $this->getLink(1),
            'last_num' => $this->last,
            'last_url' => $this->getLink($this->last),
            'pages' => [],
            'isFirst' => 1 == $this->page,
            'isLast' => $this->last == $this->page
        ];

        $params['pagination'] = $pagination;
        $this->getView();
        try {

            return $this->getView()->render('_elements/pagination.phtml', $params);
        } catch (\Exception $exc) {
            $tmpView = __DIR__.'/pagination.phtml';
            echo "Please copy file $tmpView to ". $this->getView()->getViewsDir() . self::PAGINATOR_VIEW;
            return;
        }
        /*$output = '';

        if ($page > 1) {
            $output .= ' <a href="' . str_replace('{page}', 1, $this->url) . '">' . $this->text_first . '</a> <a href="' . str_replace('{page}', $page - 1, $this->url) . '" rel="prev">' . $this->text_prev . '</a> ';
        }
        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);
                $end = $page + floor($num_links / 2);
                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }
                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }
            if ($start > 1) {
                $output .= ' .... ';
            }
            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= ' <b>' . $i . '</b> ';
                } else {
                    $output .= ' <a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a> ';
                }
            }
            if ($end < $num_pages) {
                $output .= ' .... ';
            }
        }

        if ($page < $num_pages) {
            $output .= ' <a href="' . str_replace('{page}', $page + 1, $this->url) . '" rel="next">' . $this->text_next . '</a> <a href="' . str_replace('{page}', $num_pages, $this->url) . '">' . $this->text_last . '</a> ';
        }

        $find = array(
            '{start}',
            '{end}',
            '{total}',
            '{pages}',
            '{s}'
        );

        $replace = array(
            ($total) ? (($page - 1) * $limit) + 1 : 0,
            ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
            $total,
            $num_pages,
            $num_pages > 1 ? 's' : ''
        );

        $str = ($output ? '<div class="' . $this->style_links . '">' . $output . '</div>' : '');

        $str1 = str_replace('<div class="links">', '<ul class="pagination">', $str);
        $str2 = str_replace('</div>', '</ul>', $str1);
        $str3 = str_replace('<a', '<li><a', $str2);
        $str4 = str_replace('</a>', '</a></li>', $str3);
        $str5 = str_replace('<b>', '<li class="active"><a>', $str4);
        $str6 = str_replace('</b>', '<span class="sr-only">(current)</span></a></li>', $str5);
        $str7 = str_replace('&gt;|', '<i class="fa fa-angle-double-right"></i>', $str6);
        $str8 = str_replace('&gt;', '<i class="fa fa-angle-right"></i>', $str7);
        $str9 = str_replace('|&lt;', '<i class="fa fa-angle-double-left"></i>', $str8);
        $str10 = str_replace('&lt;', '<i class="fa fa-angle-left"></i>', $str9);
        $str11 = str_replace('....', '<li class="disabled"><span>....</span></li>', $str10);

        $view = $str11;
        if(!$output) {
            $view = '';
        }


        return self::arrayToObject([
            'total' => $total,
            'view' => $view,
            'text' => '<div class="' . $this->style_results . '">' . str_replace($find, $replace, $this->text) . '</div>'
        ]);*/

    }

    static function arrayToObject($data){
        $obj = new \stdClass();
        foreach($data as $key => $val){
            $obj->{$key} = $val;
        }
        return $obj;
    }

    public function getLast() {
        $total = $this->total;
        $end = 1;
        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }
        if (!(int)$this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }
        $num_links = $this->num_links;
        $num_pages = ceil($total / $limit);
        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);
                $end = $page + floor($num_links / 2);
                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }
                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }
        }
        return $end;
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
}
?>