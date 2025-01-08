import { formatTimeToAmPm } from "../utils/date.js";
import { truncateText } from "../utils/text.js";
import { getOnGoingTripById } from "./get-dashboard.js";
import { DashboardModule } from "./init-dashboard.js";

export function renderOngoingTrips() {
  const tableBody = document.querySelector("#ongoingTripsTableBody");
  tableBody.innerHTML = ""; // Clear existing rows

  const ongoingTrips = DashboardModule.getOnGoingTrips()?.data;

  if (!ongoingTrips || ongoingTrips.length === 0) {
    // If no trips, insert a row with a message
    const row = document.createElement("tr");
    row.innerHTML = `
      <td colspan="6" class="fw-bold text-center">No On Going Trips</td>
    `;
    tableBody.appendChild(row);
  } else {
    ongoingTrips.forEach((trip) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${trip.vehicle}</td>
        <td>${trip.departure_date}</td>
        <td>${formatTimeToAmPm(trip.departure_time)}</td>
        <td class="text-start">${truncateText(trip?.departure_location, 20)}</td>
        <td>${trip.start_km ? `${trip.start_km} km` : "N/A"}</td>
        <td><button class="btn btn-success btn-sm end-trip-btn" data-trip-id="${trip.trip_id}">End</button></td>
      `;
      tableBody.appendChild(row);
    });
  }
}

export function updateDuration() {
  const trip = getOnGoingTripById(DashboardModule.getSelectedOnGoingTripId());
  const endDate = document.getElementById("endDate").value;
  const endTime = document.getElementById("endTime").value;

  if (!endDate || !endTime) {
    document.getElementById("duration").innerText = "Please provide a valid end date and time.";
    return;
  }
  const durationText = calculateDuration(trip.departure_date, trip.departure_time, endDate, endTime);
  document.getElementById("duration").innerText = durationText;
}

export function calculateDuration(startDate, startTime, endDate, endTime) {
  const startDateTime = new Date(`${startDate}T${startTime}:00`);
  const endDateTime = new Date(`${endDate}T${endTime}:00`);

  if (endDateTime < startDateTime) {
    return "End time cannot be before departure time.";
  }

  const durationMs = endDateTime - startDateTime;
  const durationMinutes = Math.floor(durationMs / (1000 * 60));
  const days = Math.floor(durationMinutes / (60 * 24));
  const hours = Math.floor((durationMinutes % (60 * 24)) / 60);
  const minutes = durationMinutes % 60;

  return `${days > 0 ? `${days} day${days !== 1 ? "s" : ""}, ` : ""}${hours} hour${hours !== 1 ? "s" : ""} and ${minutes} minute${minutes !== 1 ? "s" : ""}`;
}
