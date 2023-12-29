<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CategoriesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tbl_categories');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->notEmptyString('name', 'Please provide category name')
                ->integer('status', 'Please select a category status');
        return $validator;
    }
}