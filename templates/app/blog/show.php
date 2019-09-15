<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var \App\ReadModel\Views\PostView   $post
 */
$this->extend('layout/default');
?>

<?php $this->beginBlock('title'); ?><?php echo $this->encode($post->title); ?><?php $this->endBlock(); ?>
<?php $this->beginBlock('meta'); ?>
    <meta name="description" content="<?php echo $this->encode($post->title); ?>">
<?php $this->endBlock(); ?>

<?php $this->beginBlock('breadcrumbs'); ?>
    <ul class="breadcrumb">
        <li><a href="<?php echo $this->encode($this->path('home')); ?>">Home</a></li>
        <li><a href="<?php echo $this->encode($this->path('about')); ?>">About</a></li>
        <li><a href="<?php echo $this->encode($this->path('blog')); ?>">Blog</a></li>
        <li class="active"><?php echo $this->encode($post->title); ?></li>
    </ul>
<?php $this->endBlock(); ?>



<?php $this->beginBlock('content'); ?>
    <h1><?php echo $this->encode($post->title); ?></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $this->encode($post->date->format('Y-m-d')); ?>
        </div>
        <div class="panel-body">
            <?php echo $this->encode($post->content); ?>
        </div>
    </div>
<?php $this->endBlock(); ?>

