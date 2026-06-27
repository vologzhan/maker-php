import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";

export interface DirectoryItemResponse {
    id: number
    name: string
    type: string
    directories: DirectoryItemResponse[]
    files: FileItem[]
}
