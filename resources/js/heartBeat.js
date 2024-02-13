let heartbeat;
document.addEventListener('DOMContentLoaded', () => {
  heartbeat = setInterval(() => {
    fetch('/heartbeat', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ active_session: true })
    });
  }, 1000); 
});