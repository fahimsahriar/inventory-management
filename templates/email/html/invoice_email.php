<table>
    <tr>
        <th><?= __('Invoice id') ?></th>
        <td><?= $this->Number->format($invoice->id) ?></td>
    </tr>
    <tr>
        <th><?= __('Name') ?></th>
        <td><?= h($invoice->user->name) ?></td>
    </tr>
    <tr>
        <th><?= __('Email') ?></th>
        <td><?= h($invoice->email) ?></td>
    </tr>
    <tr>
        <th><?= __('Date') ?></th>
        <td><?= __($invoice->created_at) ?></td>
    </tr>
    <?php if ($products){ ?>
    <th><?= __('Invoiced Products') ?></th>
    <th><?= __('Quantity')?></th>
    <?php }
    $total_products = 0;
    ?>
    <?php foreach ($products as $product) : ?>
    <tr>
        <td><?= $product->product->name ?></td>
        <td><?= __($product->quantity) ?></td>
        <?php 
            $total_products = $total_products + $product->quantity;
        ?>
    </tr>
    <?php endforeach; ?>
    <tr>
        <th>Total</th>
        <td><?= $total_products ?></td>
    </tr>
</table>