let heartbeat;
document.addEventListener('DOMContentLoaded', () => {
  heartbeat = setInterval(() => {
    fetch('msrvs/heartBeat.php');
  }, 1000); 
});