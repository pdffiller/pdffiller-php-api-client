<?php
namespace PDFfiller\OAuth2\Client\Provider\Core;

class ModelsList
{
    protected $total = 0;
    protected $current_page = 1;
    protected $per_page = 15;
    protected $prev_page_url = null;
    protected $next_page_url = null;

    protected $items = [];

    /**
     * ModelsList constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        foreach($attributes as $name => $attribute) {
            if (property_exists($this, $name)) {
                $this->{$name} = $attribute;
            }
        }
    }


    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->current_page = $currentPage;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->per_page;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->per_page = $perPage;
    }

    /**
     * @return null
     */
    public function getPrevPageUrl()
    {
        return $this->prev_page_url;
    }

    /**
     * @param null $prevPageUrl
     */
    public function setPrevPageUrl($prevPageUrl)
    {
        $this->prev_page_url = $prevPageUrl;
    }

    /**
     * @return null
     */
    public function getNextPageUrl()
    {
        return $this->next_page_url;
    }

    /**
     * @param null $nextPageUrl
     */
    public function setNextPageUrl($nextPageUrl)
    {
        $this->next_page_url = $nextPageUrl;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getList();
    }
}
