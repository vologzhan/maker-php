<script lang="ts">
	let { children } = $props();
</script>

<nav>
	<a href="/">home</a>
	<a href="/about">about</a>
	<a href="/blog">blog</a>
	<a href="/roll">roll</a>
</nav>

{@render children()}
