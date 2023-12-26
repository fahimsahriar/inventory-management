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
                ->notEmptyString('name', 'We need your name.')
                ->notEmptyString('description', 'We need your product description.')
                ->integer('quantity', 'The value must be an integer')
                ->greaterThanOrEqual('quantity', 0, 'The value must be a positive number or zero')
                ->requirePresence('quantity', 'create')
                ->notEmptyString('quantity', 'Please fill this field')
                ->requirePresence('status', 'create')
                ->notEmptyString('status', 'Please fill this field')
                ->requirePresence('categories', 'create')
                ->notEmptyString('categories', 'Please fill this field');
        return $validator;
    }
}