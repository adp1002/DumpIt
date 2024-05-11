import { FilterMod } from "./FilterMod"

export type Filter = {
    id: string|null,
    name: string,
    mods: FilterMod[],
}