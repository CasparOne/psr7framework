<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php echo $this->renderBlock('meta'); ?>
    <title><?php echo $this->renderBlock('title'); ?> - App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <style>
        body { padding-top: 70px; }
        .app { display: flex; min-height: 100vh; flex-direction: column; }
        .app-content { flex: 1; }
        .app-footer { padding-bottom: 1em; }
    </style>
</head>
<body class="app">
<header class="app-header">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    Application
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/about"><i class="glyphicon glyphicon-book"></i> About</a></li>
                    <li><a href="/cabinet"><i class="glyphicon glyphicon-user"></i> Cabinet</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="app-content">
    <main class="container">
        <?php echo $this->renderBlock('breadcrumbs'); ?>
        <?php echo $this->renderBlock('content'); ?>
    </main>
</div>

<footer class="app-footer">
    <div class="container">
        <hr />
        <p>&copy; 2019 - My App.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</body>
</html>