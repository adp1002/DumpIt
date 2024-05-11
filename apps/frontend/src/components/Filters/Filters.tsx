import { useEffect, useState } from 'react'
import { getFilters, getMods, saveFilter } from '../../services/api';
import { Filter } from '../../models/Filter';
import { CONDITIONS, FilterMod, Mod } from '../../models/FilterMod';
import Dropdown from '../BasicElements/Dropdown';
import NavBar from '../NavBar';
import Input from '../BasicElements/Input';
import { toast } from 'react-toastify';
import LabelInput from '../BasicElements/LabelInput';

export default function Filters() {
  const [filters, setFilters] = useState<Filter[]>([]);
  const [mods, setMods] = useState<Mod[]>([]);
  const [indexedMods, setIndexedMods] = useState<Record<string, Mod>>({});
  const [selectedFilter, setSelectedFilter] = useState<Filter | null>(null);

  useEffect(() => {
    getFilters().then((data) => setFilters(data.data));
    getMods().then(data => {
      setMods(data.data);

      const indexedMods: Record<string, Mod> = {};

      data.data.forEach((mod: Mod) => {
        indexedMods[mod.id] = mod;
      })

      setIndexedMods(indexedMods);
    })
  }, []);

  const generateNewMod = (modId?: string) => {
    const newModId = modId ?? mods[0].id;
    return {id: newModId, condition: 'gte', values: Array(indexedMods[newModId].placeholders).fill(0)}
  }

  const updateMod = (modIndex: number, property: keyof FilterMod, newValue: FilterMod[keyof FilterMod]) => {
    setSelectedFilter(prevFilter => {
      if (null === prevFilter) {
        return prevFilter;
      }

      const newFilter: Filter = { ...prevFilter };
      let newFilterMod = { ...newFilter.mods[modIndex] };

      switch (property) {
        case 'id':
          newFilterMod = generateNewMod(newValue as string);
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

      const newFilter: Filter = { ...prevFilter };

      newFilter.mods.push(generateNewMod())

      return newFilter;
    })
  }

  const removeMod = (modIndex: number) => {
    setSelectedFilter(prevFilter => {
      if (null === prevFilter) {
        return prevFilter;
      }

      const newFilter: Filter = { ...prevFilter };

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

  const generateNewFilter = (): Filter => {
    return {id: null, name: 'New filter', mods: []}
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

      return {...prevFilter, name}
    })
  }

  const Filters = () => (
    <div className="w-1/3 flex-wrap">
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
      <div className="w-2/5 mb-2 font-semibold text-lg tracking-tight">Mod</div>
      <div className="w-1/5 mb-2 font-semibold text-lg tracking-tight">Condition</div>
      <div className="w-1/5 mb-2 font-semibold text-lg tracking-tight">Value/s</div>
      <div className="w-1/5 mb-2 font-semibold text-lg tracking-tight text-center">Remove</div>
      {selectedFilter.mods.map((mod, modIndex) => (
        <div key={modIndex} className='flex w-full'>
          <Dropdown key={`${modIndex}-id`} className="w-2/5 mb-1" options={mods} initialValue={mod.id} accessors={{ label: 'text', value: 'id' }} onChange={(value) => updateMod(modIndex, 'id', value)} />
          <Dropdown key={`${modIndex}-condition`} className='w-1/5' options={CONDITIONS} initialValue={mod.condition} onChange={(value) => updateMod(modIndex, 'condition', value)} />
          <div key={`${modIndex}-values`} className='w-1/5 flex'>
            {mod.values.map((value, valueIndex) => (
              <span key={`${modIndex}-values-${valueIndex}`} className='max-w-20 mr-2'>
                <Input
                  key={`${modIndex}-values-${valueIndex}`}
                  className='max-w-20 border-solid border border-teal-500 rounded-lg px-2'
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
          <div key={`${modIndex}-remove`} className='w-1/5 text-center' onClick={() => removeMod(modIndex)}>x</div>
        </div>
      ))}
      <div className='w-full'>
        <button className='btn-transparent text-wrap' onClick={addMod}>+ Add mod</button>
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
