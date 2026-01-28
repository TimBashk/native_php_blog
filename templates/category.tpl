{extends file="layout.tpl"}

{block name="content"}
    <h1>{$category.name}</h1>
    <p class="category__description">{$category.description}</p>

    <div class="category-sort">
        <span>Сортировать по: </span>
        <a href="?id={$category.id}&sort=date" {if $currentSort == 'date'}class="active"{/if}>Дате</a> |
        <a href="?id={$category.id}&sort=views" {if $currentSort == 'views'}class="active"{/if}>Популярности</a>
    </div>

    {if $posts|count}
        <div class="posts">
            {foreach $posts as $post}
                <div class="post-card">
                    <div class="post-card__image">
                        <img src="/assets/images/{$post.image|default:'placeholder.jpg'}" alt="">
                    </div>

                    <div class="post-card__body">
                        <h3>
                            <a href="/post.php?id={$post.id}">
                                {$post.title}
                            </a>
                        </h3>

                        <p class="post-card__description">
                            {$post.description}
                        </p>

                        <div class="post-card__meta">
                            👁 {$post.views} просмотров
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>

        {if $totalPages > 1}
            <div class="pagination">
                {if $currentPage > 1}
                    <a href="?id={$category.id}&sort={$currentSort}&page={$currentPage-1}">&laquo; Предыдущая</a>
                {/if}

                {section name=page loop=$totalPages}
                    {assign var=pageNum value=$smarty.section.page.index + 1}
                    {if $pageNum == $currentPage}
                        <span class="current">{$pageNum}</span>
                    {else}
                        <a href="?id={$category.id}&sort={$currentSort}&page={$pageNum}">{$pageNum}</a>
                    {/if}
                {/section}

                {if $currentPage < $totalPages}
                    <a href="?id={$category.id}&sort={$currentSort}&page={$currentPage+1}">Следующая &raquo;</a>
                {/if}
            </div>
        {/if}

    {else}
        <p>В этой категории пока нет статей.</p>
    {/if}
{/block}
