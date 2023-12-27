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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('productid')
            ->requirePresence('productid', 'create')
            ->notEmptyString('productid');

        $validator
            ->integer('userid')
            ->requirePresence('userid', 'create')
            ->notEmptyString('userid');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->integer('previous')
            ->requirePresence('previous', 'create')
            ->notEmptyString('previous');

        $validator
            ->integer('current')
            ->requirePresence('current', 'create')
            ->notEmptyString('current');

        $validator
            ->integer('unread')
            ->requirePresence('unread', 'create')
            ->notEmptyString('unread');

        return $validator;
    }
}
?>