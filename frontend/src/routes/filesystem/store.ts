import { writable } from 'svelte/store';
import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";

export const currentController = writable<FileItem | null>(null);
