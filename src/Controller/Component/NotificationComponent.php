<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class NotificationComponent extends Component
{
    public function makeNotification($previous_quantity, $current_qunantity, $product_id, $user_id)
    {
        // Load the model
        $this->Notifications = TableRegistry::getTableLocator()->get('Notifications');
        
        $notification = $this->Notifications->newEmptyEntity();
        $notification['previous_quantity'] = $previous_quantity;
        $notification['current_quantity'] = $current_qunantity;
        $notification['productid'] = $product_id;
        $notification['userid'] = $user_id;
        $notification['description'] = 'Previous quantity was '.$notification['previous_quantity'].', and updated quantity is '.$notification['current_quantity'];
        $notification['date_time'] = new \DateTime();
        $this->Notifications->save($notification);
    }
}

?>