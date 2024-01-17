<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

class InvoicesController extends AppController
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

    public function index(){
        $this->autoRender = false;
        echo "hellO";
    }

}