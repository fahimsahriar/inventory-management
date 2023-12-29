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
                    <td><?= (($product->status == 1) ? __('Active') : __('Inactive')) ?></td>
                </tr>
                <?php if ($notifications){ ?>
                <th><?= __('Logs') ?></th>
                <td><?= __('Description')?></td>
                <?php } ?>
                <?php foreach ($notifications as $notification) : ?>
                <tr>
                    <th><?= __('') ?></th>
                    <td><?= $notification->description ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
