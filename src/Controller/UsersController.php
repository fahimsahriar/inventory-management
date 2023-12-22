<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Core\Configure;

class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }
    //user add and registration
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $userdata = $this->request->getData();
            $loggedInUser = $this->request->getSession()->read('Auth');
            if($loggedInUser){
                if($loggedInUser['User']['role'] == Configure::read('super_admin'))
                {
                    $userdata['role'] = (int)$userdata['role'];
                }
            }
            $passwordHash = new DefaultPasswordHasher();
            $userdata['password'] = $passwordHash->hash($userdata['password']);
            $user = $this->Users->patchEntity($user, $userdata);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }
}