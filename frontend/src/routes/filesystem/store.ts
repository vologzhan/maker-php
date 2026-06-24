import { writable } from 'svelte/store';
import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";

export const currentController = writable<ControllerItem | null>(null);
