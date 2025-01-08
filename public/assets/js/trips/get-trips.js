import { generatePagination } from "../utils/pagination.js";
import { TripModule } from "./init-trips.js";
import { renderTrips } from "./ui-trips.js";

export async function fetchVehicles() {
  try {
    const response = await fetch(`index.php?page=getVehicles`);
    const vehicles = await response.json();
    const vehicleSelect = document.getElementById("vehicle");
    vehicles.forEach((vehicle) => {
      const option = document.createElement("option");
      option.value = vehicle.license_plate;
      option.textContent = vehicle.license_plate;
      vehicleSelect.appendChild(option);
    });
  } catch (error) {
    console.error("Error fetching vehicles:", error);
  }
}

export function fetchTrips(page = 1, limit = 10) {
  const fromDate = document.getElementById("fromDate").value;
  const toDate = document.getElementById("toDate").value;
  const status = document.getElementById("status").value;
  const vehicle = document.getElementById("vehicle").value;

  const params = new URLSearchParams({
    currentPage: page,
    limit,
    fromDate: fromDate || "", // Send empty string if no value
    toDate: toDate || "",
    status: status || "",
    vehicle: vehicle || "",
  });

  fetch(`index.php?page=getTrips&${params.toString()}`)
    .then((response) => response.json())
    .then(({ data, meta }) => {
      TripModule.setTripsData(data);
      renderTrips();
      generatePagination("trips-pagination-links", meta.totalPages, page, (newPage) => {
        TripModule.setCurrentPage(newPage);
        fetchTrips(newPage, limit);
      });
      document.getElementById("recordInfo").textContent = `Showing ${meta.startIndex} to ${meta.endIndex} of ${meta.totalRecords} records`;
    })
    .catch((error) => console.error("Error fetching trips data:", error));
}

export function getTripById(tripId) {
  return TripModule.getTripsData().find((t) => t.trip_id === parseInt(tripId));
}
