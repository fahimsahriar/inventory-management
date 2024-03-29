<?php use Cake\Core\Configure;
$session = $this->request->getSession();
$cart = $session->read('Cart2');
?>
<div class="row">
    <div class="column-responsive">
        <div class="categories view content">
            <?= $this->Html->link(__('Back'), ['action' => 'editinvoice', $invoiceId, Configure::read('editflag')], ['class' => 'button float-right']) ?>
            <h3><?= h($product->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($product->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= h($product->category->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stock') ?></th>
                    <td><?= h($product->quantity + (int)$cart[$selected]) ?></td>
                </tr>
            </table>
            <?= $this->Form->create() ?>
                <?= $this->Form->control('quantity', [
                        'type' => 'number',
                        'label' => 'Enter a quantity:',
                        'min' => 1,
                        'max' => (int)$product->quantity + (int)$cart[$selected],
                        'step'=> 1,
                        'class'=> 'number-input',
                        'required'=> true,
                        'value' => $cart[$selected]
                ]) ?>
                <?= $this->Form->control('product_id', [
                        'type' => 'hidden',
                        'value' => $product->id
                ]) ?>
                <?= $this->Form->button('Submit') ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
                    