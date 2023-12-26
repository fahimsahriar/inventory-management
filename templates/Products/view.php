<div class="row">
    <div class="column-responsive column-80">
        <div class="categories view content">
            <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right']) ?>
            <h3>Name: <?= h($product->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($product->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($product->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($product->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= h($product->category->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Quantity') ?></th>
                    <td><?= h($product->quantity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($product->status) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
