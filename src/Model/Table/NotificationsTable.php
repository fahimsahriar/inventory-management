<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class NotificationsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('tbl_notifications');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'productid',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'userid',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->notEmpty('id')
            ->greaterThanOrEqual('id', 0);

        $validator
            ->integer('productid')
            ->requirePresence('productid', 'create')
            ->notEmpty('productid')
            ->greaterThanOrEqual('productid', 0);

        $validator
            ->integer('userid')
            ->requirePresence('userid', 'create')
            ->notEmpty('userid')
            ->greaterThanOrEqual('userid', 0);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->integer('previous_quantity')
            ->requirePresence('previous_quantity', 'create')
            ->notEmpty('previous_quantity')
            ->greaterThanOrEqual('previous_quantity', 0);

        $validator
            ->integer('current_quantity')
            ->requirePresence('current_quantity', 'create')
            ->notEmpty('current_quantity')
            ->greaterThanOrEqual('current_quantity', 0);

        $validator
            ->integer('unread')
            ->requirePresence('unread', 'create')
            ->notEmpty('unread')
            ->greaterThanOrEqual('unread', 0);
        $validator
            ->requirePresence('date_time', 'create')
            ->notEmpty('date_time');

        return $validator;
    }
}
?>