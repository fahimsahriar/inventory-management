<?php
$loggedInUser = $this->request->getSession()->read('Auth');
?>
<div class="row">
    <div class="column-responsive mb-4">
        <div class="form content">
            <h3><?= __("Create new invoice") ?></h3>
            <?php
                if($loggedInUser){
                    echo $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right']);
                }else{
                    echo $this->Html->link(__('Back'), ['controller' => 'Pages','action' => 'display'], ['class' => 'button float-right']);
                }
            ?>
            <div>
                <!-- This is the template that we will clone -->
                <div id="product-template" style="display:none;">
                    <?= $this->element('product') ?>
                    <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>
                    <button type="button" class="remove-product">Remove product</button>
                </div>
            </div>

            <?= $this->Form->create(null, ['url' => ['controller' => 'invoices', 'action' => 'makeInvoice']]) ?>
            <div id="product-section">
                <?= $this->element('product') ?>
                <!-- <button type="button" class="remove-product">Remove product</button> -->
                <div class="quantity_warning" style="display:none;color:red;">The quantity entered is greater than available stock.</div>               
            </div>
            <button id="add_more_products" type="button">Add Product</button>
            <br>
            <?= $this->Form->button(__('Submit'), ['id' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>