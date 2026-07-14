<script lang="ts">
    import { contextMenu } from '$lib/Store/ContextMenu';

    function close() {
        contextMenu.update(menu => ({
            ...menu,
            visible: false
        }));
    }
</script>

<svelte:window
        onclick={close}
        onkeydown={(e) => e.key === 'Escape' && close()}
/>

{#if $contextMenu.visible}
    <div
            class="menu"
            style:left="{$contextMenu.x}px"
            style:top="{$contextMenu.y}px"
    >
        {#each $contextMenu.items as item}
            <button
                    onclick={() => {
                    item.action();
                    close();
                }}
            >
                {item.label}
            </button>
        {/each}
    </div>
{/if}

<style>
    .menu {
        position: fixed;
        background: white;
        border: 1px solid #ccc;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 10px rgb(0 0 0 / .2);
        z-index: 1000;
    }

    .menu button {
        padding: .5rem 1rem;
        border: none;
        background: none;
        text-align: left;
    }

    .menu button:hover {
        background: #eee;
    }
</style>
