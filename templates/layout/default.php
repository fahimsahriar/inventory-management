<?php
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
$cakeDescription = 'User management: Fahim';
$loggedInUser = $this->request->getSession()->read('Auth');
$table = TableRegistry::getTableLocator()->get('Notifications');
$Products = TableRegistry::getTableLocator()->get('Products');
if($loggedInUser){
    $notifications = $table->find('all', ['contain' => ['Products'], 'conditions' => ['unread' => Configure::read('unread'), 'Notifications.userid' => $loggedInUser['User']['id']],
    'order' => ['Notifications.date_time' => 'DESC']])->toArray();
}
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
            <div class="dropdown">
                <div class="bell_div" onclick="toggleDropdown()">
                    <?php 
                        if ($notifications) {
                            echo count($notifications);
                        }
                    ?>
                    <i class="fa-regular fa-bell"></i>
                </div>
                <div id="dropdownMenu" class="dropdown-content">
                    <?php $notifications = array_slice($notifications, 0, 5);?>
                    <?php foreach ($notifications as $notification) : ?>
                        <?= $this->Form->postLink(
                                    __($notification->product->name).__("'s quantity changed").__(". ").h($notification->description),
                                    ['controller'=>'Notifications','action' => 'view', $notification->id]
                        ) ?>
                        <p class="time_ago"><?= $this->Time->timeAgoInWords($notification->date_time) ?></p>
                        <div class="notication_area"></div>
                    <?php endforeach; ?>
                    <a href="<?= $this->Url->build([
                                'controller' => 'Notifications',
                                'action' => 'index',
                            ]) ?>">
                            See all
                    </a>
                </div>
            </div>
                
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
<?= $this->Html->script("notification_toggle.js") ?>
</html>