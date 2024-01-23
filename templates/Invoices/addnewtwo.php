<div>
    <!-- This is the template that we will clone -->
    <div id="product-template" style="display:none;">
        <?= $this->element('product') ?>
        <button type="button" class="remove-product">Remove product</button>
    </div>
</div>
<div id="product_fields_container">
    <?= $this->Form->create(null, ['url' => ['controller' => 'invoices', 'action' => 'show']]) ?>
    <div class="product_fields">
        <!-- Product Dropdown -->
        <?= $this->Form->control('product_id', ['options' => $products, 'empty' => 'Select a product', 'class'  => 'product_id']) ?>
        <!-- Quantity -->
        <?= $this->Form->number('quantity', ['min' => 0, 'class' => 'product_quantity']) ?>
        <!-- Warning for excessive quantity -->
        <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
        <button type="button" class="remove_product" disabled>Remove Product</button>
    </div>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>

<button id="add_more_products">Add More Products</button>

<?= $this->Form->create(null, ['url' => ['controller' => 'invoices', 'action' => 'yourAction']]) ?>
<div id="product-section">
    <?= $this->element('product') ?>
    <button type="button" class="remove-product">Remove product</button>
</div>
<button id="add-product" type="button">Add Product</button>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
<script>
    $(document).ready(function() {
        $("#add-product").click(function() {
            var $template = $("#product-template").clone().removeAttr('id').show(); // Clone the template and remove its id
            $("#product-section").append($template); // Append the cloned template to the product section
        });

        $('#product-section').on('click', '.remove-product', function() {
            $(this).parent().remove(); // Remove the parent product section of the clicked remove button
        });
    });
</script>