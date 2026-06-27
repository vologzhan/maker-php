<script lang="ts">
    import {currentController} from "./store";
    import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";

    let form = $state<ControllerItem>({
        id: 0,
        name: '',
        method: '',
        path: '',
        responseId: 0
    });

    $effect(() => {
        if ($currentController) {
            form = { ...$currentController };
        }
    });

    function save() {
        console.log('save', form);

        // TODO: отправить на сервер

        currentController.set({ ...form });
    }
</script>

{#if $currentController}
    <form onsubmit={(e) => {
        e.preventDefault();
        save();
    }}>
        <div>
            <label for="name">Name</label>
            <input
                    id="name"
                    bind:value={form.name}
            />
        </div>

        <div>
            <label for="method">Method</label>
            <input
                    id="method"
                    bind:value={form.method}
            />
        </div>

        <div>
            <label for="path">Path</label>
            <input
                    id="path"
                    bind:value={form.path}
            />
        </div>

        <div>
            <label for="responseId">Response ID</label>
            <input
                    id="responseId"
                    type="number"
                    bind:value={form.responseId}
            />
        </div>

        <button type="submit">
            Save
        </button>
    </form>
{:else}
    Select item
{/if}
