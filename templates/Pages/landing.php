<?php
$loggedInUser = $this->request->getSession()->read('Auth');
?>

<div class="landing-page-content">
    <?php
        if ($loggedInUser){ ?>
            <h2 class="heading">Welcome to dashboard, see <a class="" href="<?= $this->Url->build([
                'controller' => 'Users',
                'action' => 'index',
            ]) ?>">user list</a> </h2>
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
