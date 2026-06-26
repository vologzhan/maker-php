<script lang="ts">
    import type {ListResponse} from "$lib/Response/Project/ListResponse";
    import {GetList} from "$lib/Controller/Project/GetListController";
    import IndexDirectory from "./IndexDirectory.svelte";
    import {currentProject, projects} from "$lib/Store/Project";

    let error = $state('');

    GetList()
        .then((res: ListResponse) => {
            projects.set(res.items);
            currentProject.set(res.items[0] ?? null);
        })
        .catch((err: any) => {
            error = err instanceof Error ? err.message : String(err)
        })
</script>

<h1>Projects</h1>

{#if projects}
    <ul>
        {#each $projects as project}
            <li>
                <button
                        type="button"
                        class="project-item"
                        class:selected={$currentProject?.id === project.id}
                        onclick={() => $currentProject = project}
                >
                    {project.name}
                </button>
            </li>
        {/each}
    </ul>

    <IndexDirectory />
{:else if error}
    Error: {error}
{:else}
    Loading...
{/if}

<style>
    .project-item {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        text-decoration: underline;
        font: inherit;
    }

    .project-item.selected {
        font-weight: bold;
        text-decoration: none;
    }
</style>
