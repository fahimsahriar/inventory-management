<?php
use Cake\Core\Configure;
$this->Flash->render();
$loggedInUser = $this->request->getSession()->read('Auth');
?>
<div class="row">
    <div class="column-responsive mb-4">
        <div class="form content">
            <?php
                if($loggedInUser){
                    echo $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right']);
                }else{
                    echo $this->Html->link(__('Back'), ['controller' => 'Pages','action' => 'display'], ['class' => 'button float-right']);
                }
            ?>
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('password');
                    echo $this->Form->control('email');
                    echo $this->Form->control('phone');
                    echo $this->Form->control('address');
                    echo $this->Form->control('zip_code');
                    if($loggedInUser){
                        $role = $loggedInUser['User']['role'];
                        if($role == Configure::read('super_admin')){
                            echo $this->Form->control('role', [
                                'options' => [
                                    '0' => 'Admin',
                                    '1' => 'Super Admin'
                                ],
                                'empty' => '(choose role)'
                            ]);
                        }
                    }
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
