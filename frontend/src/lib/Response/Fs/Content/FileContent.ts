import type {TokenItem} from "$lib/Response/Fs/Content/TokenItem";
import type {ControllerItem} from "$lib/Response/Controller/ControllerItem";

export interface FileContent {
    tokens: TokenItem[]
    controller: ControllerItem|null
}
