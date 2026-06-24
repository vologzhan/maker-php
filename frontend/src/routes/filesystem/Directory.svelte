<script lang="ts">
    import File from './File.svelte';
    import Directory from './Directory.svelte';
    import {slide} from 'svelte/transition';
    import type {DirectoryItemResponse} from "$lib/Response/Project/Filesystem/DirectoryItemResponse";

    let {
        dir,
        expanded = $bindable(false)
    }: {
        dir: DirectoryItemResponse
        expanded?: boolean
    } = $props();
</script>

<button class:expanded onclick={() => expanded = !expanded}>
    {dir.name}
</button>

{#if expanded}
    <ul transition:slide={{ duration: 300 }}>
        {#each dir.directories as directory}
            <li>
                <Directory dir={directory} />
            </li>
        {/each}
        {#each dir.files as file}
            <li>
                <File file={file} />
            </li>
        {/each}
    </ul>
{/if}

<style>
    button {
        padding: 0 0 0 1.5em;
        background: url($lib/icons/folder.svg) 0 0.1em no-repeat;
        background-size: 1em 1em;
        font-weight: bold;
        cursor: pointer;
        border: none;
        font-size: 14px;
    }

    .expanded {
        background-image: url($lib/icons/folder-open.svg);
    }

    ul {
        padding: 0.2em 0 0 0.5em;
        margin: 0 0 0 0.5em;
        list-style: none;
        border-left: 1px solid #eee;
    }

    li {
        padding: 0.2em 0;
    }
</style>
