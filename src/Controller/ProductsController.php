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
    //user add and registration
    public function add()
    {
        $categories = $this->Categories->find('list')->where(['deleted' => 0]);
        $this->set(compact('categories'));
        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {
            $productdata = $this->request->getData();
            $product = $this->Products->patchEntity($product, $productdata);
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
        $query = $this->Products->find('all', [
            'contain' => ['Categories'],
            'conditions' => ['Products.deleted' => Configure::read('not_deleted')],
        ]);
        
        $products = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('products'));
        $userData = $this->Auth->user();
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
        $categories = $this->Products->Categories->find('list')->where(['deleted' => Configure::read('not_deleted')]);
        $this->set(compact('categories'));
        $userData = $this->Auth->user();
        $product = $this->Products->get($id, [
            'contain' => ['Categories'],
        ]);
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
        $userData = $this->Auth->user();
        if($userData['role']==Configure::read('admin'))
        {
            $this->Flash->error(__('You are not able to edit'));
            return $this->redirect(['action' => 'index']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        $product->deleted = Configure::read('not_deleted');
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Products->save($product)) {
                $this->Flash->warning(__('The product has been deleted'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be deleted. Please, try again'));
        }
    }
}