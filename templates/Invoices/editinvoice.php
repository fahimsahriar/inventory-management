<?php
use Cake\ORM\TableRegistry;
$product_table = TableRegistry::getTableLocator()->get('Products');
$session = $this->request->getSession();
$user_email = $session->read('email');
$user_id = $session->read('userid');
?>
<div class="row">
    <div class="column-responsive mb-4">
        <div class="form content">
            <?php
                echo $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right']);
            ?>
            <?= $this->Form->create(null) ?>
                <legend><?= __('Edit invoice') ?></legend>
                <div class="table-responsive">
                    <p>Name: <?= $invoice->user->name ?></p>
                    <p>Email: <?= $invoice->email ?></p>
                </div>
                <div class="add_products">
                    <?php
                        if ($session->check('Cart2')) { 
                            $cart2 = $session->read('Cart2');
                            ?>
                                <table>
                                    <thead>
                                        <th><?= __('Added Products') ?></th>
                                        <th><?= __('Quantity') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </thead>
                                    <?php
                                    $counter = 0;
                                    $total_quantity = 0;
                                    $invoiceid = $invoice->id;
                                    ?>
                                    
                                    <tbody>
                                        <?php foreach ($cart2 as $product_id => $quantity) : ?>
                                            <?php $total_quantity = $total_quantity + $quantity ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $productitem = $product_table->get($product_id);
                                                        echo $productitem['name'];
                                                    ?>
                                                </td>
                                                <td><?php echo $quantity; ?></td>
                                                <td class="actions">
                                                    <div style="display: none;">
                                                        <?= $this->Form->create(null, ['url' => ['action' => 'remove', $product_id, $invoiceid], 'style' => 'display: none;', 'id' => 'removeForm']); ?>
                                                        <?= $this->Form->postLink(__('remove'), '#', ['confirm' => __('Are you sure you want to remove?'), 'onclick' => 'document.getElementById("removeForm").submit(); return false;']); ?>
                                                        <?= $this->Form->end(); ?>
                                                    </div>

                                                    <?php
                                                    echo $this->Form->postLink(__('Delete'), ['action' => 'remove', $product_id, $invoiceid],
                                                    ['confirm' => __('Are you sure you want to delete # {0}?', $product_id)]);
                                                    ?>
                                                    <?= $this->Html->link(__('Edit'), ['action' => 'editcartforeditinginvoice', $product_id, $invoiceid]) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <td></td>
                                        <th>Total: <?= $total_quantity ?></th>
                                        <td></td>
                                    </tfoot>
                            </table>
                    <?php    }
                    ?>
                    <?= $this->Html->link(__('Add Product'), ['action' => 'productsforexistinginvoice', $invoice->id], ['class' => 'button invoice_add_product_btn']) ?>
                </div>
                <?php
                if ($session->check('Cart2')) {
                    $cart2 = $session->read('Cart2');
                    if(count($cart2)){
                        echo $this->Form->button(__('Submit'), ['class' => 'button invoice_submission_btn']);
                    }
                }
                ?>  
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>