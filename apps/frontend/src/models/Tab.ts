export type TabInfo = {
    id: string,
    name: string,
    index: string,
    league: string,
    lastSync: string,
}

type ItemMod = {
    mod: string,
    values: number[],
}

export type RawItem = {
    id: string,
    name: string,
    baseType: string,
    mods: ItemMod[],
}

export type Item = {
    id: string,
    name: string,
    baseType: string,
    mods: string[],
}

export type Tab = TabInfo & {
    items: Item[],
}

export type RawTab = TabInfo & {
    items: RawItem[],
}
