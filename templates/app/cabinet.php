<?php
/** @var string $username */

$this->params['title'] = 'Hello';
$this->extends = 'layout/columns';
?>
<ul class="breadcrumb">
<li><a href="/">Home</a></li>
<li class="active">Cabinet</li>
</ul>
<h1>Cabinet of <?= htmlspecialchars(ucfirst($username), ENT_QUOTES | ENT_SUBSTITUTE) ?></h1>