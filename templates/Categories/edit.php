<?php
use Cake\Core\Configure;
$this->Flash->render();
$loggedInUser = $this->request->getSession()->read('Auth');
?>
<div class="row">
    <div class="column-responsive">
        <div class="categories form content">
            <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right']) ?>
            <?= $this->Form->create($category) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    $role = $loggedInUser['User']['role'];
                    if($role == Configure::read('super_admin')){
                        echo $this->Form->control('status', [
                            'options' => [
                                'active' => 'active',
                                'inactive' => 'inactive'
                            ],
                            'empty' => '(choose status)'
                        ]);
                    }
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>