<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class InvoicedProduct extends Entity
{
    protected $_accessible = [
        'invoiceid' => true,
        'productid' => true,
        'quantity' => true,
    ];
}
?>