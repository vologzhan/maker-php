<script lang="ts">
    import File from './File.svelte';
    import Directory from './Directory.svelte';
    import {slide} from 'svelte/transition';
    import type {DirectoryItemResponse} from "$lib/Response/Project/Filesystem/DirectoryItemResponse";
    import {contextMenu} from '$lib/Store/ContextMenu';
    import {UpdateDirectoryType} from "$lib/Controller/Project/Filesystem/UpdateDirectoryTypeController";
    import {CreateController} from "$lib/Controller/Filesystem/File/CreateController";
    import type {CreateRequest} from "$lib/Request/Filesystem/File/CreateRequest";
    import type {CreateResponse} from "$lib/Response/Filesystem/File/CreateResponse";

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
                    label: 'Create file',
                    action: () => {
                        const name = prompt('Enter file name');
                        if (!name) {
                            return;
                        }

                        const req: CreateRequest = {
                            directoryId: dir.id,
                            name: name,
                        }

                        CreateController(req)
                            .then((res: CreateResponse) => {
                                dir.files.push({
                                    id: res.id,
                                    name: name,
                                });
                            })
                            .catch((err: any) => {
                                const error = err instanceof Error ? err.message : String(err)
                                console.log('create file failed: ', error);
                            })

                        console.log('create file in', dir.name + '/' + name);
                    }
                },
                {
                    label: 'Create directory',
                    action: () => {
                        console.log('create directory in', dir.name);
                    }
                },
                {
                    label: 'Rename',
                    action: () => {
                        console.log('rename', dir.name);
                    }
                },
                {
                    label: 'Delete',
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

    function deleteFile(id: number) {
        dir.files = dir.files.filter(f => f.id !== id);
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
                <File file={file} onDelete={deleteFile} />
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
