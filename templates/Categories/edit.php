<?php
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
                    echo $this->Form->control('status', [
                        'options' => [
                            '1' => 'active',
                            '0' => 'inactive'
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