<?php

namespace App\Controller;

use App\Controller\AppController;

class CategoriesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel("Categories");
    }
    //user categories
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        if ($this->request->is('post')) {
            $categorydata = $this->request->getData();
            $category = $this->Categories->patchEntity($category, $categorydata);

            $loggedInUser = $this->request->getSession()->read('Auth');
            $category['userid'] = $loggedInUser['User']['id'];

            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller' => 'Categories', 'action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('category'));
    }
    //list view
    public function index()
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Categories->find('all', [
            'conditions' => ['deleted' => 0, 'userid' => $loggedInUser['User']['id']],
        ]);
        $categories = $this->paginate($query, [
            'limit' => 6,
        ]);

        $this->set(compact('categories'));
        $userData = $this->Auth->user();
        $this->set(compact('userData'));
    }
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => [],
        ]);

        //user varification
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $category['userid']){
            $this->Flash->error(__('You are not permited to edit'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $this->set(compact('category'));
    }
    public function delete($id = null)
    {
        $this->autoRender = false;
        $category = $this->Categories->get($id, [
            'contain' => [],
        ]);
        //user varification
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $category['userid']){
            $this->Flash->error(__('You are not permited to edit'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        $category->deleted = 1;
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Categories->save($category)) {
                $this->Flash->warning(__('The category has been deleted'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be deleted. Please, try again'));
        }
    }
}