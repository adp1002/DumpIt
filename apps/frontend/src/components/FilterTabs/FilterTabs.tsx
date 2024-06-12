import { useEffect, useState } from "react";
import { filterTab, getFilters, getLeagues, getTab, getTabs, refreshTab, refreshTabs } from "../../services/api";
import NavBar from "../NavBar";
import { Item, RawItem, RawTab, Tab, TabInfo } from "../../models/Tab";
import { Filter } from "../../models/Filter";
import { Tooltip } from "react-tooltip";
import { League } from "../../models/League";
import Dropdown from "../BasicElements/Dropdown";

function getImageURL(name: string) {
    return new URL(`../../assets/item-icons/${name}`, import.meta.url).href
}

function parseItems(items: RawItem[]): Item[] {
    return items.map(item => {
        return {
            ...item,
            mods: item.mods.map(mod => {
                let parsedMod = mod.mod;
                mod.values.forEach(value => {
                    parsedMod = parsedMod.replace('#', value.toString());
                });
                return parsedMod;
            }),
        }
    })
}

export default function FilterTabs() {
    const [leagues, setLeagues] = useState<League[]>([]);
    const [tabs, setTabs] = useState<TabInfo[]>([]);
    const [filters, setFilters] = useState<Filter[]>([]);

    const [selectedLeagueId, setSelectedLeagueId] = useState<string | null>(null);
    const [selectedTab, setSelectedTab] = useState<Tab | null>(null);
    const [selectedFilters, setSelectedFilters] = useState<Record<string, Filter>>({});

    const [filteredItems, setFilteredItems] = useState<Item[]>([]);

    const loadSelectedTab = (id: string): void => {
        setSelectedTab(null);
        getTab(id).then((data: { data: RawTab }) => {
            setSelectedTab({
                ...data.data,
                items: parseItems(data.data.items)
            });
        });
    }

    const addFilter = (filter: Filter) => {
        setSelectedFilters(prevFilter => {
            const newFilter = { ...prevFilter };
            newFilter[filter.id] = filter;
            return newFilter;
        })
    }

    const removeFilter = (filter: Filter) => {
        setSelectedFilters(prevFilter => {
            const newFilter = { ...prevFilter };
            delete newFilter[filter.id];
            return newFilter;
        })
    }

    useEffect(() => {
        getLeagues().then(data => setLeagues(data.data));
    }, [])

    useEffect(() => {
        if (null === selectedLeagueId) return;

        getFilters().then(data => setFilters(data.data));
        getTabs(selectedLeagueId).then((data: { data: TabInfo[] }) => {
            setTabs(data.data);

            if (0 !== data.data.length) {
                loadSelectedTab(data.data[0].id)
            }
        });
    }, [selectedLeagueId])

    const LeagueSelect = () => (
        <div className="m-5">
            <Dropdown
                options={leagues}
                initialValue={selectedLeagueId ?? ''}
                accessors={{ label: 'id', value: 'id' }}
                label={'League:'}
                onChange={(value) => {
                    setSelectedLeagueId(value);
                    setTabs([]);
                    setSelectedTab(null);
                }}
            />
        </div>
    )

    const TabNav = () => {
        if (0 === tabs.length) {
            return <></>
        }

        return (
            <ul className={`mt-2 flex border-b`}>
                {tabs.map(tab => (
                    <li className="-mb-px mr-1" key={tab.id}>
                        <div
                            className={`bg-white inline-block font-semibold py-2 px-4 ${tab.id === selectedTab?.id ? 'border-l border-t border-r rounded-t text-blue-700' : 'text-blue-500 hover:text-blue-800'}`}
                            onClick={() => loadSelectedTab(tab.id)}
                        >
                            {tab.name}
                        </div>
                    </li>
                ))}
            </ul>
        )
    }

    const TabsAndFilters = () => (
        <div className="flex flex-wrap p-4">
            <div className="w-full">
                <button className="mb-4 mr-10 btn-transparent" onClick={() => refreshTabs(selectedLeagueId as string).then(data => setTabs(data.data))}>Refresh tabs</button>
                <button className="mb-4 content-end btn-transparent" onClick={() => refreshTab(selectedTab?.id as string)}>Refresh selected tab</button>
            </div>
            {
                0 < tabs.length &&
                <div className="w-1/2 flex flex-wrap">
                    <div className="mb-4 w-full">Select a tab:</div>
                    {tabs.map(tab => (
                        <div className="w-1/3" key={tab.id}>
                            <input
                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                type="radio"
                                name="tab"
                                value={tab.name}
                                checked={selectedTab?.id === tab.id}
                                onClick={() => setSelectedTab({ ...tab, items: [] })}
                            />
                            <label className="ms-2 font-medium">{tab.name}</label>
                        </div>
                    ))}
                </div>
            }
            {
                0 < filters.length &&
                <div className="w-1/2 flex flex-wrap items-center">
                    <div className="mb-4 w-full">Select one or more filters:</div>
                    {filters.map(filter => (
                        <div className="w-1/3 mb-4" key={filter.id}>
                            <input
                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                type="checkbox"
                                name="filters"
                                value={filter.name}
                                checked={selectedFilters.hasOwnProperty(filter.id ?? '')}
                                onChange={(event) => event.currentTarget.checked ? addFilter(filter) : removeFilter(filter)}
                            />
                            <label className="ms-2 text-sm font-medium">{filter.name}</label>
                        </div>
                    ))}
                </div>
            }
            {null !== selectedTab && 0 < Object.keys(selectedFilters).length &&
                <div className="w-full m-5 justify-center items-center text-center align-center">
                    <button
                        className="btn-primary"
                        onClick={() => {
                            filterTab(selectedTab.id, Object.keys(selectedFilters)).then(data => setFilteredItems(parseItems(data.data)))
                        }}
                    >
                        Filter tab
                    </button>
                </div>
            }
        </div>
    )

    const TabPreview = () => {
        return (
            <div className="flex flex-wrap items-center justify-center border-t">
                {filteredItems.map(item => (
                    <div className="m-2">
                        <img data-tooltip-id={item.id} src={getImageURL(`${item.baseType.replaceAll(' ', '_')}_inventory_icon.png`)} />
                        <Tooltip id={item.id}>
                            <div className='text-xl text-amber-200'>{item.name} {item.baseType}</div>
                            {item.mods.map(mod => (
                                <div>{mod}</div>
                            ))}
                        </Tooltip>
                    </div>
                ))}
            </div>
        )
    }


    //TODO add refreshTabs button

    return (
        <>
            <NavBar />
            <LeagueSelect />
            {/* <TabNav /> */}
            <TabsAndFilters />
            <TabPreview />
        </>
    );
}
