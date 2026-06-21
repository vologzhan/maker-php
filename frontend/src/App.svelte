<script lang="ts">
    interface Controller {
        id: number;
        name: string;
        method: string;
        path: string;
        responseId: number;
    }

    let controllers: Controller[] = [
        {
            id: 1,
            name: 'Index',
            method: 'POST',
            path: '/api/project/index',
            responseId: 2
        },
        {
            id: 2,
            name: 'Self check',
            method: 'GET',
            path: '/',
            responseId: 3
        }
    ];

    let selected: Controller | null = null;
</script>

<h1>Controllers</h1>

{#each controllers as controller}
    <div>
        <button onclick={() => (selected = controller)}>
            {controller.name}
        </button>
    </div>
{/each}

<hr />

{#if selected}
    <h2>{selected.name}</h2>

    <div>Method: {selected.method}</div>
    <div>Path: {selected.path}</div>
    <div>Response ID: {selected.responseId}</div>
{/if}