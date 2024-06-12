import { useEffect, useState } from 'react'
import { getFilters, getMods, saveFilter } from '../../services/api';
import { NewFilter } from '../../models/Filter';
import { CONDITIONS, FilterMod, Mod } from '../../models/FilterMod';
import Dropdown from '../BasicElements/Dropdown';
import NavBar from '../NavBar';
import Input from '../BasicElements/Input';
import { toast } from 'react-toastify';
import LabelInput from '../BasicElements/LabelInput';
import Icon from '@mdi/react';
import { mdiClose } from '@mdi/js';

export default function Filters() {
  const [filters, setFilters] = useState<NewFilter[]>([]);
  const [mods, setMods] = useState<Mod[]>([]);
  const [indexedMods, setIndexedMods] = useState<Record<string, Mod>>({});
  const [selectedFilter, setSelectedFilter] = useState<(NewFilter & { id: string | null }) | null>(null);

  useEffect(() => {
    getFilters().then((data) => setFilters(data.data));
    getMods().then(data => {
      const mods: Mod[] = data.data;

      mods.sort((m1, m2) => m1.text.localeCompare(m2.text))

      setMods(mods);

      const indexedMods: Record<string, Mod> = {};

      mods.forEach((mod: Mod) => {
        indexedMods[mod.id] = mod;
      })

      setIndexedMods(indexedMods);
    })
  }, []);

  const generateNewMod = (modId?: string, modText?: string) => {
    const newModId = modId ?? mods[0].id;
    const newModText = modText ?? mods[0].text;

    return { id: newModId, mod: newModText, condition: 'gte', values: Array(indexedMods[newModId].placeholders).fill(0) }
  }

  const updateMod = (modIndex: number, property: keyof FilterMod, newValue: any) => {
    setSelectedFilter(prevFilter => {
      if (null === prevFilter) {
        return prevFilter;
      }

      const newFilter: NewFilter = { ...prevFilter };
      let newFilterMod = { ...newFilter.mods[modIndex] };

      switch (property) {
        case 'id':
          newFilterMod = generateNewMod(newValue.id, newValue.mod);
          break;
        case 'condition':
          newFilterMod[property] = newValue as string;
          break;
        case 'values':
          newFilterMod[property] = newValue as number[];
          break;
      }

      newFilter.mods[modIndex] = newFilterMod;

      return newFilter;
    })
  }

  const addMod = () => {
    setSelectedFilter(prevFilter => {
      if (null === prevFilter) {
        return prevFilter;
      }

      const newFilter: NewFilter = { ...prevFilter };

      newFilter.mods.push(generateNewMod())

      return newFilter;
    })
  }

  const removeMod = (modIndex: number) => {
    setSelectedFilter(prevFilter => {
      if (null === prevFilter) {
        return prevFilter;
      }

      const newFilter: NewFilter = { ...prevFilter };

      newFilter.mods.splice(modIndex, 1)

      return newFilter;
    })
  }

  const saveSelectedFilter = () => {
    if (selectedFilter === null) return;

    saveFilter(selectedFilter, selectedFilter.id).then(() => {
      toast.success('Filter successfully saved')
    })
  }

  const generateNewFilter = (): NewFilter => {
    return { id: null, name: 'New filter', mods: [] }
  }
  const addFilter = () => {
    const newFilter = generateNewFilter();
    setFilters(prevFilters => ([...prevFilters, newFilter]));
    setSelectedFilter(newFilter);
  }

  const updateFilterName = (name: string) => {
    setSelectedFilter(prevFilter => {
      if (null === prevFilter) {
        return prevFilter;
      }

      return { ...prevFilter, name }
    })
  }

  const Filters = () => (
    <div className="w-1/3 flex-wrap">
      <div className='text-xl font-bold mb-3'>My filters:</div>
      {filters.map(filter => <div key={filter.id} className={`md:w-1/3 mb-1 ${selectedFilter?.id === filter.id ? 'underline' : ''}`} onClick={() => setSelectedFilter(filter)}>{filter.name}</div>)}
      <button className='btn-transparent text-wrap' onClick={addFilter}>+ Add filter</button>
    </div>
  );

  const SelectedFilter = () => (
    selectedFilter &&
    <div className="flex w-2/3 flex-wrap">
      <div className='flex w-full mb-4 text-wrap'>
        <LabelInput className="font-semibold text-xl tracking-tight flex items-stretch justify-center" initialValue={selectedFilter.name} onChange={(value) => updateFilterName(value.toString())} />
      </div>
      <div className="w-2/5 pr-2 mb-2 font-semibold text-lg tracking-tight">Mod</div>
      <div className="w-1/5 px-2 mb-2 font-semibold text-lg tracking-tight">Condition</div>
      <div className="w-1/5 px-2 mb-2 font-semibold text-lg tracking-tight">Value/s</div>
      <div className="w-1/5 px-2 mb-2 font-semibold text-lg tracking-tight text-center">Remove</div>
      {selectedFilter.mods.map((mod, modIndex) => (
        <div key={modIndex} className='flex w-full'>
          <div className="w-2/5 mb-1 pr-2">
            <Dropdown
              key={`${modIndex}-id`}
              // @ts-ignore
              options={mods}
              initialValue={{ id: mod.id, text: mod.mod }}
              accessors={{ label: 'text', value: 'id' }}
              onChange={(option) => updateMod(modIndex, 'id', { id: option.id, mod: option.text })}
            />
          </div>

          <div className='w-1/5 px-2'>
            {0 < mod.values.length &&
              <Dropdown
                key={`${modIndex}-condition`}
                options={CONDITIONS}
                initialValue={CONDITIONS.find(option => option.value === mod.condition)}
                onChange={(option) => updateMod(modIndex, 'condition', option.value)}
              />
            }
          </div>
          <div key={`${modIndex}-values`} className='w-1/5 px-2 flex'>
            {mod.values.map((value, valueIndex) => (
              <span key={`${modIndex}-values-${valueIndex}`} className='max-w-20 mr-2'>
                <Input
                  key={`${modIndex}-values-${valueIndex}`}
                  className='max-w-20 border-solid border border-teal-500 rounded-lg px-2 h-10'
                  onlyIntegers
                  initialValue={value}
                  onChange={(value) => {
                    const values = mod.values;
                    values[valueIndex] = value as number;
                    updateMod(modIndex, 'values', values)
                  }}
                />
              </span>
            ))}
          </div>
          <div key={`${modIndex}-remove`} className='w-1/5 px-2 flex justify-center' onClick={() => removeMod(modIndex)}>
            <Icon color='red' path={mdiClose} size={1} />
          </div>
        </div>
      ))}
      <div className='w-full'>
        <button className='btn-transparent text-wrap mt-2' onClick={addMod}>+ Add mod</button>
      </div>

      <button className="btn-primary text-wrap mt-5" onClick={saveSelectedFilter}>Save</button>
    </div>
  );

  return (
    <>
      <NavBar />
      <div className="flex m-10">
        <Filters />
        <SelectedFilter />
      </div>
    </>
  )
}
