import { formatTimeToAmPm } from "../utils/date.js";
import { truncateText } from "../utils/text.js";
import { getTripById } from "./get-trips.js";
import { TripModule } from "./init-trips.js";

export function renderTrips() {
  const tableBody = document.querySelector("#tripsTableBody");
  tableBody.innerHTML = "";

  const trips = TripModule.getTripsData();

  if (trips.length === 0) {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td colspan="15" class="text-center fw-bold text-muted">No data found</td>
    `;
    tableBody.appendChild(row);
    return;
  }

  const startIndex = (TripModule.getCurrentPage() - 1) * TripModule.getCurrentLimit();

  trips.forEach((trip, index) => {
    const rowNumber = startIndex + index + 1;
    const row = document.createElement("tr");
    const statusColor = trip.status === "Completed" ? "text-success" : "text-warning";
    const actions = `
    <div class="dropdown text-center">
      <button
    class="btn btn-dark btn-sm dropdown-toggle"
    type="button"
    id="dropdownMenuButton"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    <i class="bi bi-three-dots-vertical"></i>
  </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        ${
          trip.status === "In Progress"
            ? `
          <li>
            <button
              class="dropdown-item end-trip-btn"
              data-trip-id="${trip.trip_id}"
            >
              End Trip
            </button>
          </li>`
            : ""
        }
        <li>
          <button
            class="dropdown-item delete-trip-btn"
            data-trip-id="${trip.trip_id}"
            data-bs-toggle="modal"
            data-bs-target="#deleteTripModal"
          >
            Delete Trip
          </button>
        </li>
      </ul>
    </div>

  `;

    row.innerHTML = `
      <td>${actions}</td>
      <td>${rowNumber}</td>
      <td class="text-start">${trip.vehicle}</td>
      <td>${trip.departure_date}</td>
      <td>${formatTimeToAmPm(trip.departure_time)}</td>
      <td>${trip.start_km ? `${trip.start_km} km` : "N/A"}</td>
      <td class="text-start">${truncateText(trip.departure_location, 20)}</td>
      <td>${trip.arrival_date || "N/A"}</td>
      <td>${formatTimeToAmPm(trip.arrival_time)}</td>
      <td>${trip.end_km ? `${trip.end_km} km` : "N/A"}</td>
      <td class="text-start">${truncateText(trip?.arrival_location, 20) || "N/A"}</td>
      <td>${trip.start_km && trip.end_km ? `${(trip.end_km - trip.start_km).toFixed(2)} km` : "N/A"}</td>
      <td>
      ${
        trip.departure_date && trip.departure_time && trip.arrival_date && trip.arrival_time
          ? truncateText(calculateDuration(trip.departure_date, trip.departure_time, trip.arrival_date, trip.arrival_time), 15)
          : "N/A"
      } </td>
      <td class="text-start">${truncateText(trip?.purpose, 15) || "N/A"}</td>
      <td class="${statusColor}">${trip.status}</td>
    `;

    tableBody.appendChild(row);
  });
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

  const dayPart = days > 0 ? `${days} day${days !== 1 ? "s" : ""}` : "";
  const hourPart = hours > 0 ? `${hours} hour${hours !== 1 ? "s" : ""}` : "";
  const minutePart = minutes > 0 ? `${minutes} minute${minutes !== 1 ? "s" : ""}` : "";

  const parts = [dayPart, hourPart, minutePart].filter(Boolean);

  return parts.join(", ").replace(/, ([^,]*)$/, " and $1");
}

export function updateDuration() {
  const trip = getTripById(TripModule.getSelectedTripId());

  const endDate = document.getElementById("endDate").value;
  const endTime = document.getElementById("endTime").value;

  if (!endDate || !endTime) {
    document.getElementById("duration").innerText = "Please provide a valid end date and time.";
    return;
  }

  const durationText = calculateDuration(trip.departure_date, trip.departure_time, endDate, endTime);
  document.getElementById("duration").innerText = durationText;
}
