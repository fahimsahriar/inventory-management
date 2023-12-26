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
            <?= $this->Form->create($category) ?>
            <fieldset>
                <legend><?= __('Add category') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('status', [
                        'options' => [
                            'active' => 'active',
                            'inactive' => 'inactive'
                        ],
                        'empty' => '(choose status)'
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
