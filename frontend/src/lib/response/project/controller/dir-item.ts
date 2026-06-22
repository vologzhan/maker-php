import type {ControllerItem} from "$lib/response/project/controller/controller-item";

export interface DirItem {
    name: string
    directories: DirItem[]
    files: ControllerItem[]
}
