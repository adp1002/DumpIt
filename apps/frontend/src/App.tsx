import { Route, Routes, Navigate } from 'react-router-dom'
import { useState } from 'react';
import Login from './components/Login/Login'
import Filters from './components/Filters/Filters'
import Homepage from './components/Homepage/Homepage'
import Logout from './components/Logout/Logout';
import FilterTabs from './components/FilterTabs/FilterTabs';

function App () {
  const [token, setToken] = useState(localStorage.getItem('jwt'));

  const login = (token: string) => {
    localStorage.setItem('jwt', token);
    setToken(token);
  }

  const logout = () => {
    localStorage.removeItem('jwt');
    setToken(null);
  }

  if (null === token) {
    return (
      <Routes>
        <Route path='/login' element={<Login login={login} />} />
        <Route path='*' element={<Navigate to='/login' replace />} />
      </Routes>
    )
  }

  return (
    <>
      <Routes>
        <Route path='/logout' element={<Logout logout={logout} />} />
        <Route path='/' element={<Homepage />} />
        <Route path='/filters' element={<Filters />} />
        <Route path='/filter-tabs' element={<FilterTabs />} />
        <Route path='*' element={<Navigate to='/' replace />} />
      </Routes>
    </>
  )
}

export default App
