<?php
use Cake\Core\Configure;
$loggedInUser = $this->request->getSession()->read('Auth');
if($loggedInUser){
    $role = $loggedInUser['User']['role'];
}
?>

<div class="landing-page-content">
    <?php
        if ($loggedInUser){ ?>
            <h2 class="heading">Welcome to dashboard</h2>
            <div class="inner_button">
                <?php
                    if($role == Configure::read('super_admin')){ ?>
                        <a class="button" href="<?= $this->Url->build([
                            'controller' => 'Users',
                            'action' => 'index',
                        ]) ?>">Userlist</a>
                    <?php }
                ?>
                <a class="button" href="<?= $this->Url->build([
                    'controller' => 'Products',
                    'action' => 'index',
                ]) ?>">Product List</a>
                <a class="button" href="<?= $this->Url->build([
                    'controller' => 'Categories',
                    'action' => 'index',
                ]) ?>">Category List</a>
            </div>
        <?php
        }else{ ?>
            <h2 class="heading">Welcome to user management</h2>
        <?php }
    ?>
    <?php
        if (!$loggedInUser){ ?>
            <div class="landing_page_buttons">
                <p>Want to register?</p>
                <div class="inner_button">
                    <a class="button" href="<?= $this->Url->build([
                        'controller' => 'Users',
                        'action' => 'add',
                    ]) ?>">Register</a>
                </div>
            </div>
        <?php
        }
    ?>
</div>
