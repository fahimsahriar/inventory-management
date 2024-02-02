<?php
$tempDelete = $this->request->getSession()->read('tempDelete');
$cart = $this->request->getSession()->read('Cart2');
?>
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
                    <td><?php 
                        if(isset($tempDelete[$product->id])){
                            echo h($product->quantity)+$tempDelete[$product->id];
                        }elseif(isset($cart[$product->id])){
                            echo h($product->quantity)+$cart[$product->id];
                        }else{
                            echo h($product->quantity);
                        }
                    ?></td>
                </tr>
            </table>
            <?= $this->Form->create() ?>
                <?php 
                    if(isset($tempDelete[$product->id])){
                        echo $this->Form->control('quantity', [
                            'type' => 'number',
                            'label' => 'Enter a quantity:',
                            'min' => 1,
                            'max' => $product->quantity + $tempDelete[$product->id],
                            'step'=> 1,
                            'class'=> 'number-input',
                            'required'=> true,
                    ]);
                    }elseif(isset($cart[$product->id])){
                        echo $this->Form->control('quantity', [
                            'type' => 'number',
                            'label' => 'Enter a quantity:',
                            'min' => 1,
                            'max' => $product->quantity + $cart[$product->id],
                            'step'=> 1,
                            'class'=> 'number-input',
                            'required'=> true,
                    ]);
                    }else{
                        echo $this->Form->control('quantity', [
                            'type' => 'number',
                            'label' => 'Enter a quantity:',
                            'min' => 1,
                            'max' => $product->quantity,
                            'step'=> 1,
                            'class'=> 'number-input',
                            'required'=> true,
                    ]);
                    }
                ?>
                <?= $this->Form->control('product_id', [
                        'type' => 'hidden',
                        'value' => $product->id
                ]) ?>
                <?= $this->Form->button('Add product') ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
