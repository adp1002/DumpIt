import { useEffect, useState } from "react";
import { getFilters, getLeagues, getTab, getTabs } from "../../services/api";
import NavBar from "../NavBar";
import { RawTab, Tab, TabInfo } from "../../models/Tab";
import { Filter } from "../../models/Filter";
import { Tooltip } from "react-tooltip";
import { League } from "../../models/League";
import Dropdown from "../BasicElements/Dropdown";
import Accordion from "../BasicElements/Accordion";

function getImageURL(name: string) {
    return new URL(`../../assets/item-icons/${name}`, import.meta.url).href
}

export default function FilterTabs() {
    const [leagues, setLeagues] = useState<League[]>([]);
    const [tabs, setTabs] = useState<TabInfo[]>([]);
    const [filters, setFilters] = useState<Filter[]>([]);

    const [selectedLeagueId, setSelectedLeagueId] = useState<string | null>(null);
    const [selectedTab, setSelectedTab] = useState<Tab | null>(null);
    const [selectedFilters, setSelectedFilters] = useState<Filter[]>([]);

    const loadSelectedTab = (id: string): void => {
        setSelectedTab(null);
        getTab(id).then((data: { data: RawTab }) => {
            setSelectedTab({
                ...data.data,
                items: data.data.items.map(item => {
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
            });
        });
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
                label = { 'League:'}
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

    const TabPreview = () => {
        return (
            <div>
                {selectedTab?.items.map(item => (
                    <>
                        <img data-tooltip-id={item.id} src={getImageURL(`${item.baseType.replaceAll(' ', '_')}_inventory_icon.png`)} />
                        <Tooltip id={item.id}>
                            <div className='text-xl text-amber-200'>{item.name} {item.baseType}</div>
                            {item.mods.map(mod => (
                                <div>{mod}</div>
                            ))}
                        </Tooltip>
                    </>
                ))}
            </div>
        )
    }


    //TODO add refreshTabs button

    return (
        <>
            <NavBar />
            <LeagueSelect />
            <TabNav />
            <TabPreview />
        </>
    );
}
