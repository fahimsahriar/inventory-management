<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InvoicesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tbl_invoices');

        $this->belongsTo('Users', [
            'foreignKey' => 'userid',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->email('email', 'please enter right email')    
                ->integer('userid', 'The value must be an integer')
                ->requirePresence('date_time', 'create')
                ->notEmpty('date_time');
        return $validator;
    }
}