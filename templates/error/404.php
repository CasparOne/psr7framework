<?php

/** @var Framework\Template\PhpRenderer $this */
$this->extend('layout/default');
?>
<?php $this->beginBlock('title'); ?>404 - Not Found<?php $this->endBlock(); ?>
<?php $this->beginBlock('breadcrumbs'); ?>
<ul class="breadcrumb">
    <li><a href="<?php echo $this->encode($this->path('home')); ?>">Home</a></li>
    <li><a href="<?php echo $this->encode($this->path('about')); ?>">About</a></li>
    <li><a href="<?php echo $this->encode($this->path('blog')); ?>">Blog</a></li>
    <li class="active">Error</li>
</ul>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('content'); ?>
<h1>404 - Error! Page not found!</h1>
<?php $this->endBlock(); ?>
