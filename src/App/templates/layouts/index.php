<?php
/**
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Тестовое задание для компании Gaijin</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="https://bootstraptema.ru/plugins/2015/bootstrap3/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="/js/request.js" type="text/javascript"></script>
    <script src="/js/app.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/css/styles.css" />
</head>
<body>
    <section class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo $content;
                ?>
            </div>
        </div><!-- /.row -->
    </section><!-- /.container -->
</body>
</html>