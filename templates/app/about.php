<?php
/** @var Framework\Template\PhpRenderer $this */
$this->extend('layout/columns');
?>

<?php $this->beginBlock('title'); ?>About<?php $this->endBlock(); ?>

<?php $this->beginBlock('sidebar'); ?>
<div class="panel panel-default">
    <div class="panel-heading">Site</div>
    <div class="panel-body">Site navigation</div>
</div>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('breadcrumbs'); ?>
<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">About</li>
</ul>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('meta'); ?>
    <meta name="about" content="About the site">
<?php $this->endBlock(); ?>
<?php $this->beginBlock('main'); ?>
<h1>About the site</h1>
<?php $this->endBlock(); ?>
