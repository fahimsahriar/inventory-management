<?php
$cakeDescription = 'User management: Fahim';
$loggedInUser = $this->request->getSession()->read('Auth');
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build([
                            'controller' => 'Pages',
                            'action' => 'display',
                        ]) ?>"><span>Cake</span>PHP</a>
        </div>
        <div class="top-nav-links">
            <?php
            if ($loggedInUser) {
            ?>
                <a href=""><i class="fa-regular fa-bell"></i></a>
                <?php 
                if (isset($headerData)) {
                    echo count($headerData);
                }
                ?>
            <?php
                echo $this->Html->link(__('Logout'), ['controller' => 'users', 'action' => 'logout']);
            } else {
                echo $this->Html->link(__('Login'), ['controller' => 'users', 'action' => 'login']);
            }
            ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
<script src="https://kit.fontawesome.com/5a02838b79.js" crossorigin="anonymous"></script>
</html>