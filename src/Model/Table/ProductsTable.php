<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tbl_products');

        $this->belongsTo('Categories', [
            'foreignKey' => 'categories',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->notEmptyString('name', 'Please enter your name.')
                ->notEmptyString('description', 'Please enter your product description.')
                ->integer('quantity', 'The value must be an integer')
                ->greaterThanOrEqual('quantity', 0, 'The value must be a positive number or zero')
                ->requirePresence('quantity', 'create')
                ->requirePresence('status', 'create')
                ->integer('status', 'The value must be an integer')
                ->requirePresence('categories', 'create')
                ->integer('quantity', 'The value must be an integer');
        return $validator;
    }
}