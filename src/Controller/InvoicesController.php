<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

class InvoicesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel("Invoices");
        $this->loadModel("Categories");
        $this->loadModel("Notifications");
        $this->loadModel("Products");
        $this->loadModel("InvoicedProducts");
    }
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    public function index(){
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Invoices->find('all', [
            'contain' => ['Users'],
            'conditions' => ['Invoices.userid' => $loggedInUser['User']['id'], 'Invoices.deleted' => 0,],
        ]);
        
        $invoices = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('invoices'));
        $userData =  $loggedInUser['User'];
        $this->set(compact('userData'));
    }
    public function add(){
        $invoice = $this->Invoices->newEmptyEntity();
        $loggedInUser = $this->request->getSession()->read('Auth');
        $loggedInUser = $loggedInUser['User'];
        $this->set(compact('loggedInUser'));
        $this->set(compact('invoice'));

        $session = $this->request->getSession();
        $session->write('email', $loggedInUser['email']);
        $session->write('userid', $loggedInUser['id']);

        $invoice['email'] = $loggedInUser['email'];
        $invoice['userid'] = $loggedInUser['id'];
        $invoice['created_at'] = new \DateTime();

        if ($this->request->is('post')) {
            $entity = $this->Invoices->save($invoice);
            if ($entity) {
                $lastInsertedId = $entity->id;
                $session = $this->request->getSession();
                // Read the current cart data
                $cart = $session->read('Cart');
                foreach($cart as $index => $product) {
                    $invoice_product = $this->InvoicedProducts->newEmptyEntity();
                    $invoice_product['invoiceid'] = $lastInsertedId;
                    $invoice_product['productid'] = $product['id'];
                    $invoice_product['quantity'] = $product['quantity'];

                    if($this->InvoicedProducts->save($invoice_product)){}else{
                        $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
                        return $this->redirect(['action' => 'index']);
                    }
                }

                $this->Flash->success(__('The invoice saved.'));
                $session->delete('Cart');
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
    }
    public function products(){
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Products->find('all', [
            'contain' => ['Categories'],
            'conditions' => ['Products.deleted' => 0],
        ]);
        
        $products = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('products'));
    }
    public function addtocart($id = null)
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $product = $this->Products->get($id, [
            'contain' => ['Categories'],
        ]);


        $this->set(compact('product'));

        if ($this->request->is(['patch', 'post', 'put'])) {
            //getting product information from the form
            $productdata = $this->request->getData();

            $product = ['id' => $productdata['product_id'], 'quantity' => $productdata['quantity']];

            $session = $this->request->getSession();
            // Check if 'Cart' session exists
            if (!$session->check('Cart')) {
                $session->write('Cart', []);  // Initialize an empty array if not existed yet
            }
        
            // Read current cart data
            $cart = $session->read('Cart');
        
            // Append new product into cart data
            $cart[] = $product;
            
            // Update the session value
            $session->write('Cart', $cart);

            return $this->redirect(['action' => 'add']);

        }
    }
    public function editcart($selected = null)
    {
        $session = $this->request->getSession();
        $cart = $session->read('Cart');
        $product = $this->Products->get($cart[$selected]['id'], [
            'contain' => ['Categories'],
        ]);
        $this->set(compact('product'));
        $this->set(compact('selected'));

        if ($this->request->is(['patch', 'post', 'put'])) {
            $productdata = $this->request->getData();

            // Modify 
            $index_here = 0;
            foreach($cart as $index => $product) {
                if($index_here == $selected) {
                    $cart[$index]['quantity'] = $productdata['quantity'];
                    break;
                }
                $index_here++;
            }
            $session->write('Cart', $cart);
            return $this->redirect(['action' => 'add']);

        }
    }
    public function remove($selected = null)
    {
        $this->autoRender = false;
        $session = $this->request->getSession();
        $cart = $session->read('Cart');
        $product = $this->Products->get($cart[$selected]['id'], [
            'contain' => ['Categories'],
        ]);
        $this->set(compact('product'));

        if ($this->request->is(['patch', 'post', 'put'])) {
            $session = $this->request->getSession();

            // Read the current cart data
            $cart = $session->read('Cart');
        
            // Loop through each product to find and remove
            $index_here = 0;
            foreach($cart as $index => $product) {
                if($index_here == $selected) {
                    unset($cart[$index]); // Remove from the array
                    break;
                }
                $index_here++;
            }
        
            //Re-index the array just in case
            $cart = array_values($cart);
            
            // Update the session value with the new cart
            $session->write('Cart', $cart);
            return $this->redirect(['action' => 'add']);

        }
    }
    public function view($id = null)
    {
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Users'],
        ]);

        $query = $this->InvoicedProducts->find('all', [
            'contain' => ['Products'],
            'conditions' => ['InvoicedProducts.invoiceid' => $invoice->id],
        ]);
        $products = $query->toArray();

        $this->set(compact('invoice'));
        $this->set(compact('products'));
    }
    public function delete($id = null)
    {
        $this->autoRender = false;
        
        //user varification
        $invoice = $this->Invoices->get($id);
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $invoice['userid']){
            $this->Flash->error(__('You are not permited to delet'));
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $invoice->deleted = Configure::read('deleted');
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Invoices->save($invoice)) {
                $this->Flash->warning(__('The product has been deleted'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be deleted. Please, try again'));
        }
    }
    public function editinvoice(){
        $invoice = $this->Invoices->newEmptyEntity();
        $loggedInUser = $this->request->getSession()->read('Auth');
        $loggedInUser = $loggedInUser['User'];
        $this->set(compact('loggedInUser'));
        $this->set(compact('invoice'));

        $session = $this->request->getSession();
        $session->write('email', $loggedInUser['email']);
        $session->write('userid', $loggedInUser['id']);

        $invoice['email'] = $loggedInUser['email'];
        $invoice['userid'] = $loggedInUser['id'];
        $invoice['created_at'] = new \DateTime();

        if ($this->request->is('post')) {
            $entity = $this->Invoices->save($invoice);
            if ($entity) {
                $lastInsertedId = $entity->id;
                $session = $this->request->getSession();
                // Read the current cart data
                $cart = $session->read('Cart');
                foreach($cart as $index => $product) {
                    $invoice_product = $this->InvoicedProducts->newEmptyEntity();
                    $invoice_product['invoiceid'] = $lastInsertedId;
                    $invoice_product['productid'] = $product['id'];
                    $invoice_product['quantity'] = $product['quantity'];

                    if($this->InvoicedProducts->save($invoice_product)){}else{
                        $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
                        return $this->redirect(['action' => 'index']);
                    }
                }

                $this->Flash->success(__('The invoice saved.'));
                $session->delete('Cart');
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
            }
        }
    }
}