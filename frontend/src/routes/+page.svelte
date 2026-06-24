<script lang="ts">
    import {SelfCheck} from "$lib/Controller/SelfCheck";
    import ProjectList from "./ProjectList.svelte";

    let checkBackend = $state(false);
    let error = $state('');

    SelfCheck()
        .then(() => {
            checkBackend = true
        })
        .catch((err: any) => {
            error = err instanceof Error ? err.message : String(err)
        })
</script>

<div style:color={error ? 'red' : 'green'}>Backend</div>

{#if checkBackend}
    <ProjectList />
{:else if error}
    Error: {error}
{:else}
    Loading...
{/if}
