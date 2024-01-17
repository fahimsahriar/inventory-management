<div class="row">
    <div class="column-responsive column-80">
        <div class="categories view content">
            <?= $this->Html->link(__('Back'), ['action' => 'products'], ['class' => 'button float-right']) ?>
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
                    <td><?= h($product->quantity) ?></td>
                </tr>
            </table>
            <?= $this->Form->create() ?>
                <?= $this->Form->control('quantity', [
                        'type' => 'number',
                        'label' => 'Enter a quantity:',
                        'min' => 1,
                        'max' => $product->quantity,
                        'step'=> 1,
                        'class'=> 'number-input',
                        'required'=> true,
                ]) ?>
                <?= $this->Form->control('product_id', [
                        'type' => 'hidden',
                        'value' => $product->id
                ]) ?>
                <?= $this->Form->button('Add product') ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
