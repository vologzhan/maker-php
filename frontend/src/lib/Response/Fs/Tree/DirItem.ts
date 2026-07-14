import type {FileItem} from "$lib/Response/Fs/Tree/FileItem";

export interface DirItem {
    id: number
    name: string
    dirs: DirItem[]
    files: FileItem[]
}
