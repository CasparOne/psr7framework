<?php
$this->params['title'] = 'About';
$this->extend('layout/columns');
?>
<?php ob_start(); ?>
<div class="panel panel-default">
    <div class="panel-heading">Site</div>
    <div class="panel-body">Site navigation</div>
</div>
<?php $this->params['sidebar'] = ob_get_clean(); ?>

<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">About</li>
</ul>
<h1>About the site</h1>
