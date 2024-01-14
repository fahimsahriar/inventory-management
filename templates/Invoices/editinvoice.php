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
                <legend><?= __('Create invoice') ?></legend>
                <div class="table-responsive">
                    <p>Name: <?= $invoice->user->name ?></p>
                    <p>Email: <?= $invoice->email ?></p>
                </div>
                <div class="add_products">
                    <?php
                        $session = $this->request->getSession();
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
                                    echo "<pre>";
                                    var_dump($cart2);
                                    echo "</pre>";
                                    ?>
                                    
                                    <?php foreach ($cart2 as $product_id => $quantity) : ?>
                                    <?php
                                    echo "<pre>";
                                    var_dump($product_id);
                                    var_dump($quantity);
                                    echo "</pre>";
                                    ?>
                                    <tbody>
                                        <td>
                                        <?php
                                            $productitem = $product_table->get($product_id);
                                            echo $productitem['name'];
                                            $total_quantity = $total_quantity + (int)$quantity;
                                        ?>
                                        </td>
                                        <td><?php var_dump($quantity) ?></td>
                                        <td class="actions">
                                        <?php
                                            echo $this->Form->postLink(__('Remove'), ['action' => 'remove', $counter,$invoiceid ],
                                            ['confirm' => __('Are you sure you want to remove?')]);
                                        ?>
                                        <?= $this->Html->link(__('Edit'), ['action' => 'editcartforeditinginvoice', $product_id, $invoiceid]) ?>
                                        </td>
                                    </tbody>  
                                    <?php endforeach; ?>
                                    <tfoot>
                                        <td></td>
                                        <th>Total: <?= $total_quantity ?></th>
                                        <td></td>
                                    </tfoot>
                            </table>
                    <?php    }
                    ?>
                    <?= $this->Html->link(__('Add Product'), ['action' => 'products'], ['class' => 'button invoice_add_product_btn']) ?>
                </div>
                <?= $this->Form->button(__('Submit'), ['class' => 'button invoice_submission_btn']) ?>    
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>