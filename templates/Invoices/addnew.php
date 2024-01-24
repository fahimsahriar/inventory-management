<h1>Select a product and quantity</h1>
<div>
    <!-- This is the template that we will clone -->
    <div id="product-template" style="display:none;">
        <?= $this->element('product') ?>
        <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        <button type="button" class="remove-product">Remove product</button>
    </div>
</div>

<?= $this->Form->create(null, ['url' => ['controller' => 'invoices', 'action' => 'yourAction']]) ?>
<div id="product-section">
    <?= $this->element('product') ?>
    <button type="button" class="remove-product">Remove product</button>
    <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
</div>
<button id="add_more_products" type="button">Add Product</button>
<br>
<?= $this->Form->button(__('Submit'), ['id' => 'submit']) ?>
<?= $this->Form->end() ?>