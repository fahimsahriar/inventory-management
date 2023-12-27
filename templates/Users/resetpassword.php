<?php echo $this->Flash->render() ?>
<?= $this->Form->create() ?>
<h2>Enter your new password</h2>
<?= $this->Form->control('password'); ?>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>