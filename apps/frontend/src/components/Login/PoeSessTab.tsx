import { FormEvent } from 'react'
import { authenticate, registerUser } from '../../services/api'

export default function PoeSessTab({ login }: LoginProps) {
  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault()

    const form = e.currentTarget
    const formData = new FormData(form)

    const body = { username: formData.get('username'), token: formData.get('token') };

    registerUser({ ...body, type: 'poesessid' })
      .then(() => authenticate(body))
      .then((data) => {
        if (null != data.token) {
          login(data.token)
        }

        // TODO some sort of error (toast?)
      })
  }

  return (
    <div className="bg-white rounded px-8 pt-6 pb-8 mb-4">
      <form method='post' onSubmit={handleSubmit}>
        <div className="mb-4">
          <label className="block text-gray-700 text-sm font-bold mb-2">
            Username
          </label>
          <input className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username" name='username' />
        </div>
        <div className="mb-6">
          <label className="block text-gray-700 text-sm font-bold mb-2">
            Password
          </label>
          <input className="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************" name='token' />
        </div>
        <div className="flex items-center justify-between">
          <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type='submit'>
            Log in
          </button>
        </div>
      </form>

    </div>
  )

  return (
    <>
      <form method='post' onSubmit={handleSubmit}>
        <label>
          Username: <input name='username' />
        </label>
        <label>
          POESESSID: <input name='token' type='password' />
        </label>
        <button type='submit'>
          Login
        </button>
      </form>
    </>
  )
}
