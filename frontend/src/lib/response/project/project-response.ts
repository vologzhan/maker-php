import type {DirItem} from "$lib/response/project/controller/DirItem";
import type {ResponseItem} from "$lib/response/project/controller/ResponseItem";

export interface ProjectResponse {
    id: number,
    name: string,
    controllers: DirItem
    responses: ResponseItem[]
}
