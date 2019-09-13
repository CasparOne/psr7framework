<?php
/** @var Framework\Template\PhpRenderer $this */
?>
<?php $this->extend('layout/default'); ?>
<?php $this->beginBlock('title'); ?>Hello<?php $this->endBlock(); ?>
<?php $this->beginBlock('meta'); ?>
    <meta name="description" content="Home Page Description">
<?php $this->endBlock(); ?>
<?php $this->beginBlock('content'); ?>
<div class="jumbotron">
    <h1>Hello!</h1>
    <p>
        Congratulations, <?php echo ucfirst($name); ?>! You have successfully created your application.
    </p>
</div>
<?php $this->endBlock(); ?>