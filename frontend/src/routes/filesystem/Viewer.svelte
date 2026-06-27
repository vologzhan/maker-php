<script lang="ts">
    import {currentFile, currentContent} from "$lib/Store/File";
    import type {TokenItem} from "$lib/Response/Filesystem/File/TokenItem";
    import {GetContent} from "$lib/Controller/Filesystem/File/GetContentController";

    let currentToken: TokenItem|null = $state(null);
    let error = $state('');

    $effect(() => {
        const file = $currentFile;
        if (!file) return;

        GetContent(file.id)
            .then(res => currentContent.set(res))
            .catch(err => error = err instanceof Error ? err.message : String(err));
    });

    function renderToken(token: TokenItem): string {
        if (token.type !== 'whitespace') {
            return escapeHtml(token.value)
        }

        return escapeHtml(token.value)
            .replace(/ /g, '<span style="color: #d7caca">·</span>')
            .replace(/\t/g, '<span style="color: #d7caca; letter-spacing: -1px">----</span>')
            .replace(/\n/g, '\n')
    }

    function escapeHtml(text: string): string {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
    }
</script>

{#if $currentContent}
    <div class="layout">
        <div class="editor">
            {#each $currentContent.items as token}
                <span
                        class="token"
                        title={`Type: ${token.type}
    Pos: ${token.pos}
    End: ${token.end}`}
                        onmouseenter={() => currentToken = token}
                        onmouseleave={() => currentToken = null}
                >
                    {@html renderToken(token)}
                </span>
            {/each}
        </div>

        <div class="sidebar">
            {#if currentToken}
                <div><b>Type:</b> {currentToken.type}</div>
                <div><b>Pos:</b> {currentToken.pos}</div>
                <div><b>End:</b> {currentToken.end}</div>

                <hr>

                <pre>{currentToken.value}</pre>
            {/if}
        </div>
    </div>
{:else if error}
    Error: {error}
{:else}
    Select item
{/if}

<style>
    .layout {
        display: flex;
        gap: 20px;
    }

    .editor {
        flex: 1;
        border: 1px solid #ccc;
        padding: 16px;
        white-space: pre-wrap;
        font-family: monospace;
        overflow: auto;
    }

    .token:hover {
        background: #fff3a0;
    }

    .sidebar {
        width: 350px;
        border: 1px solid #ccc;
        padding: 12px;
    }

    pre {
        margin: 0;
        white-space: pre-wrap;
    }

    .editor {
        white-space: pre-wrap;
        font-family: monospace;
    }
</style>
