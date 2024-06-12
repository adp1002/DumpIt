import { FilterMod } from "./FilterMod"

export type NewFilter = {
    id: string|null,
    name: string,
    mods: FilterMod[],
}

export type Filter = {
    id: string,
    name: string,
    mods: FilterMod[],
}