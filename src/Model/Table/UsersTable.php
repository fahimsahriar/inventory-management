<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tbl_users');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->notEmptyString('name', 'We need your name.')
                ->scalar('name', 'Name should have only text')
                ->add('name', 'custom', [
                    'rule' => function ($value, $context) {
                        return preg_match('/^[a-zA-Z]+$/', $value) > 0;
                    },
                    'message' => 'Name can only contain letters'
                ])
                ->minLength('name', 1)
                ->notEmptyString('password', 'We need your password.')
                ->email('email', 'please enter right email')
                ->notEmptyString('email', 'We need your email.')
                ->notEmptyString('phone', 'We need your phone.')
                ->numeric('phone', 'Phone number should have only numeric value')
                ->notEmptyString('address', 'We need your address.')
                ->add('zip_code', 'validFormat', [
                    'rule' => ["custom", "/^[0-9]{5}(-[0-9]{4})?$/"],
                    'message' => 'Please enter a 5 digit valid zip code'])
                ;

        return $validator;
    }
}
