import type {DirItem} from "$lib/response/project/controller/dir-item";

export interface ProjectResponse {
    id: number,
    name: string,
    controllers: DirItem
}
