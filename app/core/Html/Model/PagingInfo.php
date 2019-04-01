<?php

namespace App\Core\Html\Model;

use App\Core\BaseModel;

/**
 * Class PagingInfo
 * @property int totalItems
 * @property int itemsPerPage
 * @property int currentPage
 * @property int totalPages
 */
class PagingInfo extends BaseModel
{
    protected $totalItems;
    protected $itemsPerPage;
    protected $currentPage;

    public function __get($name)
    {
        return ($name == 'totalPages') ? $this->getTotalPages() : parent::__get($name);
    }

    private function getTotalPages()
    {
        return ceil($this->totalItems / $this->itemsPerPage);
    }

}