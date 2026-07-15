<script lang="ts">
    import type {FileItem} from "$lib/Response/Fs/Tree/FileItem";
    import {contextMenu} from '$lib/Store/ContextMenu';
    import {DeleteFile} from "$lib/Controller/Fs/DeleteFile";

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
                        DeleteFile(file.id)
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
</script>

<a
    href={`/file/${file.id}`}
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
