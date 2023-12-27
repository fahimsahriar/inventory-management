<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class HeaderDataComponent extends Component
{
    public function getData()
    {
        $table = TableRegistry::getTableLocator()->get('Notifications');
        $data = $table->find('list', ['conditions' => ['unread' => 0]])->toArray();
        return $data;
    }
}
?>