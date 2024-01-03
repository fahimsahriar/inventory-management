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
        $this->loadModel("SelectedProducts");
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
    public function add($editflag = null){
        $invoice = $this->Invoices->newEmptyEntity();
        $loggedInUser = $this->request->getSession()->read('Auth');
        $loggedInUser = $loggedInUser['User'];
        $this->set(compact('loggedInUser'));
        $this->set(compact('invoice'));

        $session = $this->request->getSession();
        $session->write('email', $loggedInUser['email']);
        $session->write('userid', $loggedInUser['id']);
        if($editflag != Configure::read('editflag')){
            $session->write('Cart', []);
        }

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
                    $invoice_product = $this->SelectedProducts->newEmptyEntity();
                    $invoice_product['invoiceid'] = $lastInsertedId;
                    $invoice_product['productid'] = $product['id'];
                    $invoice_product['quantity'] = $product['quantity'];

                    if($this->SelectedProducts->save($invoice_product)){
                        $processed_product = $this->Products->get($product['id']);
                        // notification module
                        $notification = $this->Notifications->newEmptyEntity();
                        $notification['previous_quantity'] = $processed_product['quantity'];
                        $processed_product['quantity'] = $processed_product['quantity'] - $product['quantity'];
                        $notification['current_quantity'] = $processed_product['quantity'];
                        $notification['productid'] = $product['id'];
                        $notification['userid'] = $loggedInUser['id'];
                        $notification['description'] = 'Previous quantity was '.$notification['previous_quantity'].', and updated quantity is '.$notification['current_quantity'];
                        $notification['date_time'] = new \DateTime();
                        //updating product
                        $this->Products->save($processed_product);
                        if ($this->Notifications->save($notification)) {
                        }else{
                            $this->Flash->error(__('The product quantity could not be updated. Please, try again.'));
                        }
                    }else{
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

            return $this->redirect(['action' => 'add', Configure::read('editflag')]);

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
    public function editcartforeditinginvoice($selected = null, $invoiceid = null)
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
            return $this->redirect(['action' => 'editinvoice', $invoiceid, Configure::read('editflag')]);

        }
    }
    public function remove($selected = null, $invoiceid = null)
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
            return $this->redirect(['action' => 'editinvoice', $invoiceid, Configure::read('editflag')]);

        }
    }
    public function view($id = null)
    {
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Users'],
        ]);

        $query = $this->SelectedProducts->find('all', [
            'contain' => ['Products'],
            'conditions' => ['SelectedProducts.invoiceid' => $invoice->id],
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
            $this->Flash->error(__('You are not permited to delete'));
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
    public function editinvoice($id = null, $editflag = null){
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Users'],
        ]);
        $this->set(compact('invoice'));
        //User verification
        $loggedInUser = $this->request->getSession()->read('Auth');
        if($loggedInUser['User']['id'] != $invoice['userid']){
            $this->Flash->error(__('You are not permited to edit'));
            return $this->redirect(['action' => 'index']);
        }
        //getting existing products in session
        $query = $this->SelectedProducts->find('all', [
            'contain' => ['Products'],
            'conditions' => ['SelectedProducts.invoiceid' => $invoice->id],
        ]);
        $products = $query->toArray();

        $session = $this->request->getSession();
        if($editflag != Configure::read('editflag')){
            $session->write('Cart', []);  // Initialize an empty 'Cart' session
            foreach ($products as $product) {
                $session_product = ['id' => $product->product->id, 'quantity' => $product->quantity];
            
                // Read current cart data
                $cart = $session->read('Cart');
            
                // Append new product into cart data
                $cart[] = $session_product;
                
                // Update the session value
                $session->write('Cart', $cart);
            }
        }
        if ($this->request->is(['post', 'put'])) {
            $this->SelectedProducts->deleteAll(['SelectedProducts.invoiceid' => $invoice->id]);
            
            $previous_quantity = [];
            $updated_quantity = [];
            foreach ($products as $product) {
                $processed_product = $this->Products->get($product->product->id);
                $previous_quantity[$processed_product['id']] = $processed_product['quantity'];
                $processed_product['quantity'] = $processed_product['quantity'] + $product->quantity;
                $this->Products->save($processed_product);
            }
            // Read the current cart data
            $cart = $session->read('Cart');
            foreach($cart as $index => $product) {
                $invoice_product = $this->SelectedProducts->newEmptyEntity();
                $invoice_product['invoiceid'] = $invoice->id; //invoice id
                $invoice_product['productid'] = $product['id'];
                $invoice_product['quantity'] = $product['quantity'];

                //product adjusting
                $processed_product = $this->Products->get($product['id']);
                $processed_product['quantity'] = $processed_product['quantity'] - $product['quantity'];
                $saved_products = $this->Products->save($processed_product);

                //notification update
                $updated_quantity[$saved_products['id']] = $saved_products['quantity'];
                $notification = $this->Notifications->newEmptyEntity();
                $notification['previous_quantity'] = $previous_quantity[$saved_products['id']];
                $notification['current_quantity'] = $updated_quantity[$saved_products['id']];
                $notification['productid'] = $saved_products['id'];
                $notification['userid'] = $loggedInUser['User']['id'];
                $notification['description'] = 'Previous quantity was '.$notification['previous_quantity'].', and updated quantity is '.$notification['current_quantity'];
                $notification['date_time'] = new \DateTime();
                $this->Notifications->save($notification);

                $saved_selected_product = $this->SelectedProducts->save($invoice_product);

                if($saved_selected_product){}else{
                    $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->success(__('The invoice saved.'));
            $session->delete('Cart');
            return $this->redirect(['action' => 'index']);
        }
    }
    public function mailinvoice($id = null){
        $this->autoRender = false;
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Users'],
        ]);
    
        $query = $this->SelectedProducts->find('all', [
            'contain' => ['Products'],
            'conditions' => ['SelectedProducts.invoiceid' => $id],
        ]);
        $products = $query->toArray();
        $mailer = new \App\Mailer\InvoiceMailer();
        $mailer->send('invoice', [$invoice, $products]);
        $this->Flash->success(__('The invoice send in your email.'));
        return $this->redirect(['action' => 'index']);

    }
}