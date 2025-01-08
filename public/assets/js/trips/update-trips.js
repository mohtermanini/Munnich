import { displayErrors } from "../utils/forms.js";
import { ToastUtils } from "../utils/toast.js";
import { fetchTrips, fetchVehicles } from "./get-trips.js";
import { TripModule } from "./init-trips.js";

export function saveStartTrip() {
  const csrfToken = document.querySelector("#startTripForm input[name='csrfToken']").value;
  const licensePlate = document.getElementById("licensePlate").value;
  const startDate = document.getElementById("startDate").value;
  const startTime = document.getElementById("startTime").value;
  const startLocation = document.getElementById("startLocation").value;
  const startKm = document.getElementById("startKm").value;

  fetch("index.php?page=startTrip", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ csrfToken, licensePlate, startDate, startTime, startLocation, startKm }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.errors || data.error) {
        displayErrors({ formId: "startTripForm", validationErrors: data.errors, error: data.error });
      } else if (data.success) {
        const startTripModal = document.getElementById("startTripModal");
        const modalInstance = bootstrap.Modal.getInstance(startTripModal);
        modalInstance.hide();
        fetchTrips(TripModule.getCurrentPage(), TripModule.getCurrentLimit());
        fetchVehicles();
        ToastUtils.showSuccess("Trip has been started sucecssfully!");
      } else {
        console.error("Error:", data);
      }
    })
    .catch((error) => console.error("Error ending trip:", error));
}

export function saveEndTrip() {
  const tripId = TripModule.getSelectedTripId();

  if (!tripId) return;

  const csrfToken = document.querySelector("#endTripForm input[name='csrfToken']").value;
  const endDate = document.getElementById("endDate").value;
  const endTime = document.getElementById("endTime").value;
  const endLocation = document.getElementById("endLocation").value;
  const endKm = document.getElementById("endKm").value;
  const purpose = document.getElementById("purpose").value;

  fetch("index.php?page=endTrip", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ csrfToken, tripId, endDate, endTime, endLocation, endKm, purpose }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.errors || data.error) {
        displayErrors({ formId: "endTripForm", validationErrors: data.errors, error: data.error });
      } else if (data.success) {
        const endTripModal = document.getElementById("endTripModal");
        const modalInstance = bootstrap.Modal.getInstance(endTripModal);
        modalInstance.hide();
        fetchTrips(TripModule.getCurrentPage(), TripModule.getCurrentLimit());
        ToastUtils.showSuccess("Trip has been eneded sucecssfully!");
      } else {
        displayErrors({ formId: "endTripForm", validationErrors: data.errors, error: data.error });
        console.error("Error:", data);
      }
    })
    .catch((error) => console.error("Error ending trip:", error));
}
