<h3><?= __('Editing invoive') ?></h3>
<h4><?= __('Select Product and quantity') ?></h4>
<br>
<div>
    <!-- This is the template that we will clone -->
    <div id="product-template" style="display:none;">
        <div class="form-group">
            <?= $this->Form->control('product_id[]', ['options' => $products, 'empty' => 'Select a product', 'class'  => 'product_id', 'label' => '']) ?>
            <!-- Quantity -->
            <?= $this->Form->number('quantity[]', ['min' => 0, 'class' => 'product_quantity', 'placeholder' => 'Quantity']) ?>
            <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        </div>
        <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        <button type="button" class="remove-product">Remove product</button>
        <hr>
    </div>
</div>

<?= $this->Form->create(null, ['url' => ['controller' => 'invoices', 'action' => 'editInvoiceFormSubmission']]) ?>
<div id="product-container">
<?php foreach ($selected_products as $index => $selected_product): ?>
    <div id="product-section">
        <div class="form-group">
            <?= $this->Form->control('product_id[]', ['options' => $products, 'default' => $selected_product->product_id, 'empty' => 'Select a product', 'class' => 'product_id', 'label' => false]) ?>
            <?= $this->Form->number('quantity[]', ['value' => $selected_product->quantity, 'min' => 0, 'class' => 'product_quantity', 'placeholder' => 'Quantity']) ?>
            <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        </div>
        <button type="button" class="remove-product">Remove product</button>
        <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        <hr>
    </div>
<?php endforeach; ?>
</div>
<button id="add_more_products" type="button">Add Product</button>
<br>
<?= $this->Form->button(__('Submit'), ['id' => 'submit']) ?>
<?= $this->Form->end() ?>
<script type="text/javascript">
    var invoiceid = "<?php echo $invoiceid; ?>";
</script>