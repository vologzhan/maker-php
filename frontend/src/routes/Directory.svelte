<script lang="ts">
    import File from './File.svelte';
    import Directory from './Directory.svelte';
    import {slide} from 'svelte/transition';
    import type {DirItem} from "$lib/Response/Fs/Tree/DirItem";
    import {contextMenu} from '$lib/Store/ContextMenu';
    import {SetDirType} from "$lib/Controller/Fs/SetDirType";
    import {CreateController} from "$lib/Controller/Filesystem/File/CreateController";
    import type {CreateRequest} from "$lib/Request/Filesystem/File/CreateRequest";
    import type {FileItem} from "$lib/Response/Fs/Tree/FileItem";

    let {
        dir,
        open = $bindable(false)
    }: {
        dir: DirItem
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
                        createFile('File name', null)
                    }
                },
                {
                    label: 'Create PHP Class',
                    action: () => {
                        createFile('Class name', 'php_class')
                    }
                },
                {
                    label: 'Create Controller',
                    action: () => {
                        createFile('Controller name', 'controller')
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
                    label: 'Set type Project',
                    action: () =>
                        SetDirType(dir.id, {type: 'project'})
                            .then((res: FileItem) => {
                                dir.files.push(res)
                            })
                            .catch((err: any) => {
                                console.error(err);
                            })
                },
                {
                    label: 'Set type Controller',
                    action: () =>
                        SetDirType(dir.id, {type: 'controller'})
                            .then((res: FileItem) => {
                                dir.files.push(res)
                            })
                            .catch((err: any) => {
                                console.error(err);
                            })
                },
            ]
        });
    }

    function deleteFile(id: number) {
        dir.files = dir.files.filter(f => f.id !== id);
    }

    function createFile(message: string, type: string|null) {
        const filename = prompt(message);
        if (!filename) {
            return;
        }

        const req: CreateRequest = {
            directoryId: dir.id,
            name: filename,
            type: type
        }

        CreateController(req)
            .then((res: FileItem) => {
                dir.files.push(res);
            })
            .catch((err: any) => {
                const error = err instanceof Error ? err.message : String(err)
                console.log('create file failed: ', error);
            })

        console.log('create file in', dir.name + '/' + filename);
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
        {#each dir.dirs as directory (directory.id)}
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
