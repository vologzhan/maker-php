import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";

export interface DirItem {
    name: string
    directories: DirItem[]
    files: ControllerItem[]
}
