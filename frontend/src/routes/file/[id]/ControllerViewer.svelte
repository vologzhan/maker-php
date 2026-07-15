<script lang="ts">
    import {UpdateController} from "$lib/Controller/Controller/UpdateController";
    import type {FileContent} from "$lib/Response/Fs/Content/FileContent";
    import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";

    let {
        controller,
        onUpdate,
    }: {
        controller: ControllerItem
        onUpdate: (content: FileContent) => void
    } = $props();

    let error = $state('')

    function save() {
        UpdateController(controller.id, controller)
            .then((res: FileContent) => {
                onUpdate(res)
            })
            .catch((err: any) => {
                error = err instanceof Error ? err.message : String(err)
            })
    }
</script>

<form onsubmit={(e) => {
        e.preventDefault();
        save();
    }}>
    <div>
        <label for="method">Method</label>
        <input
                id="method"
                bind:value={controller.method}
        />
    </div>

    <div>
        <label for="path">Path</label>
        <input
                id="path"
                bind:value={controller.path}
        />
    </div>

    <div>
        <label for="responseId">Response ID</label>
        <input
                id="responseId"
                type="number"
                bind:value={controller.responseId}
        />
    </div>

    <button type="submit">
        Save
    </button>

    {#if error}
        Error: {error}
    {/if}
</form>
