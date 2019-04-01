<?php

namespace App\Models;

use App\Core\BaseModel;


/**
 * Class Book
 * @property int id
 * @property string name
 * @property string description
 * @property PairIdName[] authors
 * @property PairIdName[] genres
 * @property double price
 */
class Book extends BaseModel
{
    protected $id;
    protected $name;
    protected $description;
    protected $authors;
    protected $genres;
    protected $price;
}