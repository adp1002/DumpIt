import { ReactNode, useState } from 'react'

type Tab = {
    [tabName: string]: ReactNode
}

type TabProps = {
    tabs: Tab
}

export default function Tab ({ tabs }: TabProps) {
  const [tabId, setTabId] = useState(Object.keys(tabs)[0])

  const selectedTabClass = 'text-black border-b border-black';
  const defaultTab = 'text-gray-500 border-b border-gray-200 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300';

  return (
    <>
      <div className={`text-sm font-medium text-center`}>
        <ul className='flex flex-wrap -mb-px'>
          {Object.keys(tabs).map(name => (
            <li
              className={`space-x-2 flex-1 w-64 inline-block p-4 border-b-2 hover:cursor-pointer ${tabId === name ? selectedTabClass : defaultTab}`}
              onClick={() => setTabId(name)}
              key={name}
            >
              {name}
            </li>
          ))}
        </ul>
      </div>
      {tabs[tabId]}
    </>
  )
}
