<script lang="ts">
    import {UpdateController} from "$lib/Controller/Controller/UpdateController";
    import type {ContentItemResponse} from "$lib/Response/Filesystem/File/ContentItemResponse";
    import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";

    let {
        controller,
    }: {
        controller: ControllerItem
    } = $props();

    let error = $state('')

    function save() {
        UpdateController(controller.id, controller)
            .then((res: ContentItemResponse) => {
                // controller.content = res.items // todo update content
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
