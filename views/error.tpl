{extends file="layout.tpl"}

{block name="lang"}en{/block}
{block name="title"}Error {$statusCode}{/block}
{block name="bodyAttributes"} class="error-page"{/block}

{block name="content"}
    <div class="error">
        <h1>{$statusCode}</h1>
        <p>{$message}</p>
    </div>
{/block}
