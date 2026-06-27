import type {ContentItemResponse} from "$lib/Response/Filesystem/File/ContentItemResponse";

export interface ControllerResponse {
    id: number
    method: string
    path: string
    responseId: number|null
    // content: ContentItemResponse
}
