<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class TblNotification extends Entity
{
    protected $_accessible = [
        'productid' => true,
        'userid' => true,
        'description' => true,
        'previous_quantity' => true,
        'current_quantity' => true,
        'unread' => true,
        'date_time' => true,
    ];
}
?>