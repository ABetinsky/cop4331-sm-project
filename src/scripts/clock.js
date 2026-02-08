function updateClock() {
  const now = new Date();

  const datePart = now.toLocaleDateString(undefined, {
    weekday: "short",
    month: "short",
    day: "numeric",
  });

  const timePart = now.toLocaleTimeString(undefined, {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit"
  });

  document.getElementById("date").textContent = datePart;
  document.getElementById("time").textContent = timePart;
}

setInterval(updateClock, 1000);
updateClock();