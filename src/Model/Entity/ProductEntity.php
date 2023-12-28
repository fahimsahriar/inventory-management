<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Product extends Entity
{
    protected $_accessible = [
        'name' => true,
        'categories' => true,
        'description' => true,
        'quantity' => true,
        'status' => true,
        'deleted' => true,
    ];
}