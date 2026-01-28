<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{$title|default:"Блог"}</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    {if isset($breadcrumbs) && $breadcrumbs|count}
        <nav class="breadcrumbs">
            {foreach $breadcrumbs as $crumb}
                {if $crumb.url}
                    <a href="{$crumb.url}">{$crumb.title}</a> &raquo;
                {else}
                    <span>{$crumb.title}</span>
                {/if}
            {/foreach}
        </nav>
    {/if}
    {block name="content"}{/block}
</div>
</body>
</html>