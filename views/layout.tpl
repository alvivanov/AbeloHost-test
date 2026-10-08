<!DOCTYPE html>
<html lang="{block name="lang"}ru{/block}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name="title"}Блог{/block}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body{block name="bodyAttributes"}{/block}>
    {block name="header"}
        <header class="site-header py-4 border-bottom">
            <div class="container">
                <a class="site-logo" href="/">Блог</a>
            </div>
        </header>
    {/block}
    <main class="container pt-5 pb-5">
        {block name="content"}{/block}
    </main>
</body>
</html>
