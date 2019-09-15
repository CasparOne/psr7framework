<?php
/** @var Framework\Template\PhpRenderer $this */
$this->extend('layout/columns');
?>

<?php $this->beginBlock('title'); ?>About<?php $this->endBlock(); ?>

<?php $this->beginBlock('breadcrumbs'); ?>
<ul class="breadcrumb">
    <li><a href="<?php echo $this->encode($this->path('home')); ?>">Home</a></li>
    <li class="active">About</li>
</ul>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('meta'); ?>
    <meta name="about" content="About the site">
<?php $this->endBlock(); ?>
<?php $this->beginBlock('main'); ?>
<h1>About the site</h1>
<?php $this->endBlock(); ?>
