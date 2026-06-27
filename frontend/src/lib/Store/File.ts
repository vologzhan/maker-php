import {writable} from 'svelte/store';
import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";
import type {ContentResponse} from "$lib/Response/Filesystem/File/ContentResponse";

export const currentFile = writable<FileItem | null>(null);
export const currentContent = writable<ContentResponse | null>(null);
