<div class="form-group">
    <?= $this->Form->control('product_id[]', ['options' => $products, 'empty' => 'Select a product', 'class'  => 'product_id', 'label' => '']) ?>
    <!-- Quantity -->
    <?= $this->Form->number('quantity[]', ['min' => 0, 'class' => 'product_quantity', 'placeholder' => 'Quantity']) ?>
    <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
</div>