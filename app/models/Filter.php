<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2019
 * Time: 16:05
 */

namespace App\Models;


use App\Core\BaseModel;

/**
 * Class Filter
 * @package App\Models
 * @property int genreId
 * @property int authorId
 */
class Filter extends BaseModel
{
    protected $genreId;
    protected $authorId;
}