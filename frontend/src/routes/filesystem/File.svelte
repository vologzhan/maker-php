<script lang="ts">
    import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";
    import {contextMenu} from '$lib/Store/ContextMenu';
    import {DeleteController} from "$lib/Controller/Controller/DeleteController";
    import {DeleteController as DeleteFile} from "$lib/Controller/Filesystem/File/DeleteController";

    let {
        onDelete,
        file,
    }: {
        onDelete: any,
        file: FileItem
    } = $props();

    function openContextMenu(event: MouseEvent) {
        event.preventDefault();

        contextMenu.set({
            visible: true,
            x: event.clientX,
            y: event.clientY,
            items: [
                {
                    label: 'Переименовать',
                    action: () => {
                        console.log('rename file', file.id);
                    }
                },
                {
                    label: 'Удалить',
                    action: () => {
                        let controller = null
                        if (file.type === 'controller') {
                            controller = DeleteController
                        } else {
                            controller = DeleteFile
                        }

                        controller(file.id)
                            .then(() => {
                                onDelete(file.id)
                            })
                            .catch(err => {
                                const error = err instanceof Error ? err.message : String(err)
                                console.log('delete file failed, fileId: ', file.id, 'error: ', error);
                            })
                    }
                }
            ]
        });
    }

    function getHref(file: FileItem) {
        switch (file.type) {
            case 'controller':
                return `/filesystem/controller/${file.id}`;
            default:
                return `/filesystem/file/${file.id}`;
        }
    }
</script>

<a
    href={getHref(file)}
    oncontextmenu={openContextMenu}
>
    {file.name}
</a>

<style>
    a {
        padding: 0 0 0 1.5em;
        background: url($lib/icons/file.svg) 0 0.1em no-repeat;
        background-size: 1em 1em;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        color: inherit;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
