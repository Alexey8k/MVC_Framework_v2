<?php

namespace App\Models;


use App\Core\BaseModel;

/**
 * Class PaginateModel
 * @package App\Models
 * @property PartialBook[] books
 * @property int totalItems
 */
class PaginateModel extends BaseModel
{
    protected $books;
    protected $totalItems;
}