import type {DirItem} from "$lib/Response/Controller/DirItem";
import type {ResponseItem} from "$lib/Response/Controller/ResponseItem";

export interface ProjectResponse {
    id: number,
    name: string,
    controllers: DirItem
    responses: ResponseItem[]
}
