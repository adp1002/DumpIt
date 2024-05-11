import ReactDOM from 'react-dom/client'
import App from './App.tsx'
import './index.css'
import 'react-toastify/dist/ReactToastify.css';
import { BrowserRouter } from 'react-router-dom'
import { ToastContainer } from 'react-toastify'

ReactDOM.createRoot(document.getElementById('root')!).render(
  <>
    <ToastContainer position='top-center' theme='colored' autoClose={2000} />
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </>
)
