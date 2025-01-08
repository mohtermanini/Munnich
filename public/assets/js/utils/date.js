export function formatTimeToAmPm(time) {
  if (!time) return "N/A";
  const [hours, minutes] = time.split(":");
  const date = new Date();
  date.setHours(hours, minutes);
  return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", hour12: true });
}
