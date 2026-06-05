<script lang="ts">
    import axios from 'axios'
    import { onMount } from 'svelte'

    onMount(() => {
        fileContentController()
    })

    interface FileContentResponse {
        tokens: TokenItem[]
    }

    interface TokenItem {
        pos: number
        end: number
        value: string
        type: string
    }

    async function fileContentController() {
        try {
            const response = await axios.get<FileContentResponse>(
                '/api/file/content'
            )

            fileContent = response.data

            console.log('Response:', response.data)
        } catch (error) {
            console.error(error)
        }
    }

    let fileContent: FileContentResponse = {
        tokens: [],
    }

    let currentToken: TokenItem | null = null

    function renderTokenValue(node: NodeItem): string {
        if (node.type !== 'whitespace') {
            return escapeHtml(node.value)
        }

        return escapeHtml(node.value)
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

<div class="layout">
    <div class="editor">
        {#each fileContent.tokens as token}
            <span
                    class="token"
                    title={`Type: ${token.type}
Pos: ${token.pos}
End: ${token.end}`}
                    onmouseenter={() => currentToken = token}
                    onmouseleave={() => currentToken = null}
            >
                {@html renderTokenValue(token)}
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