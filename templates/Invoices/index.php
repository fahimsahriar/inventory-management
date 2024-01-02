<div class="categories index content">
    <div class="add_button_div">
        <?= $this->Html->link(__('New invoices'), ['action' => 'add'], ['class' => 'button button-outline float-right']) ?>
    </div>
    <h3><?= __('Invoices') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= __('Invoice id') ?></th>
                    <th><?= __('User name') ?></th>
                    <th><?= __('Email') ?></th>
                    <th><?= $this->Paginator->sort('Created at') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice) : ?>
                    <tr>
                        <td><?= $this->Number->format($invoice->id) ?></td>
                        <td><?= h($invoice->user->name) ?></td>
                        <td><?= h($invoice->email) ?></td>
                        <td><?= h($invoice->created_at) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $invoice->id]) ?>
                            <?php
                                echo $this->Html->link(__('Edit'), ['action' => 'editinvoice', $invoice->id]);
                                echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $invoice->id],
                                ['confirm' => __('Are you sure you want to delete # {0}?', $invoice->id)]);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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