<?= $this->Flash->render() ?>
<div class="row">
    <div class="column-responsive mb-4">
        <div class="form content">
            <?php echo $this->Form->create(null, [
                'url' => [
                    "controller" => "Users",
                    "action" => "recover"
                ]
            ]); ?>
            <fieldset>
                <legend><?= __('Password Recovery') ?></legend>
                <?php
                echo $this->Form->control('email');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>