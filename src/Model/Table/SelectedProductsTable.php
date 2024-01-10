<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SelectedProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tbl_selectedproducts');

        $this->belongsTo('Invoices', [
            'foreignKey' => 'userid',
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->integer('invoice_id', 'The value must be an integer')
                ->notEmpty('invoice_id')
                ->integer('product_id', 'The value must be an integer')
                ->notEmpty('product_id')
                ->integer('quantity', 'The value must be an integer')
                ->notEmpty('quantity');
        return $validator;
    }
}