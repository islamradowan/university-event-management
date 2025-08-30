import Pusher from 'pusher-js'

const pusher = new Pusher('a68bdc09fdd21617929d', {
  cluster: 'mt1',
  encrypted: true,
  authEndpoint: 'http://localhost:8000/api/broadcasting/auth',
  auth: {
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
      'Accept': 'application/json'
    }
  }
})

export default pusher