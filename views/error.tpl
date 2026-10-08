{extends file="layout.tpl"}

{block name="lang"}en{/block}
{block name="title"}Error {$statusCode}{/block}
{block name="bodyAttributes"} class="d-flex align-items-center justify-content-center vh-100 m-0"{/block}
{block name="header"}{/block}

{block name="content"}
    <div class="text-center">
        <h1 class="display-1 fw-bold text-primary">{$statusCode}</h1>
        <p class="lead text-secondary mb-4">{$message}</p>
        <a class="btn btn-outline-dark" href="/">&larr; На главную</a>
    </div>
{/block}
