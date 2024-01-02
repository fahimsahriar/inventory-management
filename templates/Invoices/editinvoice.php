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
                if($loggedInUser){
                    echo $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'button float-right']);
                }else{
                    echo $this->Html->link(__('Back'), ['controller' => 'Pages','action' => 'display'], ['class' => 'button float-right']);
                }
            ?>
            <?= $this->Form->create($invoice) ?>
                <legend><?= __('Create invoice') ?></legend>
                <div class="table-responsive">
                    <p>Name: <?= $loggedInUser['name'] ?></p>
                    <p>Email: <?= $loggedInUser['email'] ?></p>
                </div>
                <div class="add_products">
                    <?php
                        $session = $this->request->getSession();
                        if ($session->check('Cart')) { 
                            $cart = $session->read('Cart'); ?>
                                <table>
                                    <thead>
                                        <th>Added Products</th>
                                        <th>Quantity</th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </thead>
                                    <?php
                                    $counter = 0;
                                    $total_quantity = 0;
                                     ?>
                                    <?php foreach ($cart as $item) : ?>
                                    <tbody>
                                        <td>
                                        <?php
                                            $productitem = $product_table->get($item['id']);
                                            echo $productitem['name'];
                                            $total_quantity = $total_quantity + $item['quantity'];
                                        ?>
                                        </td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td class="actions">
                                        <?php
                                            echo $this->Form->postLink(__('Remove'), ['action' => 'remove', $counter], ['confirm' => __('Are you sure you want to remove?')]);
                                        ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'editcart', $counter]) ?>
                                        </td>
                                    </tbody>  
                                    <?php $counter++; ?> 
                                    <?php endforeach; ?>
                                    <tfoot>
                                        <td></td>
                                        <td>Total: <?= $total_quantity ?></td>
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