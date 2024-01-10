<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class SelectedProduct extends Entity
{
    protected $_accessible = [
        'invoice_id' => true,
        'product_id' => true,
        'quantity' => true,
    ];
}
?>