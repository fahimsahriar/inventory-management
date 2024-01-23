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
            'conditions' => ['Invoices.userid' => $loggedInUser['User']['id'], 'Invoices.deleted' => Configure::read('not_deleted')],
        ]);
        
        $invoices = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('invoices'));
        $userData =  $loggedInUser['User'];
        $this->set(compact('userData'));
    }
    public function add($editflag = null){
        $loggedInUser = $this->request->getSession()->read('Auth'); // get id of currently logged in user
        $products = $this->Products->find('list', [
            'keyField' => 'id', 
            'valueField' => 'name',
            'conditions' => ['userid' => (int)$loggedInUser['User']['id'], 'Products.status' => Configure::read('active')] // add condition
        ]);
        $this->set(compact('products'));
    }
    public function addnew()
    {
        $loggedInUser = $this->request->getSession()->read('Auth'); // get id of currently logged in user
        $products = $this->Products->find('list', [
            'keyField' => 'id', 
            'valueField' => 'name',
            'conditions' => ['userid' => (int)$loggedInUser['User']['id'], 'Products.status' => Configure::read('active')] // add condition
        ]);
        $this->set(compact('products'));
    }
    public function products(){
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Products->find('all', [
            'contain' => ['Categories'],
            'conditions' => ['Products.deleted' => Configure::read('not_deleted'),
                            'Products.userid' => $loggedInUser['User']['id'],
                            'Products.status' => 1,
                            ]
        ]);
        
        $products = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('products'));
    }
    public function productsforexistinginvoice($invoiceId = null){
        $loggedInUser = $this->request->getSession()->read('Auth');
        $query = $this->Products->find('all', [
            'contain' => ['Categories'],
            'conditions' => ['Products.deleted' => Configure::read('not_deleted'), 'Products.userid' => $loggedInUser['User']['id']]
        ]);
        
        $products = $this->paginate($query, [
            'limit' => Configure::read('limit'),
        ]);

        $this->set(compact('products'));
        $this->set(compact('invoiceId'));
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
            $indexTracker = 0;
            foreach($cart as $index => $product) {
                if($indexTracker == $selected) {
                    $cart[$index]['quantity'] = $productdata['quantity'];
                    break;
                }
                $indexTracker++;
            }
            $session->write('Cart', $cart);
            return $this->redirect(['action' => 'add']);

        }
    }
    public function addtocartforedit($id = null, $invoiceId = null)
    {
        $loggedInUser = $this->request->getSession()->read('Auth');
        $product = $this->Products->get($id, [
            'contain' => ['Categories'],
        ]);


        $this->set(compact('product'));

        if ($this->request->is(['patch', 'post', 'put'])) {
            //getting product information from the form
            $productdata = $this->request->getData();

            $product_id = $productdata['product_id'];
            $quantity = (int)$productdata['quantity'];

            $session = $this->request->getSession();
            // Check if 'Cart' session exists
            if (!$session->check('Cart2')) {
                $session->write('Cart2', []);  // Initialize an empty array if not existed yet
            }
        
            // Read current cart data
            $cart = $session->read('Cart2');
        
            // Append new product into cart data
            $cart[$product_id] = $quantity;
            
            // Update the session value
            $session->write('Cart2', $cart);

            //adding product to the database
            $invoiceProduct = $this->SelectedProducts->newEmptyEntity();
            $invoiceProduct['invoice_id'] = $invoiceId;
            $invoiceProduct['product_id'] = $product_id;
            $invoiceProduct['quantity'] = $quantity;
            $this->SelectedProducts->save($invoiceProduct);

            return $this->redirect(['action' => 'editinvoice',$invoiceId, Configure::read('editflag')]);

        }
    }
    public function editcartforeditinginvoice($selected = null, $invoiceId = null)
    {
        $session = $this->request->getSession();
        $cart = $session->read('Cart2');
        $product = $this->Products->get($selected, [
            'contain' => ['Categories'],
        ]);
        $this->set(compact('product'));
        $this->set(compact('selected'));
        $this->set(compact('invoiceId'));

        if ($this->request->is(['patch', 'post', 'put'])) {
            $productdata = $this->request->getData();
            $cart[$selected] = (int)$productdata["quantity"];
            $session->write('Cart2', $cart);
            return $this->redirect(['action' => 'editinvoice', $invoiceId, Configure::read('editflag')]);

        }
    }
    public function remove($product_id = null, $invoiceId = null)
    {
        $this->autoRender = false;
        $session = $this->request->getSession();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $session = $this->request->getSession();

            // Read current cart data
            $cart = $session->read('Cart2');

            // Check if the product ID exists in the cart
            if (isset($cart[$product_id])) {
                // Unset the product from the cart data
                unset($cart[$product_id]);

                // Update the session value
                $session->write('Cart2', $cart);

            } else {
                echo "Product with ID $product_id not found in the cart.";
                die;
            }
            return $this->redirect(['action' => 'editinvoice', $invoiceId, Configure::read('editflag')]);

        }
    }
    public function view($id = null)
    {
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Users'],
        ]);

        $query = $this->SelectedProducts->find('all', [
            'contain' => ['Products'],
            'conditions' => ['SelectedProducts.invoice_id' => $invoice->id],
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
            'conditions' => ['SelectedProducts.invoice_id' => $invoice->id],
        ]);
        $products = $query->toArray();

        $session = $this->request->getSession();
        if($editflag != Configure::read('editflag')){
            //if it this page start for the first time
            $session->write('Cart2', []);
            foreach ($products as $product) {
                $product_id = $product->product->id;
                $quantity = $product->quantity;
            
                // Read current cart data
                $cart = $session->read('Cart2');
            
                // Update or add the product to the cart data
                $cart[$product_id] = $quantity;
            
                // Update the session value
                $session->write('Cart2', $cart);
            }
        }
        if ($this->request->is(['post', 'put'])) {
            $previousQuantity = [];
            $updatedQuantity = [];
            foreach ($products as $product) {
                $processedProduct = $this->Products->get($product->product->id);
        
                $table = $this->getTableLocator()->get('SelectedProducts');

                // Example conditions: find a row where the 'product_id' and 'invoice_id' columns match the desired values
                $conditions = [
                    'product_id' => (int)$product->product->id,
                    'invoice_id' => (int)$invoice->id
                ];

                // Using the find method to retrieve the row based on the conditions
                $previousSelectedProduct = $table->find()->where($conditions)->first();

                if ($previousSelectedProduct) {
                    // Row found, doing something with $previousSelectedProduct
                    $processedProduct['quantity'] = $processedProduct['quantity'] + $previousSelectedProduct['quantity'];
                    $previousQuantity[$processedProduct['id']] = $processedProduct['quantity'];
                    $this->Products->save($processedProduct);
                } else {
                    // No matching row found
                    $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->SelectedProducts->deleteAll(['SelectedProducts.invoice_id' => $invoice->id]);
            // Reading the current cart data
            $cart = $session->read('Cart2');
            foreach($cart as $product_id => $quantity) {
                //product adjusting
                $processedProduct = $this->Products->get($product_id);
                $processedProduct['quantity'] = $processedProduct['quantity'] - $quantity;
                $saved_products = $this->Products->save($processedProduct);

                //notification update
                $updatedQuantity[$saved_products['id']] = $saved_products['quantity'];
                $notification = $this->Notifications->newEmptyEntity();
                $notification['previous_quantity'] = $previousQuantity[$saved_products['id']];
                $notification['current_quantity'] = $updatedQuantity[$saved_products['id']];
                $notification['productid'] = $saved_products['id'];
                $notification['userid'] = $loggedInUser['User']['id'];
                $notification['description'] = 'Previous quantity was '.$notification['previous_quantity'].', and updated quantity is '.$notification['current_quantity'];
                $notification['date_time'] = new \DateTime();
                $this->Notifications->save($notification);

                $invoice_product = $this->SelectedProducts->newEmptyEntity();
                $invoice_product['invoice_id'] = $invoice->id;
                $invoice_product['product_id'] = $product_id;
                $invoice_product['quantity'] = $quantity;
                $savedSelectedProduct = $this->SelectedProducts->save($invoice_product);

                if($savedSelectedProduct){}else{
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
            'conditions' => ['SelectedProducts.invoice_id' => $id],
        ]);
        $products = $query->toArray();
        
        $mailer = new \App\Mailer\InvoiceMailer();
        
        try {
            $mailer->send('invoice', [$invoice, $products]);
            $this->Flash->success(__('The invoice was sent to your email.'));
        } catch (\Exception $e) {
            // Log the error if necessary
            // Log::error($e->getMessage());
    
            $this->Flash->error(__('Error sending the invoice email. Please try again later.'));
        }
    
        return $this->redirect(['action' => 'index']);
    }    
    public function getQuantity($id = null)
    {
        $this->autoRender = false; // We don't render a view in this case
    
        if($id) {
            $product = $this->Products->get($id);
            echo $product->quantity;
        } else {
            echo 'not found';
        }
    }
    public function storeInSession()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            //getting product information from the form
            $productId = $this->request->getData('product_id');
            $quantity = $this->request->getData('quantity');

            $product = ['id' => $productId, 'quantity' => $quantity];

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
        // if ($this->request->is('post')) {
        //     $productId = $this->request->getData('product_id');
        //     $quantity = $this->request->getData('quantity');
            
        //     // store in session
        //     $this->request->getSession()->write('Product.' . $productId, $quantity);
        // }
    }

    public function show(){
        $this->autoRender = false;
        $products = json_decode($this->request->getData('products'), true);
        var_dump($products);
        var_dump($this->request->getData('products'));
    }
    public function addnewtwo(){
        $user_id = $this->Auth->user('id'); // get id of currently logged in user
        $products = $this->Products->find('list', [
            'keyField' => 'id', 
            'valueField' => 'name',
            'conditions' => ['userid' => $user_id] // add condition
        ]);
        $this->set(compact('products'));
    }
    public function makeInvoice()
    {
        $this->autoRender = false;
        $inputArray = $this->request->getData();
        $outputArray = array();

        for($i = 0; $i < count($inputArray["product_id"]); $i++){
            $outputArray[] = array(
                "id" => (string) $inputArray["product_id"][$i],
                "quantity" => (string) $inputArray["quantity"][$i]
            );
        }

        $data = $outputArray;

        //saving the invoice
        $loggedInUser = $this->request->getSession()->read('Auth');
        $loggedInUser = $loggedInUser['User'];
        
        $invoice = $this->Invoices->newEmptyEntity();
        $invoice['email'] = $loggedInUser['email'];
        $invoice['userid'] = $loggedInUser['id'];
        $invoice['created_at'] = new \DateTime();

        $entity = $this->Invoices->save($invoice);
        if ($entity) {
            $lastInsertedId = $entity->id;
            foreach($data as $index => $product) {
                $invoiceProduct = $this->SelectedProducts->newEmptyEntity();
                $invoiceProduct['invoice_id'] = $lastInsertedId;
                $invoiceProduct['product_id'] = $product['id'];
                $invoiceProduct['quantity'] = $product['quantity'];
    
                if($this->SelectedProducts->save($invoiceProduct)){
                    $processedProduct = $this->Products->get($product['id']);
                    // notification module
                    $notification = $this->Notifications->newEmptyEntity();
                    $notification['previous_quantity'] = $processedProduct['quantity'];
                    $processedProduct['quantity'] = $processedProduct['quantity'] - $product['quantity'];
                    $notification['current_quantity'] = $processedProduct['quantity'];
                    $notification['productid'] = $product['id'];
                    $notification['userid'] = $loggedInUser['id'];
                    $notification['description'] = 'Previous quantity was '.$notification['previous_quantity'].', and updated quantity is '.$notification['current_quantity'];
                    $notification['date_time'] = new \DateTime();
                    //updating product
                    $this->Products->save($processedProduct);
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
            return $this->redirect(['action' => 'index']);
        }else{
            $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
        }

        $this->set(compact('data'));
        $this->render('new_page');
    }
}