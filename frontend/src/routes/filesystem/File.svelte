<script lang="ts">
    import {currentController} from "./store";
    import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";

    let {
        file,
    }: {
        file: FileItem
    } = $props();
</script>

<!-- todo тут другой файл и вообще может быть не контроллер -->
<button onclick={() => currentController.set(file) }>
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
