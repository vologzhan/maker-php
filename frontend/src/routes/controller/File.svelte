<script lang="ts">
    import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";
    import {currentController} from "./store";

    let {
        controller,
    }: {
        controller: ControllerItem
    } = $props();
</script>

<button onclick={() => currentController.set(controller) }>
    {controller.name}
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
