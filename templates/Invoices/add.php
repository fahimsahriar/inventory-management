<div class="row">
    <div class="column-responsive mb-4">
        <div class="form content">
            <h3><?= __("Create new invoice") ?></h3>
            <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right'])?>
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

            <?= $this->Form->create(null, ['url' => ['controller' => 'invoices', 'action' => 'makeInvoice']]) ?>
            <div id="product-container">
                <div id="product-section">
                    <?= $this->element('product') ?>
                    <!-- <button type="button" class="remove-product">Remove product</button> -->
                    <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>               
                </div>
            </div>
            <button id="add_more_products" type="button">Add Product</button>
            <br>
            <?= $this->Form->button(__('Submit'), ['id' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var invoiceid = "<?php echo null; ?>";
</script>