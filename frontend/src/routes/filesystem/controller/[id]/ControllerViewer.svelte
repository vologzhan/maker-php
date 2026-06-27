<script lang="ts">
    import type {ControllerResponse} from "$lib/Response/Controller/ControllerResponse";

    let {
        controller,
    }: {
        controller: ControllerResponse
    } = $props();

    function save() {
        console.log('save', controller);
        // TODO: отправить на сервер
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
</form>
