import { writable } from 'svelte/store';

export interface MenuItem {
    label: string;
    action: () => void;
}

export const contextMenu = writable({
    visible: false,
    x: 0,
    y: 0,
    items: [] as MenuItem[]
});
