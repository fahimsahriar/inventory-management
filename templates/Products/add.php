<?php
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
            <?= $this->Form->create($product) ?>
            <fieldset>
                <legend><?= __('Add product') ?></legend>
                <?= $this->Form->control('name', ['type' => 'text', 'label' => 'Name']) ?>
                <?= $this->Form->control('description', ['type' => 'text', 'label' => 'Description']) ?>
                <?= $this->Form->control('categories', ['options' => $categories, 'empty' => 'Select a category']) ?>
                <?= $this->Form->control('quantity', ['type' => 'number', 'label' => 'Quantity']) ?>
                <?= $this->Form->control('status', [
                                'options' => [
                                    'active' => 'active',
                                    'inactive' => 'inactive'
                                ],
                                'empty' => '(choose status)'
                            ]) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
