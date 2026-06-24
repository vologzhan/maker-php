import {writable} from 'svelte/store';
import type {ProjectItemResponse} from "$lib/Response/Project/ProjectItemResponse";

export const projects = writable<ProjectItemResponse[]>([]);
export const currentProject = writable<ProjectItemResponse | null>(null);

