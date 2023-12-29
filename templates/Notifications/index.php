<?php
use Cake\Core\Configure;
?>
<div class="categories index content">
    <h3><?= __('notifications') ?></h3>
    <div class="table-responsive">
        <?php if(count($notifications)){ ?>
        <table>
            <thead>
                <tr>
                    <th><?= __('Time') ?></th>
                    <th><?= $this->Paginator->sort('Unread notifications') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification) : ?>
                    <tr>
                        <td><?= $this->Time->nice($notification->date_time) ?></td>
                        <?php if($notification->unread == Configure::read('unread')){ ?>
                            <td class="unread_class"><?= __($notification->product->name).__("'s quantity changed").__(". ").h($notification->description) ?></td>
                        <?php } else{ ?>
                            <td><?= __($notification->product->name).__("'s quantity changed").__(". ").h($notification->description) ?></td>
                        <?php } ?>
                        <td class="actions">
                            <?= $this->Form->postLink(
                                'View',
                                ['action' => 'view', $notification->id]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php }else{
            echo "<p>No new notification</p>";
        }
        ?>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>