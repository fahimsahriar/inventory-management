<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

class ProductsController extends AppController
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
    //add product
    public function add()
    {
        //category selection based on user
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Categories->find('list',[
            'conditions' => ['deleted' => Configure::read('not_deleted'),
                            'userid' => $loggedInUser['User']['id'],
                            'status' => 1,
                            ],
        ]);
        $categories = $query->toArray();
        $this->set(compact('categories'));

        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {

            $productdata = $this->request->getData();
            $product = $this->Products->patchEntity($product, $productdata);

            $loggedInUser = $this->request->getSession()->read('Auth');
            $product['userid'] = $loggedInUser['User']['id'];

            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['controller' => 'Products', 'action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $this->set(compact('product'));
    }
    //list view
    public function index()
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Products->find('all', [
            'contain' => ['Categories'],
            'conditions' => ['Products.deleted' => 0, 'Products.userid' => $loggedInUser['User']['id']],
        ]);
        
        $products = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('products'));
        $userData =  $loggedInUser['User'];
        $this->set(compact('userData'));
    }
    //single user view
    public function view($id = null)
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
    public function edit($id = null)
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $categories = $this->Categories->find('list',[
            'conditions' => ['deleted' => Configure::read('not_deleted'),
                            'userid' => $loggedInUser['User']['id'],
                            'status' => 1,
                            ],
        ]);
        $this->set(compact('categories'));

        $product = $this->Products->get($id, [
            'contain' => ['Categories'],
        ]);

        //user varification
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $product['userid']){
            $this->Flash->error(__('You are not permited to edit'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            //checking that quantitiy of a product is changing or not
            $formData = $this->request->getData();
            if($product['quantity']!=$formData['quantity']){
                $notification = $this->Notifications->newEmptyEntity();
                $notification['productid'] = $product['id'];
                $notification['userid'] = $loggedInUser['User']['id'];
                $notification['description'] = 'Previous quantity was '.$product['quantity'].', and updated quantity is '.$formData['quantity'];
                $notification['previous_quantity'] = $product['quantity'];
                $notification['current_quantity'] = $formData['quantity'];
                $notification['date_time'] = new \DateTime();
                if ($this->Notifications->save($notification)) {
                    $this->Flash->success(__('The product quantity updated.'));
                }else{
                    $this->Flash->error(__('The product quantity could not be updated. Please, try again.'));
                }
            }
            $product = $this->Products->patchEntity($product, $formData);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $this->set(compact('product'));
    }
    public function delete($id = null)
    {
        $this->autoRender = false;
        
        //user varification
        $product = $this->Products->get($id, [
            'contain' => ['Categories'],
        ]);
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $product['userid']){
            $this->Flash->error(__('You are not permited to edit'));
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $product->deleted = Configure::read('deleted');
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Products->save($product)) {
                $this->Flash->warning(__('The product has been deleted'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be deleted. Please, try again'));
        }
    }
}