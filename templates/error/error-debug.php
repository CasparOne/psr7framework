<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var \Throwable                      $exception
 */
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php echo $this->renderBlock('meta'); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
    <title>Error</title>
</head>
<body>
<div class="app-content">
    <main class="container">

        <h1>Exception: <?php echo $this->encode($exception->getMessage()); ?></h1>

        <p>Code: <?php echo $this->encode($exception->getCode()); ?></p>
        <p><?php echo $this->encode($exception->getFile()); ?> on line <?php echo $this->encode($exception->getLine()); ?></p>
        <?php foreach ($exception->getTrace() as $trace): ?>
            <p><?php echo $this->encode($trace['file']); ?> on line <?php echo $this->encode($trace['line']); ?></p>
        <?php endforeach; ?>

    </main>
</div>
</body>
</html>
