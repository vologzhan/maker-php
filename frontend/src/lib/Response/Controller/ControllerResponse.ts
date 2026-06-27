import type {TokenItem} from "$lib/Response/Filesystem/File/TokenItem";

export interface ControllerResponse {
    id: number
    method: string
    path: string
    responseId: number|null
    content: TokenItem[]
}
