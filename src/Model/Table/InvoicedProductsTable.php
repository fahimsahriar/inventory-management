<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InvoicedProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tbl_selectedproducts');

        $this->belongsTo('Invoices', [
            'foreignKey' => 'userid',
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'productid',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->integer('invoiceid', 'The value must be an integer')
                ->notEmpty('invoiceid')
                ->integer('productid', 'The value must be an integer')
                ->notEmpty('productid')
                ->integer('quantity', 'The value must be an integer')
                ->notEmpty('quantity');
        return $validator;
    }
}