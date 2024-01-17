<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

class NotificationsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel("Products");
        $this->loadModel("Categories");
        $this->loadModel("Notifications");
    }
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
    }
    //list view
    public function index()
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Notifications->find('all', [
            'contain' => ['Products'],
            'conditions' => ['Notifications.userid' => $loggedInUser['User']['id']],
            'order' => ['Notifications.date_time' => 'DESC'],
        ]);
        
        $notifications = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('notifications'));
        $userData =  $loggedInUser['User'];
        $this->set(compact('userData'));
    }
    //single user view
    public function view($id = null)
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post', 'delete']);
        //user varification
        $notification = $this->Notifications->get($id, [
            'contain' => ['Products'],
        ]);
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $notification['userid']){
            $this->Flash->error(__('You are not permited to see'));
            return $this->redirect(['action' => 'index']);
        }

        $notification->unread = Configure::read('read');
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Notifications->save($notification)) {

                return $this->redirect(['action' => 'productview', $notification->productid]);
            }
            $this->Flash->error(__('The product could not be deleted. Please, try again'));
        }
    }
    public function productview($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Categories'],
        ]);

        //for notification
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Notifications->find('all', [
            'contain' => ['Products'],
            'conditions' => ['Notifications.productid' => $product->id],
        ]);
        $notifications = $query->toArray();

        $this->set(compact('product'));
        $this->set(compact('notifications'));
    }
}