<?php 
$tempDelete = $this->request->getSession()->read('tempDelete');
$cart = $this->request->getSession()->read('Cart2');
?>
<div class="categories index content">
    <div class="add_button_div">
        <?= $this->Html->link(__('Invoice list'), ['action' => 'index'], ['class' => 'button button-outline float-right']) ?>
    </div>
    <h3><?= __('Add products') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('Product id') ?></th>
                    <th><?= $this->Paginator->sort('Name') ?></th>
                    <th><?= $this->Paginator->sort('Desciption') ?></th>
                    <th>Category</th>
                    <th><?= $this->Paginator->sort('Quantity') ?></th>
                    <th><?= $this->Paginator->sort('Status') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $this->Number->format($product->id) ?></td>
                        <td><?= h($product->name) ?></td>
                        <td><?= h($product->description) ?></td>
                        <td><?= h($product->category->name) ?></td>
                        <td><?php 
                        if(isset($tempDelete[$product->id])){
                            echo h($product->quantity)+$tempDelete[$product->id];
                        }elseif(isset($cart[$product->id])){
                            echo h($product->quantity)+$cart[$product->id];
                        }else{
                            echo h($product->quantity);
                        }
                        ?></td>
                        <td><?= (($product->status == 1) ? __('Active') : __('Inactive')) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('Add product'), ['action' => 'addtocartforedit', $product->id, $invoiceId]) ?>
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