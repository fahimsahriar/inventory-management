<?= $this->Flash->render() ?>
<div class="row">
    <div class="column-responsive mb-4">
        <div class="form content">
            <?php echo $this->Form->create(null, [
                'url' => [
                    "controller" => "Users",
                    "action" => "login"
                ]
            ]); ?>
            <fieldset>
                <legend><?= __('Login') ?></legend>
                <?php
                echo $this->Form->control('email');
                echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Login')) ?>
            <?= $this->Form->end() ?>
            <p>Forgot password? For password recovery
                <?php 
                    echo $this->Html->link(__('Click here'),
                    [
                        'controller' => 'Users',
                        'action' => 'recover'
                    ]);
                ?>
            </p>
        </div>
    </div>
</div>