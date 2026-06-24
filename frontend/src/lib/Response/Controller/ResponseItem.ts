import type {FieldItem} from "$lib/Response/Controller/FieldItem";

export interface ResponseItem {
    id: number
    name: string
    fields: FieldItem
}
