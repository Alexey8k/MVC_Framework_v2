<?php

namespace App\Models;


use App\Core\BaseModel;

/**
 * Class PartialBook
 * @package App\Models
 * @property int id;
 * @property string name
 * @property string genres
 * @property string authors
 * @property string description
 * @property double price
 */
class PartialBook extends BaseModel
{
    protected $id;
    protected $name;
    protected $genres;
    protected $authors;
    protected $description;
    protected $price;
}