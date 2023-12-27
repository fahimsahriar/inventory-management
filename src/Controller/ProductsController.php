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
        $categories = $this->Products->Categories->find('list',[
            'conditions' => ['deleted' => 0, 'userid' => $loggedInUser['User']['id']],
        ]);
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
            'limit' => 6,
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

        $this->set(compact('product'));
    }
    public function edit($id = null)
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $categories = $this->Products->Categories->find('list',[
            'conditions' => ['deleted' => 0, 'userid' => $loggedInUser['User']['id']],
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
            $product = $this->Products->patchEntity($product, $this->request->getData());
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
        $product = $this->Products->get($id);
        $product->deleted = 1;
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Products->save($product)) {
                $this->Flash->warning(__('The product has been deleted'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be deleted. Please, try again'));
        }
    }
}