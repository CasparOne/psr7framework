<?php
/** @var string $username */

$this->params['title'] = 'Cabinet';
$this->extend('layout/columns');
?>
<?php ob_start(); ?>
<div class="panel panel-default">
    <div class="panel-heading">Cabinet</div>
    <div class="panel-body">Cabinet navigation</div>
</div>
<?php $this->params['sidebar'] = ob_get_clean(); ?>
<ul class="breadcrumb">
<li><a href="/">Home</a></li>
<li class="active">Cabinet</li>
</ul>
<h1>Cabinet of <?= htmlspecialchars(ucfirst($username), ENT_QUOTES | ENT_SUBSTITUTE) ?></h1>