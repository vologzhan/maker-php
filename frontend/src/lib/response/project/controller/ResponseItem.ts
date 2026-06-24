import type {FieldItem} from "$lib/response/project/controller/FieldItem";

export interface ResponseItem {
    id: number
    name: string
    fields: FieldItem
}
