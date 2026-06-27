<script lang="ts">
    import File from './File.svelte';
    import Directory from './Directory.svelte';
    import {slide} from 'svelte/transition';
    import type {DirectoryItemResponse} from "$lib/Response/Project/Filesystem/DirectoryItemResponse";
    import {contextMenu} from '$lib/Store/ContextMenu';
    import {UpdateDirectoryType} from "$lib/Controller/Project/Filesystem/UpdateDirectoryTypeController";

    let {
        dir,
        open = $bindable(false)
    }: {
        dir: DirectoryItemResponse
        open?: boolean
    } = $props();

    function openOrClose() {
        open = !open;
    }

    function openContextMenu(event: MouseEvent) {
        event.preventDefault();

        contextMenu.set({
            visible: true,
            x: event.clientX,
            y: event.clientY,
            items: [
                {
                    label: 'Создать файл',
                    action: () => {
                        console.log('create file in', dir.name);
                    }
                },
                {
                    label: 'Создать директорию',
                    action: () => {
                        console.log('create directory in', dir.name);
                    }
                },
                {
                    label: 'Переименовать',
                    action: () => {
                        console.log('rename', dir.name);
                    }
                },
                {
                    label: 'Удалить',
                    action: () => {
                        console.log('delete', dir.name);
                    }
                },
                {
                    label: 'Set type Controller',
                    action: () =>
                        UpdateDirectoryType(dir.id, {type: 'controller'})
                            .catch((err: any) => {
                                console.error(err);
                            })
                }
            ]
        });
    }
</script>

<button
        class:open={open}
        onclick={openOrClose}
        oncontextmenu={openContextMenu}
>
    {dir.name}
</button>

{#if open}
    <ul transition:slide={{ duration: 300 }}>
        {#each dir.directories as directory (directory.id)}
            <li>
                <Directory dir={directory} />
            </li>
        {/each}

        {#each dir.files as file (file.id)}
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

    button:hover {
        text-decoration: underline; /* или убрать вовсе */
    }

    .open {
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
