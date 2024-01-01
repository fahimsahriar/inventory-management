<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Invoice extends Entity
{
    protected $_accessible = [
        'email' => true,
        'userid' => true,
        'date_time' => true,
    ];
}
?>