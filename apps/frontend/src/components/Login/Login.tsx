import Tab from '../BasicElements/Tab'
import ApiTab from './ApiTab'
import PoeSessTab from './PoeSessTab'

export default function Login ({login}: LoginProps) {
  return (
    <>
      <div className='h-screen flex items-center justify-center'>
        <div className='max-w-sm rounded overflow-hidden shadow-lg m-auto'>
          <div className='text-xl font-bold text-center'>DumpIt</div>
          <Tab
            tabs={{
              Api: <ApiTab />,
              POESESSID: <PoeSessTab login={login}/>
            }}
          />
        </div>
      </div>
    </>
  )
}
