import type {FileItem} from "$lib/Response/Fs/FileItem";

export interface DirItem {
    id: number
    name: string
    dirs: DirItem[]
    files: FileItem[]
}
