<?php
$this->params['title'] = 'Hello';
$this->extends = 'layout/default';
?>
<div class="jumbotron">
    <h1>Hello!</h1>
    <p>
        Congratulations, <?= ucfirst($name) ?>! You have successfully created your application.
    </p>
</div>