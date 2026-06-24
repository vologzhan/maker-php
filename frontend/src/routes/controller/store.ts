import { writable } from 'svelte/store';
import type {ControllerItem} from "$lib/response/project/controller/ControllerItem";

export const currentController = writable<ControllerItem | null>(null);
