export interface Controller {
    id: number;
    name: string;
    method: string;
    path: string;
    responseId: number;
}

export interface ControllerDirectory {
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