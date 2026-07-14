<script lang="ts">
    import Directory from './Directory.svelte';
    import {GetTree} from "$lib/Controller/Fs/GetTree";
    import type {DirItem} from "$lib/Response/Fs/DirItem";
    import ContextMenu from "./ContextMenu.svelte";

    let error = $state('');
    let dir = $state<DirItem|null>(null);
    let { children } = $props();

    $effect(() => {
        GetTree()
            .then((res: DirItem) => {
                dir = res;
                error = '';
            })
            .catch((err: any) => {
                error = err instanceof Error ? err.message : String(err);
            });
    });
</script>

{#if dir}
    <div>
        <aside>
            <Directory dir={dir} open={true} />
        </aside>

        <main>
            {@render children()}
        </main>
    </div>
{:else if error}
    Error: {error}
{:else}
    Loading...
{/if}

<style>
    div {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: 100vh;
    }

    aside {
        border-right: 1px solid #ddd;
        overflow: auto;
    }

    main {
        overflow: auto;
        padding: 12px;
    }
</style>

<ContextMenu />
