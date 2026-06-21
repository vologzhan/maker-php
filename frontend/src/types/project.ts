export interface Controller {
    id: number;
    name: string;
    method: string;
    path: string;
    responseId: number;
}

export interface ControllerDirectory {
    id: number;
    name: string;
    directories: ControllerDirectory[];
    files: Controller[];
}

export interface ControllersTree {
    name: string;
    directories: ControllerDirectory[];
    files: Controller[];
}

export interface Project {
    id: number;
    name: string;
    controllers: ControllersTree;
}

export interface CreateControllerInput {
    name: string;
    method: string;
    path: string;
    responseId: number;
    directoryId: number | null;
}

export interface UpdateControllerInput {
    id: number;
    name?: string;
    method?: string;
    path?: string;
    responseId?: number;
}

export interface CreateDirectoryInput {
    name: string;
    parentDirectoryId: number | null;
}

export interface UpdateDirectoryInput {
    id: number;
    name: string;
}