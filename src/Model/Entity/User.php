<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class User extends Entity
{
    protected $_accessible = [
        'name' => true,
        'password' => true,
        'phone' => true,
        'email' => true,
        'address' => true,
        'zip_code' => true,
        'role' => true,
    ];
}
