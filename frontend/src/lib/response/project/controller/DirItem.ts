import type {ControllerItem} from "$lib/response/project/controller/ControllerItem";

export interface DirItem {
    name: string
    directories: DirItem[]
    files: ControllerItem[]
}
