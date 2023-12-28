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
        $this->loadModel("Users");
    }
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', "recover", "resetpassword", 'verification']);
    }
    public function login()
    {
        $user = $this->Users->newEmptyEntity();
        $this->set(compact('user'));
        if ($this->request->is("post")) {
            $userData = $this->Auth->identify($this->request->getData());
            if ($userData) {
                $this->Flash->success("Logged in");
                $this->Auth->setUser($userData);
                $this->set(compact('userData'));
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error("Invalid Login");
            }
        }
    }
    public function logout()
    {
        $this->Flash->success("Log out successfull");
        return $this->redirect($this->Auth->logout());
    }
    //user add and registration
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        $this->set(compact('user'));
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
    //list view
    public function index()
    {
        $users = $this->paginate($this->Users, [
            'limit' => 6,
        ]);

        $this->set(compact('users'));
        $userData = $this->Auth->user();
        $this->set(compact('userData'));
    }
    //single user view
    public function view($id = null)
    {
        $user = $this->Users->get($id);

        $this->set(compact('user'));
    }
    public function edit($id = null)
    {
        $userData = $this->Auth->user();
        if($userData['role']== Configure::read('admin'))
        {
            $this->Flash->error(__('You are not able to edit'));
            return $this->redirect(['action' => 'index']);
        }
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }
    public function delete($id = null)
    {
        $userData = $this->Auth->user();
        if($userData['role']==0)
        {
            $this->Flash->error(__('You are not able to edit'));
            return $this->redirect(['action' => 'index']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->warning(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function recover()
    {
        if ($this->request->is('post')) {
            $token = Security::hash(Security::randomBytes(25));
            
            
            $email = $this->request->getData('email');
            if ($email == NULL) {
                $this->Flash->error(__('Please insert your email address'));
                return;
            }

            $userTable = TableRegistry::get('Users');
            $user = $userTable->find('all')->where(['email' => $email])->first();
            if ($user) {
                $user->token = $token;
                if ($userTable->save($user)) {
                    $mailer = new Mailer('default');
                    $mailer->setTransport('smtp');
                    $mailer->setFrom(['fahim.sahriar@sjinnovation.com' => 'User management'])
                        ->setTo($email)
                        ->setEmailFormat('html')
                        ->setSubject('Forgot Password Request')
                        ->deliver('Hello<br/>Please click link below to reset your password<br/><br/><a href="http://localhost:8765/users/resetpassword/' . $token . '">Reset Password</a>');
                }
                $this->Flash->success('Reset password link has been sent to your email (' . $email . '), please check your email');
            }else {
                $this->Flash->error(__('Email is not registered in system'));
            }
        }
    }
    public function resetpassword($token)
    {
        if ($this->request->is('post')) {
            $hasher = new DefaultPasswordHasher();
            $newPass = $hasher->hash($this->request->getData('password'));

            $userTable = TableRegistry::get('Users');
            $user = $userTable->find('all')->where(['token' => $token])->first();
            $user->password = $newPass;
            if ($userTable->save($user)) {
                $this->Flash->success('Password successfully reset. Please login using your new password');
                return $this->redirect(['action' => 'login']);
            }
        }
    }
}