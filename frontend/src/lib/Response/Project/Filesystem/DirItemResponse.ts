import type {FileItem} from "$lib/Response/Project/Filesystem/FileItem";

export interface DirItemResponse {
    id: number
    name: string
    directories: DirItemResponse[]
    files: FileItem[]
}
