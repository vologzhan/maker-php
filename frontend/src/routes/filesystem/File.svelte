<script lang="ts">
    import {currentController} from "./store";
    import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";
    import {contextMenu} from '$lib/Store/ContextMenu';

    let {
        file,
    }: {
        file: FileItem
    } = $props();

    function select() {
        currentController.set(file);
    }

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
                        console.log('delete file', file.id);
                    }
                }
            ]
        });
    }
</script>

<button
        onclick={select}
        oncontextmenu={openContextMenu}
>
    {file.name}
</button>

<style>
    button {
        padding: 0 0 0 1.5em;
        background: url($lib/icons/file.svg) 0 0.1em no-repeat;
        background-size: 1em 1em;
        cursor: pointer;
        border: none;
        font-size: 14px;
    }
</style>